<?php

namespace App\Http\Controllers;

use App\Core\CustomResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;


class Controller extends BaseController
{
  function getCoinType() {
    try {
      $data = DB::table('mae_moneda')
        ->select('cod_moneda', 'des_moneda', 'smb_moneda')
        ->where('flg_activo', '=', 'A')
        ->get();

      return CustomResponse::success('Tipod de moneda', $data);
    } catch (\Throwable $th) {
      return CustomResponse::failure();
    }
  }
}
