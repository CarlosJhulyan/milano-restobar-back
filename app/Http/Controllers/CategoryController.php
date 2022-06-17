<?php

namespace App\Http\Controllers;

use App\Core\CustomResponse;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
  function createCategory(Request $request) {
    $description = $request->input('descripcion');
    $image = $request->file('imagen');
    $codServiceManager = $request->input('codEncargado');

    $validator = Validator::make($request->all(), [
      'descripcion' => 'required',
      'imagen' => 'required',
      'codEncargado' => 'required'
    ]);

    if ($validator->fails()) {
      return CustomResponse::failure('Datos faltantes');
    }

    try {
      $nameImage = uniqid('IMG', false) . '.' .  $image->extension();
      $category = Category::create([
        'descripcion_categoria' => $description,
        'ruta_icono_categoria' => $nameImage,
        'cme_encargado_id_cme_encargado' => $codServiceManager
      ]);

      if ($category) {
        $image->move(public_path('categories/'), $nameImage);
      }

      return CustomResponse::success('Categoría creada');
    } catch (\Throwable $th) {
      error_log($th->getMessage());
      return CustomResponse::failure();
    }
  }
}
