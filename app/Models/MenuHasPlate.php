<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuHasPlate extends Model
{
  protected $table = 'vta_carta_has_vta_plato';
  public $timestamps = false;
  protected $fillable = [
    'vta_carta_id_vta_carta',
    'vta_plato_id_vta_plato',
  ];
}
