<?php

namespace App\Http\Controllers;

use App\Core\CustomResponse;
use App\Models\OrderDetail;
use App\Models\OrderHeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
  function generateOrder(Request  $request) {
    $status = $request->input('estado');
    $total = $request->input('montoTotal');
    $codUser = $request->input('codUsuario');
    $codTable = $request->input('codMesa');
    $details = $request->input('detalles');

    $validator = Validator::make($request->all(), [
      'estado' => 'required',
      'montoTotal' => 'required',
      'codUsuario' => 'required',
      'codMesa' => 'required',
      'detalles' => 'required'
    ]);

    if ($validator->fails()) {
      return CustomResponse::failure('Datos faltantes');
    }

    try {
      $orderHeader = OrderHeader::create([
        'estado' => $status,
        'monto_total' => $total,
        'id_usuario' => $codUser,
        'vta_mesa_id_vta_mesa' => $codTable
      ]);

      if ($orderHeader) {
        $idHeader = $orderHeader->idvta_pedido_venta_cab;

        foreach ($details as $value) {
          OrderDetail::create([
            'idvta_pedido_venta_cab' => $idHeader,
            'cantidad' => $value['cantidad'],
            'id_cme_receta' => $value['id_cme_receta'],
            'id_vta_plato' => $value['id_vta_plato'],
            'precio' => $value['precio']
          ]);
        }
      }

      return CustomResponse::success('Pedido generado correctamente');
    } catch (\Throwable $th) {
      error_log($th);
      return CustomResponse::failure();
    }
  }

  function getRecentOrdersByUser(Request $request) {
    $codUser = $request->input('codUsuario');

    $validator = Validator::make($request->all(), [
      'codUsuario' => 'required',
    ]);

    if ($validator->fails()) {
      return CustomResponse::failure('Datos faltantes');
    }

    try {
      $data = DB::table('vta_pedido_venta_cab')
        ->join('vta_mesa', 'id_vta_mesa', '=', 'vta_mesa_id_vta_mesa')
        ->where('id_usuario', '=', $codUser)
        ->orderByDesc('fecha_creacion')
        ->get();

      return CustomResponse::success('Mi lista de pedidos recientes', $data);
    } catch (\Throwable $th) {
      error_log($th);
      return CustomResponse::failure();
    }
  }

  function changeStatusOrder(Request $request) {
    $codOrderHeader = $request->input('codPedidoCabecera');
    $status = $request->input('estado');

    $validator = Validator::make($request->all(), [
      'estado' => 'required',
      'codPedidoCabecera' => 'required',
    ]);

    if ($validator->fails()) {
      return CustomResponse::failure('Datos faltantes');
    }

    try {
      OrderHeader::where('idvta_pedido_venta_cab', '=', $codOrderHeader)
        ->update(['estado' => $status]);

      return CustomResponse::success('Estado de pedido actualizado');
    } catch (\Throwable $th) {
      error_log($th);
      return CustomResponse::failure();
    }
  }

  function getOrderDetailsByHeader(Request $request) {
    $codOrderHeader = $request->input('codPedidoCabecera');

    $validator = Validator::make($request->all(), [
      'codPedidoCabecera' => 'required',
    ]);

    if ($validator->fails()) {
      return CustomResponse::failure('Datos faltantes');
    }

    try {
      $data = OrderDetail::where('idvta_pedido_venta_cab', '=', $codOrderHeader)
        ->get();

      return CustomResponse::success('Lista detalles de pedido', $data);
    } catch (\Throwable $th) {
      error_log($th);
      return CustomResponse::failure();
    }
  }
}
