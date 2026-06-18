<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DetalleCotizacion extends Model {
    protected $table = 'detalle_cotizacion';
    protected $primaryKey = 'id_detalle';
    public $timestamps = false;
    protected $fillable = ['id_cotizacion', 'descripcion', 'cantidad', 'precio_unit', 'subtotal'];
    
    public function cotizacion() {
        return $this->belongsTo(Cotizacion::class, 'id_cotizacion', 'id_cotizacion');
    }
}
