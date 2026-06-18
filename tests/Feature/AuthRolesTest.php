<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Usuario;
use App\Models\Rol;

class AuthRolesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->vendedorRol = Rol::create(['nombre_rol' => 'Vendedor']);
        $this->gerenteRol = Rol::create(['nombre_rol' => 'Gerente']);
        
        $this->vendedor = Usuario::create([
            'nombre' => 'Vend', 'apellido' => 'Test',
            'email' => 'v@test.com', 'password' => bcrypt('12312312'), 'id_rol' => $this->vendedorRol->id_rol
        ]);

        $this->gerente = Usuario::create([
            'nombre' => 'Ger', 'apellido' => 'Test',
            'email' => 'g@test.com', 'password' => bcrypt('12312312'), 'id_rol' => $this->gerenteRol->id_rol
        ]);
    }

    public function test_vendedor_recibe_403_en_ruta_de_usuarios()
    {
        $response = $this->actingAs($this->vendedor)->get('/usuarios');
        $response->assertStatus(403);
    }

    public function test_gerente_recibe_403_al_intentar_crear_cliente()
    {
        $response = $this->actingAs($this->gerente)->post('/clientes', []);
        $response->assertStatus(403);
    }

    public function test_login_con_credenciales_invalidas_no_autentica()
    {
        $response = $this->post('/login', [
            'email' => 'v@test.com',
            'password' => 'wrongpassword'
        ]);

        $this->assertGuest();
    }
}
