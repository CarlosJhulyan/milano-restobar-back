<?php

namespace App\Http\Controllers;

use App\Models\Plate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\core\CustomResponse;
use Illuminate\Support\Facades\Validator;

class PlateController extends Controller
{
  function createPlate(Request $request) {
    $image = $request->file('imagen');
    $description = $request->input('descripcion');
    $price = $request->input('precio');
    $name = $request->input('nombre');
    $codCategoty = $request->input('codCategoria');
    $codRecipe = $request->input('codReceta');
    $codMenu = $request->input('codCarta');
    $codDifficulty = $request->input('codDificultad');

    $validator = Validator::make($request->all(), [
      'imagen' => 'required',
      'precio' => 'required',
      'nombre' => 'required',
      'codCategoria' => 'required',
      'codReceta' => 'required',
      'codCarta' => 'required',
      'codDificultad' => 'required',
      'description' => 'required'
    ]);

    if ($validator->fails()) {
      CustomResponse::failure('Datos faltantes');
    }

    try {
      $nameImage = uniqid('IMG', false) . '.' .  $image->extension();

      $plate = Plate::create([
        'vta_nombre_plato' => $name,
        'vta_desc_plato' => $description,
        'vta_ruta_imagen_plato' => $nameImage,
        'vta_precio' => $price,
        'id_vta_carta' => $codMenu,
        'vta_dificultad_id_vta_dificultad' => $codDifficulty,
        'vta_categoria_id_vta_categoria' => $codCategoty,
        'id_cme_receta' => $codRecipe
      ]);

      if ($plate) {
        $image->move(public_path('plates/'), $nameImage);
      }

      return CustomResponse::success('Platillo creado');
    } catch (\Throwable $th) {
      error_log($th->getMessage());
      return CustomResponse::failure();
    }
  }

  function getPlates() {
    try {
      $data = DB::table('vta_plato')
        ->select(
          'id_vta_plato',
          'vta_nombre_plato',
          'vta_desc_plato',
          'descripcion_categoria as vta_categoria_plato',
          'vta_descripcion as vta_dificultad_plato',
          'vta_ruta_imagen_plato',
          'vta_precio',
          'vta_peso_dificultad',
          'id_vta_carta',
          'id_cme_receta'
        )
        ->join('vta_categoria', 'id_vta_categoria', '=', 'vta_categoria_id_vta_categoria')
        ->join('vta_dificultad', 'id_vta_dificultad', '=', 'vta_dificultad_id_vta_dificultad')
        ->orderByDesc('id_vta_plato')
        ->get();
      return CustomResponse::success('Listado de platos', $data);
    } catch (\Throwable $th) {
      return CustomResponse::failure();
    }
  }

  function getPlatesByCodMenu(Request $request) {
    $codMenu = $request->input('codCarta');

    try {
      $data = DB::table('vta_plato')
        ->select(
          'id_vta_plato',
          'vta_nombre_plato',
          'vta_desc_plato',
          'descripcion_categoria as vta_categoria_plato',
          'vta_descripcion as vta_dificultad_plato',
          'vta_ruta_imagen_plato',
          'vta_precio',
          'vta_peso_dificultad',
          'id_vta_carta',
          'id_cme_receta'
        )
        ->join('vta_categoria', 'id_vta_categoria', '=', 'vta_categoria_id_vta_categoria')
        ->join('vta_dificultad', 'id_vta_dificultad', '=', 'vta_dificultad_id_vta_dificultad')
        ->orderByDesc('id_vta_plato')
        ->where('id_vta_carta', '=', $codMenu)
        ->get();

      return CustomResponse::success('Listado de platos', $data);
    } catch (\Throwable $th) {
      return CustomResponse::failure();
    }
  }
}