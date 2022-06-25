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

        DB::table('vta_pedido_venta_cab')
          ->where('idvta_pedido_venta_cab', '=', $idHeader)
          ->update(
            ['num_vta_pedido' => str_pad($idHeader, 9, '0', STR_PAD_LEFT)]
          );

        foreach ($details as $value) {
          OrderDetail::create([
            'idvta_pedido_venta_cab' => $idHeader,
            'cantidad' => $value['cantidad'],
            'id_cme_receta' => $value['id_cme_receta'],
            'vta_plato_id_vta_plato' => $value['id_vta_plato'],
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

  function getOrderDetailsComplete(Request $request) {
    $codOrderHeader = $request->input('codPedidoCabecera');

    $validator = Validator::make($request->all(), [
      'codPedidoCabecera' => 'required',
    ]);

    if ($validator->fails()) {
      return CustomResponse::failure('Datos faltantes');
    }

    try {
      $orderDetails = OrderDetail::select('vta_pedido_vta_det.*', 'vta_plato.vta_ruta_imagen_plato', 'vta_plato.vta_precio', 'vta_plato.vta_desc_plato')
        ->where('idvta_pedido_venta_cab', '=', $codOrderHeader)
        ->join('vta_plato', 'id_vta_plato', '=', 'vta_plato_id_vta_plato')
        ->get();
      $orderHeader = OrderHeader::select('vta_pedido_venta_cab.*', 'cme_usuario.nombre', 'cme_usuario.apellido_paterno', 'vta_mesa.vta_numero_mesa')
        ->where('idvta_pedido_venta_cab', '=', $codOrderHeader)
        ->join('cme_usuario', 'id_cme_usuario', '=', 'id_usuario')
        ->join('vta_mesa', 'id_vta_mesa', '=', 'vta_mesa_id_vta_mesa')
        ->first();

      $data = [
        'cabecera' => $orderHeader,
        'detalles' => $orderDetails
      ];

      return CustomResponse::success('Detalles de pedido', $data);
    } catch (\Throwable $th) {
      error_log($th);
      return CustomResponse::failure();
    }
  }

  function getOrdersFulfilled(Request $request) {
    $date = new \DateTime();
    try {
      $data = OrderHeader::select(
        'vta_pedido_venta_cab.*',
        DB::raw("date_format(vta_pedido_venta_cab.fecha_creacion, '%d/%c/%Y %H:%i:%s') as fecha_crea"),
        'cme_usuario.nombre',
        'cme_usuario.apellido_paterno',
        'vta_mesa.vta_numero_mesa'
      )
        ->join('cme_usuario', 'id_cme_usuario', '=', 'id_usuario')
        ->join('vta_mesa', 'id_vta_mesa', '=', 'vta_mesa_id_vta_mesa')
        ->orderBy('vta_pedido_venta_cab.fecha_edicion', 'desc')
        ->where('estado', '=', 'A')
//        ->whereBetween('vta_pedido_venta_cab.fecha_creacion', [$date->format('Y-m-d H:m:s'), $date->modify('+1 day')->format('Y-m-d H:m:s')])
        ->get();

      return CustomResponse::success('Pedidos Atendidos', $data);
    } catch (\Throwable $th) {
      error_log($th);
      return CustomResponse::failure();
    }
  }

  function getOrdersCanceled(Request $request) {
    try {
      $data = OrderHeader::select(
        'vta_pedido_venta_cab.*',
        DB::raw("date_format(vta_pedido_venta_cab.fecha_creacion, '%d/%c/%Y %H:%i:%s') as fecha_crea"),
        'cme_usuario.nombre',
        'cme_usuario.apellido_paterno',
        'vta_mesa.vta_numero_mesa'
      )
        ->join('cme_usuario', 'id_cme_usuario', '=', 'id_usuario')
        ->join('vta_mesa', 'id_vta_mesa', '=', 'vta_mesa_id_vta_mesa')
        ->orderBy('vta_pedido_venta_cab.fecha_edicion', 'desc')
        ->where('estado', '=', 'C')
        ->get();

      return CustomResponse::success('Pedidos Cancelados', $data);
    } catch (\Throwable $th) {
      error_log($th);
      return CustomResponse::failure();
    }
  }

  function generatePaymentFormOrder(Request  $request) {
    $codLocal = '001';
    $codPaymentForm = $request->input('codFormaPago');
    $numOrder = $request->input('numPedido');
    $imPayment = $request->input('imPago');
    $coinType = $request->input('tipMoneda');
    $valTypChange = $request->input('valTipoCambio');
    $valVuelto = $request->input('valVuelto');
    $imTotalPago = $request->input('imTotalPago');

    $validator = Validator::make($request->all(), [
      'codFormaPago' => 'required',
      'numPedido' => 'required',
      'imPago' => 'required',
      'tipMoneda' => 'required',
      'valTipoCambio' => 'required',
      'valVuelto' => 'required',
      'imTotalPago' => 'required',
    ]);

    if ($validator->fails()) {
      return CustomResponse::failure('Datos faltantes');
    }

    try {
      DB::table('vta_forma_pago_pedido')
        ->insert([
          'cod_local' => $codLocal,
          'cod_forma_pago' => $codPaymentForm,
          'num_ped_vta' => $numOrder,
          'im_pago' => $imPayment,
          'tip_moneda' => $coinType,
          'val_tip_cambio' => $valTypChange,
          'val_vuelto' => $valVuelto,
          'im_total_pago' => $imTotalPago,
        ]);

      return CustomResponse::success('Forma pago pedido generado correctamente');
    } catch (\Throwable $th) {
      error_log($th);
      return CustomResponse::failure();
    }
  }
}
