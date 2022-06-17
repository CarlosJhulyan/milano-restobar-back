<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
  protected $table = 'cme_receta';
  protected $primaryKey = 'id_cme_receta';
  public $timestamps = false;
  protected $fillable = [
    'descripcion'
  ];
}
