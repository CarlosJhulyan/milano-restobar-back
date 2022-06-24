<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
  protected $table = 'lgt_restaurante';
  protected $primaryKey = 'id_lgt_restaurante';
  public $timestamps = false;
  protected $fillable = [
    'lgt_nombre_resturante',
    'lg_ruc_resturante',
    'lgt_razon_restaurante',
    'lgt_direccion_restaurante',
    'lgt_horario_apertura',
    'lgt_horario_cierre'
  ];
}
