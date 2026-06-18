<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model {
    protected $table = 'cotizaciones';
    protected $primaryKey = 'id_cotizacion';
    public $timestamps = false;
    protected $fillable = ['codigo', 'id_cliente', 'id_usuario', 'fecha_emision', 'fecha_vence', 'monto_subtotal', 'igv', 'monto_total', 'estado', 'observaciones'];
    
    public function cliente() {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }
    public function usuario() {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
    public function detalles() {
        return $this->hasMany(DetalleCotizacion::class, 'id_cotizacion', 'id_cotizacion');
    }
}
