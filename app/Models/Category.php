<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {
  protected $table = 'vta_categoria';
  protected $primaryKey = 'id_vta_categoria';
  public $timestamps = false;
  protected $fillable = [
    'descripcion_categoria',
    'ruta_icono_categoria',
    'cme_encargado_id_cme_encargado'
  ];
}
