<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
  protected $table = 'vta_mesa';
  protected $primaryKey = 'id_vta_mesa';
  public $timestamps = false;
  protected $fillable = [
    'vta_numero_mesa',
    'lgt_restaurante_id_lgt_restaurante'
  ];
}
