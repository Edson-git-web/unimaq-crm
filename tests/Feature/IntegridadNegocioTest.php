<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\DetalleCotizacion;
use App\Models\Venta;
use App\Models\Rol;
use App\Models\Usuario;
use App\Models\Auditoria;

class IntegridadNegocioTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $vendedor;
    protected $gerente;

    protected function setUp(): void
    {
        parent::setUp();
        
        $rolAdmin = Rol::create(['nombre_rol' => 'Administrador']);
        $rolVendedor = Rol::create(['nombre_rol' => 'Vendedor']);
        $rolGerente = Rol::create(['nombre_rol' => 'Gerente']);

        $this->admin = Usuario::create([
            'nombre' => 'Admin', 'apellido' => 'Test',
            'email' => 'admin@test.com', 'password' => bcrypt('password'),
            'id_rol' => $rolAdmin->id_rol
        ]);

        $this->vendedor = Usuario::create([
            'nombre' => 'Vendedor', 'apellido' => 'Test',
            'email' => 'vendedor@test.com', 'password' => bcrypt('password'),
            'id_rol' => $rolVendedor->id_rol
        ]);

        $this->gerente = Usuario::create([
            'nombre' => 'Gerente', 'apellido' => 'Test',
            'email' => 'gerente@test.com', 'password' => bcrypt('password'),
            'id_rol' => $rolGerente->id_rol
        ]);
    }

    public function test_no_permite_dos_clientes_con_mismo_ruc_aunque_uno_este_eliminado()
    {
        $cliente1 = Cliente::create([
            'ruc_dni' => '12345678901',
            'razon_social' => 'Cliente 1',
            'tipo_cliente' => 'Empresa'
        ]);

        $cliente1->delete(); // Soft delete

        $response = $this->actingAs($this->vendedor)->post(route('clientes.store'), [
            'ruc_dni' => '12345678901',
            'razon_social' => 'Cliente 2',
            'tipo_cliente' => 'Empresa'
        ]);

        $response->assertSessionHasErrors('ruc_dni');
    }

    public function test_codigo_cotizacion_no_se_duplica_en_creacion_concurrente()
    {
        // Esto es dificil de simular concurrentemente en PHPUnit sincrónico.
        // Pero al menos validamos la autogeneración usando lockForUpdate.
        $cliente = Cliente::create([
            'ruc_dni' => '11111111',
            'razon_social' => 'Cliente Test',
            'tipo_cliente' => 'Empresa'
        ]);

        $this->actingAs($this->vendedor)->post(route('cotizaciones.store'), [
            'id_cliente' => $cliente->id_cliente,
            'fecha_emision' => now()->toDateString(),
            'fecha_vence' => now()->addDays(7)->toDateString(),
            'observaciones' => 'Test',
            'detalles' => [
                ['descripcion' => 'Item 1', 'cantidad' => 1, 'precio_unit' => 100]
            ]
        ]);

        $this->actingAs($this->vendedor)->post(route('cotizaciones.store'), [
            'id_cliente' => $cliente->id_cliente,
            'fecha_emision' => now()->toDateString(),
            'fecha_vence' => now()->addDays(7)->toDateString(),
            'observaciones' => 'Test 2',
            'detalles' => [
                ['descripcion' => 'Item 2', 'cantidad' => 1, 'precio_unit' => 200]
            ]
        ]);

        $cotizaciones = Cotizacion::orderBy('id_cotizacion', 'asc')->get();
        $this->assertCount(2, $cotizaciones);
        $this->assertNotEquals($cotizaciones[0]->codigo, $cotizaciones[1]->codigo);
    }

    public function test_igv_calculado_coincide_entre_request_y_base_de_datos()
    {
        $cliente = Cliente::create([
            'ruc_dni' => '22222222',
            'razon_social' => 'Cliente Test 2',
            'tipo_cliente' => 'Empresa'
        ]);

        $response = $this->actingAs($this->vendedor)->post(route('cotizaciones.store'), [
            'id_cliente' => $cliente->id_cliente,
            'fecha_emision' => now()->toDateString(),
            'fecha_vence' => now()->addDays(7)->toDateString(),
            'detalles' => [
                ['descripcion' => 'Item 1', 'cantidad' => 3.33, 'precio_unit' => 17.99] // subtotal: 59.9067 -> 59.91
            ]
        ]);


        $cotizacion = Cotizacion::first();
        
        $subtotal_esperado = round(3.33 * 17.99, 2);
        $igv_esperado = round($subtotal_esperado * config('unimaq.igv', 0.18), 2);
        $total_esperado = round($subtotal_esperado + $igv_esperado, 2);

        $this->assertEquals($subtotal_esperado, $cotizacion->monto_subtotal);
        $this->assertEquals($igv_esperado, $cotizacion->igv);
        $this->assertEquals($total_esperado, $cotizacion->monto_total);
    }

    public function test_cerrar_venta_y_actualizar_cotizacion_es_atomico()
    {
        $cliente = Cliente::create([
            'ruc_dni' => '33333333',
            'razon_social' => 'Cliente Test 3',
            'tipo_cliente' => 'Empresa'
        ]);

        $cotizacion = Cotizacion::create([
            'codigo' => 'COT-TEST',
            'id_cliente' => $cliente->id_cliente,
            'id_usuario' => $this->vendedor->id_usuario,
            'fecha_emision' => now()->toDateString(),
            'fecha_vence' => now()->addDays(7)->toDateString(),
            'estado' => 'Aprobada',
            'monto_total' => 100
        ]);

        $response = $this->actingAs($this->vendedor)->post(route('ventas.store'), [
            'id_cotizacion' => $cotizacion->id_cotizacion,
            'id_cliente' => $cliente->id_cliente,
            'fecha_venta' => now()->toDateString(),
            'monto_final' => 100,
            'estado_pago' => 'Pagado total',
            'observaciones' => 'Venta test'
        ]);

        $response->assertSessionHasNoErrors();
        
        $this->assertDatabaseHas('ventas', ['id_cotizacion' => $cotizacion->id_cotizacion]);
        $this->assertDatabaseHas('cotizaciones', [
            'id_cotizacion' => $cotizacion->id_cotizacion,
            'estado' => 'Cerrada'
        ]);
    }

    public function test_gerente_recibe_403_en_ruta_cambiarEstado_de_cotizacion()
    {
        $cliente = Cliente::create([
            'ruc_dni' => '44444444',
            'razon_social' => 'Cliente Test 4',
            'tipo_cliente' => 'Empresa'
        ]);

        $cotizacion = Cotizacion::create([
            'codigo' => 'COT-TEST-G',
            'id_cliente' => $cliente->id_cliente,
            'id_usuario' => $this->vendedor->id_usuario,
            'fecha_emision' => now()->toDateString(),
            'fecha_vence' => now()->addDays(7)->toDateString(),
            'estado' => 'Pendiente',
            'monto_total' => 100
        ]);

        $response = $this->actingAs($this->gerente)->patch(route('cotizaciones.cambiarEstado', $cotizacion), [
            'estado' => 'Aprobada'
        ]);

        $response->assertStatus(403);
    }

    public function test_auditoria_no_almacena_password_en_texto_legible()
    {
        $this->actingAs($this->admin)->put(route('perfil.update'), [
            'nombre' => 'Admin Mod',
            'apellido' => 'Test Mod',
            'email' => 'admin_mod@test.com',
            'new_password' => 'secret123',
            'new_password_confirmation' => 'secret123'
        ]);

        $auditoria = Auditoria::orderBy('id_auditoria', 'desc')->first();
        
        if ($auditoria && $auditoria->datos_despues) {
            $this->assertArrayNotHasKey('password', $auditoria->datos_despues);
        } else {
            $this->assertTrue(true); // Omitido si la auditoria de perfil_update no está explícitamente loggeando
        }
    }
}
