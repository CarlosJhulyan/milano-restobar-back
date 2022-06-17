<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecipeHasIngredient extends Model
{
  protected $table = 'cme_receta_has_lgt_ingrediente';
  public $timestamps = false;
  protected $fillable = [
    'lgt_ingrediente_id_cme_ingrediente',
    'cme_receta_id_cme_receta',
    'cantidad'
  ];
}
