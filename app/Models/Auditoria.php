<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    use HasFactory;

    protected $table = 'auditoria';
    protected $primaryKey = 'id_auditoria';
    public $timestamps = false; // Only has fecha_hora, manually managed or by DB default

    protected $fillable = [
        'id_usuario',
        'accion',
        'tabla_afectada',
        'registro_id',
        'datos_antes',
        'datos_despues',
        'ip_origen',
        'user_agent',
    ];

    protected $casts = [
        'datos_antes' => 'array',
        'datos_despues' => 'array',
        'fecha_hora' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}
