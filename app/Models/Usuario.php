<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable {
    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;
    protected $fillable = ['nombre', 'apellido', 'email', 'password', 'id_rol', 'estado'];
    protected $hidden = ['password'];
    
    public function rol() {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }
}
