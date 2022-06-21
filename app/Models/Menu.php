<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
  protected $table = 'vta_carta';
  protected $primaryKey = 'id_vta_carta';
  public $timestamps = false;
  protected $fillable = [
    'lgt_restaurante_id_lgt_restaurante',
    'vta_descripcion_carta'
  ];
}
