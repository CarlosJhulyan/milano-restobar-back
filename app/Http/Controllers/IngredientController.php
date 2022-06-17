<?php

namespace App\Http\Controllers;

use App\Core\CustomResponse;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class IngredientController extends Controller
{
    function createIngredient(Request $request) {
      $name = $request->input('nombre');
      $price = $request->input('precioCompra');
      $stock = $request->input('stock');
      $status = $request->input('estado');
      $codMeasure = $request->input('codMedida');

      $validator = Validator::make($request->all(), [
        'nombre' => 'required',
        'precioCompra' => 'required',
        'stock' => 'required',
        'estado' => 'required',
        'codMedida' => 'required',
      ]);

      if ($validator->fails()) {
        return CustomResponse::failure('Datos faltantes');
      }

      try {
        Ingredient::create([
          'nombre' => $name,
          'precio_compra' => $price,
          'stock_fisico' => $stock,
          'estado' => $status,
          'lgt_medida_id_lgt_medida' => $codMeasure
        ]);

        return CustomResponse::success('Ingrediente creado');
      } catch (\Throwable $th) {
        error_log($th->getMessage());
        return CustomResponse::failure();
      }
    }

    function getIngredients() {
      try {
        $data = DB::table('lgt_ingrediente')
          ->select('id_cme_ingrediente', 'nombre', 'precio_compra', 'stock_fisico', 'descripcion as unidad_medida')
          ->join('lgt_medida', 'id_lgt_medida', '=', 'lgt_medida_id_lgt_medida')
          ->get();

        return CustomResponse::success('Listado de ingredientes', $data);
      } catch (\Throwable $th) {
        error_log($th->getMessage());
        return CustomResponse::failure();
      }
    }
}
