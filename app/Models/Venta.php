<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model {
    protected $table = 'ventas';
    protected $primaryKey = 'id_venta';
    public $timestamps = false;
    protected $fillable = ['codigo', 'id_cotizacion', 'id_cliente', 'id_usuario', 'fecha_venta', 'monto_final', 'estado_pago', 'observaciones'];
    
    public function cliente() {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }
    public function usuario() {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
    public function cotizacion() {
        return $this->belongsTo(Cotizacion::class, 'id_cotizacion', 'id_cotizacion');
    }
}
