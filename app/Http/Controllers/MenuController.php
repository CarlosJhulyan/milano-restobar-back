<?php

namespace App\Http\Controllers;

use App\Core\CustomResponse;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    function createMenu(Request $request) {
      $codRestaurant = $request->input('codRestaurante');
      $description = $request->input('descripcion');

      $validator = Validator::make($request->all(), [
        'codRestaurante' => 'required',
        'descripcion' => 'required'
      ]);

      if ($validator->fails()) {
        return CustomResponse::failure('Datos Faltantes');
      }

      try {
        Menu::create([
          'lgt_restaurante_id_lgt_restaurante' => $codRestaurant,
          'vta_descripcion_carta' => $description
        ]);

        return CustomResponse::success('Carta creada');
      } catch (\Throwable $th) {
        error_log($th->getMessage());
        return CustomResponse::failure();
      }
    }

  function deleteMenu(Request $request) {
    $codMenu = $request->input('codCarta');

    $validator = Validator::make($request->all(), [
      'codCarta' => 'required',
    ]);

    if ($validator->fails()) {
      return CustomResponse::failure('Datos Faltantes');
    }

    try {
      Menu::where('id_vta_carta', '=', $codMenu)
        ->delete();

      return CustomResponse::success('Carta eliminada');
    } catch (\Throwable $th) {
      error_log($th);
      return CustomResponse::failure();
    }
  }

    function getMenus() {
      try {
        $data = DB::table('vta_carta')
          ->select(
            'id_vta_carta',
            'vta_descripcion_carta',
            'lgt_nombre_resturante as vta_restaurante_carta',
            'lgt_restaurante_id_lgt_restaurante'
          )
          ->join('lgt_restaurante', 'id_lgt_restaurante', '=', 'lgt_restaurante_id_lgt_restaurante')
          ->get();

        return CustomResponse::success('Lista de cartas', $data);
      } catch (\Throwable $th) {
        error_log($th->getMessage());
        return CustomResponse::failure();
      }
    }

    function getMenusByRestaurant(Request $request) {
      $codRestaurant = $request->input('codRestaurante');

      try {
        $data = Menu::all()
          ->where('lgt_restaurante_id_lgt_restaurante', '=', $codRestaurant);
        return CustomResponse::success('Lista de cartas', $data);
      } catch (\Throwable $th) {
        error_log($th->getMessage());
        return CustomResponse::failure();
      }
    }
}
