<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plate extends Model
{
    protected $table = 'vta_plato';
    protected $primaryKey = 'id_vta_plato';
    public $timestamps = false;
    protected $fillable = [
      'vta_nombre_plato',
      'vta_desc_plato',
      'vta_ruta_imagen_plato',
      'vta_precio',
      'vta_dificultad_id_vta_dificultad',
      'vta_categoria_id_vta_categoria',
      'id_cme_receta'
    ];
}
