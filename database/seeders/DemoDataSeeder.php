<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\DetalleCotizacion;
use App\Models\Venta;
use App\Models\Usuario;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('es_PE');
        $usuario = Usuario::first(); // Asume que el Admin ya existe

        if (!$usuario) return;

        $conceptos = [
            'Alquiler de Excavadora Hidráulica', 'Mantenimiento Preventivo de Tractor',
            'Alquiler de Minicargador', 'Repuestos para Retroexcavadora',
            'Alquiler de Montacargas', 'Servicio de Diagnóstico Electrónico',
            'Venta de Filtros Originales', 'Alquiler de Rodillo Compactador',
            'Mantenimiento de Grupo Electrógeno', 'Servicio de Cambio de Aceite Industrial'
        ];

        for ($i = 0; $i < 50; $i++) {
            $esEmpresa = $faker->boolean(75);
            $cliente = Cliente::create([
                'razon_social' => $esEmpresa ? $faker->company : $faker->name,
                'ruc_dni' => $esEmpresa ? $faker->numerify('20########') : $faker->numerify('########'),
                'direccion' => $faker->address,
                'telefono' => $faker->numerify('9########'),
                'email' => $faker->unique()->safeEmail,
                'tipo_cliente' => $esEmpresa ? 'Empresa' : 'Persona Natural',
            ]);

            $numCotizaciones = rand(1, 3);
            for ($j = 0; $j < $numCotizaciones; $j++) {
                $estado = $faker->randomElement(['Pendiente', 'Aprobada', 'Rechazada']);
                $fechaEmision = $faker->dateTimeBetween('-4 months', 'now');
                $fechaVence = (clone $fechaEmision)->modify('+15 days');

                $cotizacion = Cotizacion::create([
                    'codigo' => 'COT-' . strtoupper(Str::random(6)),
                    'id_cliente' => $cliente->id_cliente,
                    'id_usuario' => $usuario->id_usuario,
                    'fecha_emision' => $fechaEmision,
                    'fecha_vence' => $fechaVence,
                    'estado' => $estado,
                    'observaciones' => 'Generado automáticamente para demostración.',
                    'monto_subtotal' => 0,
                    'igv' => 0,
                    'monto_total' => 0
                ]);

                $numDetalles = rand(1, 4);
                $montoSubtotal = 0;
                for ($k = 0; $k < $numDetalles; $k++) {
                    $cantidad = rand(1, 5);
                    $precioUnit = $faker->randomFloat(2, 500, 8000);
                    $subtotal = $cantidad * $precioUnit;
                    $montoSubtotal += $subtotal;

                    DetalleCotizacion::create([
                        'id_cotizacion' => $cotizacion->id_cotizacion,
                        'descripcion' => $faker->randomElement($conceptos),
                        'cantidad' => $cantidad,
                        'precio_unit' => $precioUnit
                    ]);
                }

                $igv = $montoSubtotal * 0.18;
                $montoTotal = $montoSubtotal + $igv;
                $cotizacion->update([
                    'monto_subtotal' => $montoSubtotal,
                    'igv' => $igv,
                    'monto_total' => $montoTotal
                ]);

                if ($estado === 'Aprobada') {
                    $fechaVenta = $faker->dateTimeBetween($fechaEmision, $fechaVence);
                    Venta::create([
                        'codigo' => 'VEN-' . strtoupper(Str::random(6)),
                        'id_cotizacion' => $cotizacion->id_cotizacion,
                        'id_cliente' => $cliente->id_cliente,
                        'id_usuario' => $usuario->id_usuario,
                        'fecha_venta' => $fechaVenta,
                        'monto_final' => $montoTotal
                    ]);
                }
            }
        }
    }
}
