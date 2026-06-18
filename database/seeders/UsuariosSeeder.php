<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuariosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('usuarios')->insert([
            [
                'nombre' => 'Admin',
                'apellido' => 'Sistema',
                'email' => 'admin@unimaq.com',
                'password' => Hash::make('password123'),
                'id_rol' => 1, // Administrador
                'estado' => 1,
            ],
            [
                'nombre' => 'Vendedor',
                'apellido' => 'Prueba',
                'email' => 'vendedor@unimaq.com',
                'password' => Hash::make('password123'),
                'id_rol' => 2, // Vendedor
                'estado' => 1,
            ]
        ]);
    }
}
