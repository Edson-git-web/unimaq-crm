<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            ['nombre_rol' => 'Administrador', 'descripcion' => 'Acceso total al sistema'],
            ['nombre_rol' => 'Vendedor', 'descripcion' => 'Gestión de clientes, cotizaciones y ventas'],
            ['nombre_rol' => 'Gerente', 'descripcion' => 'Solo lectura: dashboards y reportes'],
        ]);
    }
}
