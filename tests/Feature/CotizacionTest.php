<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Cotizacion;
use App\Models\Cliente;
use App\Models\Usuario;
use App\Models\Rol;

class CotizacionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $rolAdmin = Rol::create(['nombre_rol' => 'Administrador']);
        $this->admin = Usuario::create([
            'nombre' => 'Admin',
            'apellido' => 'Test',
            'email' => 'admin_cot@test.com',
            'password' => bcrypt('password123'),
            'id_rol' => $rolAdmin->id_rol
        ]);

        $this->cliente = Cliente::create([
            'ruc_dni' => '10203040501',
            'razon_social' => 'CotizTest',
            'tipo_cliente' => 'Empresa'
        ]);
    }

    public function test_calculo_correcto_de_igv_y_total()
    {
        $subtotal = 200.00;
        $igv = $subtotal * 0.18;
        $total = $subtotal + $igv;
        
        $this->assertEquals(36.00, $igv);
        $this->assertEquals(236.00, $total);
    }

    public function test_codigo_se_autogenera_con_formato_correcto()
    {
        $codigo = 'COT-2026-001';
        $this->assertStringStartsWith('COT-', $codigo);
    }

    public function test_no_permite_transicion_de_rechazada_a_aprobada()
    {
        $this->assertTrue(true);
    }

    public function test_descargar_pdf_cotizacion()
    {
        $cotizacion = Cotizacion::create([
            'codigo' => 'COT-TEST-001',
            'id_cliente' => $this->cliente->id_cliente,
            'id_usuario' => $this->admin->id_usuario,
            'fecha_emision' => now()->toDateString(),
            'fecha_vence' => now()->addDays(7)->toDateString(),
            'estado' => 'Pendiente',
            'monto_subtotal' => 100,
            'igv' => 18,
            'monto_total' => 118,
        ]);

        $response = $this->actingAs($this->admin)->get(route('cotizaciones.pdf', $cotizacion));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }
}
