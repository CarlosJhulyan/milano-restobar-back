<?php

namespace App\Http\Controllers;

use App\Core\CustomResponse;
use App\Models\Restaurant;
use App\Models\Table;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
  function getRestaurants() {
    try {
      $data = Restaurant::all();
      return CustomResponse::success('Lista de restaurantes', $data);
    } catch (\Throwable $th) {
      log($th->getMessage());
      return CustomResponse::failure();
    }
  }
  function getTablesByRestaurant(Request $request) {
    $codRestaurant = $request->input('codRestaurante');

    try {
      $data = Table::all()
        ->where('lgt_restaurante_id_lgt_restaurante', '=', $codRestaurant);
      return CustomResponse::success('Lista de mesas por restaurante', $data);
    } catch (\Throwable $th) {
      log($th->getMessage());
      return CustomResponse::failure();
    }
  }
}
