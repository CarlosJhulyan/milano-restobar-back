<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model {
  protected $table = 'cme_usuario';
  protected $primaryKey = 'id_cme_usuario';
  public const CREATED_AT = 'fecha_creacion';
  public const UPDATED_AT = 'fecha_modificacion';
  protected $fillable = [
    'usuario',
    'clave',
    'nombre',
    'apellido_paterno',
    'apellido_materno',
    'telefono',
    'celular',
    'correo',
    'fecha_nacimiento',
    'dni',
    'direccion',
    'tipo_usuario',
    'avatar'
  ];
}
