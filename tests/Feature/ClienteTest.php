<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Cliente;
use App\Models\Usuario;
use App\Models\Rol;

class ClienteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $rolAdmin = Rol::create(['nombre_rol' => 'Administrador']);
        $this->admin = Usuario::create([
            'nombre' => 'Admin',
            'apellido' => 'Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password123'),
            'id_rol' => $rolAdmin->id_rol
        ]);
        
        $rolVendedor = Rol::create(['nombre_rol' => 'Vendedor']);
        $this->vendedor = Usuario::create([
            'nombre' => 'Vendedor',
            'apellido' => 'Test',
            'email' => 'vendedor@test.com',
            'password' => bcrypt('password123'),
            'id_rol' => $rolVendedor->id_rol
        ]);
    }

    public function test_registrar_cliente_con_datos_validos()
    {
        $response = $this->actingAs($this->admin)->post('/clientes', [
            'ruc_dni' => '12345678901',
            'razon_social' => 'Cliente Valido SAC',
            'tipo_cliente' => 'Empresa',
            'email' => 'cliente@test.com'
        ]);

        $response->assertRedirect('/clientes');
        $this->assertDatabaseHas('clientes', [
            'ruc_dni' => '12345678901'
        ]);
    }

    public function test_no_registrar_cliente_con_ruc_duplicado()
    {
        Cliente::create([
            'ruc_dni' => '12345678901',
            'razon_social' => 'Existente',
            'tipo_cliente' => 'Empresa'
        ]);

        $response = $this->actingAs($this->admin)->post('/clientes', [
            'ruc_dni' => '12345678901',
            'razon_social' => 'Duplicado SAC',
            'tipo_cliente' => 'Empresa'
        ]);

        $response->assertSessionHasErrors(['ruc_dni']);
        $this->assertEquals(1, Cliente::where('ruc_dni', '12345678901')->count());
    }

    public function test_vendedor_puede_o_no_eliminar_cliente()
    {
        $cliente = Cliente::create([
            'ruc_dni' => '999999999',
            'razon_social' => 'A Eliminar',
            'tipo_cliente' => 'Empresa'
        ]);

        // Ambos roles (Admin y Vendedor) tienen acceso al Route::resource('clientes') según el Kernel en la Fase 2,
        // Pero verifiquemos que se haga la petición correctamente.
        $response = $this->actingAs($this->vendedor)->delete('/clientes/' . $cliente->id_cliente);
        $response->assertRedirect('/clientes');
        
        $this->assertDatabaseHas('clientes', ['id_cliente' => $cliente->id_cliente]);
        $this->assertNotNull($cliente->fresh()->deleted_at);
    }
}
