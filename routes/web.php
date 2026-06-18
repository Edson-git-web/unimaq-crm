<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AuditoriaController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Auth::routes();

Route::get('/home', function() {
    return redirect()->route('dashboard');
})->name('home');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware(['rol:Administrador,Vendedor'])->group(function () {
        Route::resource('clientes', ClienteController::class)->except(['show']);
        Route::resource('cotizaciones', CotizacionController::class);
        Route::patch('cotizaciones/{cotizacion}/estado', [CotizacionController::class, 'cambiarEstado'])
             ->name('cotizaciones.cambiarEstado');
        Route::get('cotizaciones/{cotizacion}/pdf', [CotizacionController::class, 'exportarPdfCotizacion'])
             ->name('cotizaciones.pdf');
        Route::resource('ventas', VentaController::class)->except(['edit','update','destroy']);
    });

    Route::prefix('reportes')->name('reportes.')->group(function () {
        Route::get('/',      [ReporteController::class, 'index'])->name('index');
        Route::get('/pdf',   [ReporteController::class, 'exportarPdf'])->name('pdf');
        Route::get('/excel', [ReporteController::class, 'exportarExcel'])->name('excel');
    });

    Route::middleware(['rol:Administrador'])->group(function () {
        Route::resource('usuarios', UsuarioController::class)->except(['show']);
        Route::get('auditoria', [AuditoriaController::class, 'index'])->name('auditoria.index');
    });
});
