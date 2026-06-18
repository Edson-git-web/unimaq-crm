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

    // ponytail: El DDL estricto no incluye remember_token, así que lo desactivamos para evitar crashes al hacer check en "Remember Me"
    public function getRememberToken() { return null; }
    public function setRememberToken($value) { }
    public function getRememberTokenName() { return null; }
}
