<?php

namespace App\Http\Controllers;

use App\Core\CustomResponse;
use App\Models\Restaurant;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RestaurantController extends Controller
{
  function getRestaurants()
  {
    try {
      $data = Restaurant::all();
      return CustomResponse::success('Lista de restaurantes', $data);
    } catch (\Throwable $th) {
      log($th->getMessage());
      return CustomResponse::failure();
    }
  }

  function createRestaurants(Request $request)
  {
    $lgt_nombre_resturante = $request->input('lgt_nombre_resturante');
    $lg_ruc_resturante = $request->input('lg_ruc_resturante');
    $lgt_razon_restaurante = $request->input('lgt_razon_restaurante');
    $lgt_direccion_restaurante = $request->input('lgt_direccion_restaurante');
    $lgt_horario_apertura = $request->input('lgt_horario_apertura');
    $lgt_horario_cierre = $request->input('lgt_horario_cierre');

    $validator = Validator::make($request->all(), [
      'lgt_nombre_resturante' => 'required',
      'lg_ruc_resturante' => 'required',
      'lgt_razon_restaurante' => 'required',
      'lgt_direccion_restaurante' => 'required',
      'lgt_horario_apertura' => 'required',
      'lgt_horario_cierre' => 'required'
    ]);

    if ($validator->fails()) {
      return CustomResponse::failure('Datos faltantes');
    }

    try {

      $model = Restaurant::create([
        'lgt_nombre_resturante' => $lgt_nombre_resturante,
        'lg_ruc_resturante' => $lg_ruc_resturante,
        'lgt_razon_restaurante' => $lgt_razon_restaurante,
        'lgt_direccion_restaurante' => $lgt_direccion_restaurante,
        'lgt_horario_apertura' => $lgt_horario_apertura,
        'lgt_horario_cierre' => $lgt_horario_cierre
      ]);

      return CustomResponse::success('Restaurante creado');
    } catch (\Throwable $th) {
      error_log($th);
      return CustomResponse::failure();
    }
  }

  function deleteRestaurants($idrestaurant)
  {
    try {
      $data = Restaurant::where('id_lgt_restaurante', $idrestaurant)->delete();

      return CustomResponse::success('Restaurante eliminado', $data);
    } catch (\Throwable $th) {
      error_log($th);
      return CustomResponse::failure();
    }
  }

  function getTablesByRestaurant(Request $request)
  {
    $codRestaurant = $request->input('codRestaurante');

    try {
      $data = Table::select('*')
        ->join('lgt_restaurante', 'id_lgt_restaurante', '=', 'lgt_restaurante_id_lgt_restaurante')
        ->where('lgt_restaurante_id_lgt_restaurante', '=', $codRestaurant)
        ->get();
      return CustomResponse::success('Lista de mesas por restaurante', $data);
    } catch (\Throwable $th) {
      error_log($th->getMessage());
      return CustomResponse::failure();
    }
  }

  function createTable(Request $request) {
    $codRestaurant = $request->input('codRestaurante');
    $numMesa = $request->input('numMesa');

    try {
      Table::create([
        'vta_numero_mesa' => $numMesa,
        'lgt_restaurante_id_lgt_restaurante' => $codRestaurant
      ]);
      return CustomResponse::success('Mesa creada correctamente');
    } catch (\Throwable $th) {
      error_log($th->getMessage());
      return CustomResponse::failure();
    }
  }

  function deleteMesa($idmesa)
  {
    try {
      Table::where('id_vta_mesa', $idmesa)->delete();
      return CustomResponse::success('Mesa eliminada');
    } catch (\Throwable $th) {
      error_log($th);
      return CustomResponse::failure('No se puede eliminar esta mesa');
    }
  }
}
