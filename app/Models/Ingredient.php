<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
  protected $table = 'lgt_ingrediente';
  protected $primaryKey = 'id_cme_ingrediente';
  public $timestamps = false;
  protected $fillable = [
    'nombre',
    'precio_compra',
    'stock_fisico',
    'estado',
    'lgt_medida_id_lgt_medida'
  ];
}
