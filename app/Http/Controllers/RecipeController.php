<?php

namespace App\Http\Controllers;

use App\Core\CustomResponse;
use App\Models\Recipe;
use App\Models\RecipeHasIngredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class RecipeController extends Controller
{
  function getRecetas()
  {
    try {
      $data = Recipe::all();
      return CustomResponse::success('Lista de recetas', $data);
    } catch (\Throwable $th) {
      log($th->getMessage());
      return CustomResponse::failure();
    }
  }

  function createRecipe(Request $request) {
    $description = $request->input('descripcion');
    // $ingredients = $request->input('ingredientes');

    $validator = Validator::make($request->all(), [
      'descripcion' => 'required',
    ]);

    if ($validator->fails()) {
      return CustomResponse::failure('Datos faltantes');
    }

    try {
      $recipe = Recipe::create([
        'descripcion' => $description
      ]);

      // if ($recipe) {
      //   foreach ($ingredients as $value) {
      //     RecipeHasIngredient::create([
      //       'cme_receta_id_cme_receta' => $recipe->id_cme_receta,
      //       'lgt_ingrediente_id_cme_ingrediente' => $value['id_cme_ingrediente'],
      //       'cantidad' => $value['cantidad']
      //     ]);
      //   }
      // }

      return CustomResponse::success('Receta creada');
    } catch (\Throwable $th) {
      error_log($th);
      return CustomResponse::failure();
    }
  }

  function deleteRecetas(Request $request)
  {
    $id = $request->input('id_cme_receta');

    try {
      $data = Recipe::where('id_cme_receta', $id)->delete();

      return CustomResponse::success('Restaurante eliminado', $data);
    } catch (\Throwable $th) {
      error_log($th);
      return CustomResponse::failure();
    }
  }


  function getPlateRecipe(Request $request) {
    $codRecipe = $request->input('codReceta');

    try {
      $recipe = Recipe::where('id_cme_receta', '=', $codRecipe)
        ->first();

      $ingredients = DB::table('cme_receta_has_lgt_ingrediente')
        ->select(
          'cantidad',
          'descripcion as unidad_medida',
          'nombre',
          'id_cme_ingrediente'
        )
        ->join('lgt_ingrediente', 'lgt_ingrediente_id_cme_ingrediente', '=', 'id_cme_ingrediente')
        ->join('lgt_medida', 'id_lgt_medida', '=', 'lgt_medida_id_lgt_medida')
        ->where('cme_receta_id_cme_receta', '=', $codRecipe)
        ->get();

      $data = [
        'id_cme_receta' => $recipe->id_cme_receta,
        'descripcion' => $recipe->descripcion,
        'ingredientes' => $ingredients
      ];

      return CustomResponse::success('Receta creada', $data);
    } catch (\Throwable $th) {
      error_log($th);
      return CustomResponse::failure();
    }
  }
}
