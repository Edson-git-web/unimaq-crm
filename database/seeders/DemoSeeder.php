<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\DetalleCotizacion;
use App\Models\Venta;
use App\Models\Usuario;
use Faker\Factory as Faker;

class DemoSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('es_PE');

        $this->command->info('Iniciando inyección masiva de datos (Ponytail Ultra mode)...');

        // 1. Asegurar usuarios (vendedor y admin por si acaso)
        $vendedor = Usuario::firstOrCreate(
            ['email' => 'vendedor@unimaq.com'],
            ['nombre' => 'Juan', 'apellido' => 'Vendedor', 'password' => Hash::make('password'), 'id_rol' => 2, 'estado' => 1]
        );
        $admin = Usuario::firstOrCreate(
            ['email' => 'admin@unimaq.com'],
            ['nombre' => 'Admin', 'apellido' => 'Unimaq', 'password' => Hash::make('password'), 'id_rol' => 1, 'estado' => 1]
        );

        $this->command->info('Usuarios verificados. Creando 50 clientes...');

        // 2. Crear 50 Clientes
        $clientesIds = [];
        for ($i = 0; $i < 50; $i++) {
            $tipo = $faker->randomElement(['Persona Natural', 'Empresa']);
            $ruc_dni = $tipo === 'Empresa' ? '20' . $faker->numerify('#########') : $faker->numerify('########');
            
            $cliente = Cliente::create([
                'ruc_dni' => $ruc_dni,
                'razon_social' => $tipo === 'Empresa' ? $faker->company : $faker->name,
                'tipo_cliente' => $tipo,
                'email' => $faker->unique()->safeEmail,
                'telefono' => $faker->numerify('9########'),
                'direccion' => $faker->address,
                'estado' => 1,
            ]);
            $clientesIds[] = $cliente->id_cliente;
        }

        $this->command->info('Creando 70 cotizaciones...');

        // 3. Crear 70 Cotizaciones
        $cotizacionesIds = [];
        $estados = ['Pendiente', 'Aprobada', 'Rechazada', 'Cerrada', 'Expirada'];
        
        $maquinarias = [
            'Excavadora Hidráulica CAT 320', 'Cargador Frontal WA380', 'Retroexcavadora 420F', 
            'Rodillo Compactador', 'Tractor Oruga D6T', 'Motoniveladora 140K', 
            'Alquiler de Minicargador (Día)', 'Mantenimiento Preventivo 500H'
        ];

        for ($i = 0; $i < 70; $i++) {
            $subtotal = 0;
            $estado = $faker->randomElement($estados);
            $fechaEmision = $faker->dateTimeBetween('-3 months', 'now');
            $fechaVence = (clone $fechaEmision)->modify('+15 days');

            $maxId = Cotizacion::lockForUpdate()->max('id_cotizacion') ?? 0;
            $codigo = 'COT-' . str_pad($maxId + 1, 5, '0', STR_PAD_LEFT);

            $cotizacion = Cotizacion::create([
                'codigo' => $codigo,
                'id_cliente' => $faker->randomElement($clientesIds),
                'id_usuario' => $vendedor->id_usuario,
                'fecha_emision' => $fechaEmision->format('Y-m-d'),
                'fecha_vence' => $fechaVence->format('Y-m-d'),
                'estado' => $estado,
                'monto_subtotal' => 0,
                'igv' => 0,
                'monto_total' => 0,
                'observaciones' => $faker->optional()->sentence,
            ]);

            // Agregar 1 a 4 detalles
            $numDetalles = rand(1, 4);
            for ($d = 0; $d < $numDetalles; $d++) {
                $cantidad = $faker->randomFloat(2, 1, 5);
                $precio = $faker->randomFloat(2, 100, 5000);
                
                DetalleCotizacion::create([
                    'id_cotizacion' => $cotizacion->id_cotizacion,
                    'descripcion' => $faker->randomElement($maquinarias),
                    'cantidad' => $cantidad,
                    'precio_unit' => $precio,
                ]);
                $subtotal += ($cantidad * $precio);
            }

            $igv = round($subtotal * config('unimaq.igv', 0.18), 2);
            $total = round($subtotal + $igv, 2);

            $cotizacion->update([
                'monto_subtotal' => $subtotal,
                'igv' => $igv,
                'monto_total' => $total,
            ]);

            if ($estado === 'Cerrada' || $estado === 'Aprobada') {
                $cotizacionesIds[] = $cotizacion;
            }
        }

        $this->command->info('Creando 40 ventas...');

        // 4. Crear Ventas a partir de las Cotizaciones Aprobadas/Cerradas
        $countVentas = 0;
        foreach ($cotizacionesIds as $cot) {
            if ($countVentas >= 40) break;
            
            $maxIdVenta = Venta::lockForUpdate()->max('id_venta') ?? 0;
            $codigoVenta = 'VEN-' . str_pad($maxIdVenta + 1, 5, '0', STR_PAD_LEFT);

            Venta::create([
                'codigo' => $codigoVenta,
                'id_cotizacion' => $cot->id_cotizacion,
                'id_cliente' => $cot->id_cliente,
                'id_usuario' => $vendedor->id_usuario,
                'fecha_venta' => (new \DateTime($cot->fecha_emision))->modify('+' . rand(1, 5) . ' days')->format('Y-m-d'),
                'monto_final' => $cot->monto_total,
                'estado_pago' => $faker->randomElement(['Pendiente', 'Pagado parcial', 'Pagado total']),
                'observaciones' => 'Venta generada por DemoSeeder',
            ]);
            $countVentas++;
        }

        $this->command->info('¡Inyección masiva completada! 50 Clientes, 70 Cotizaciones, 40 Ventas.');
    }
}
