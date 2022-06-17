<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\core\CustomResponse;

class AuthController extends Controller
{
  function loginUser(Request $request) {
    $usuario = $request->user;
    $clave = $request->password;

    try {
      $user = DB::select('SELECT * FROM cme_usuario t WHERE t.usuario = ?', [$usuario]);
      if (count($user) == 0) {
        return CustomResponse::failure('Usuario no encontrado');
      } else {
        $password = DB::select('SELECT * FROM cme_usuario t WHERE t.usuario = ? AND t.clave = ?', [$usuario, $clave]);
        if (count($password) == 0) {
          return CustomResponse::failure('Contraseña incorrecta');
        } else {
          return CustomResponse::success('Ingreso correcto', $password[0]);
        }
      }
    } catch (\Throwable $th) {
      error_log($th);
      return CustomResponse::failure('Error en los servidores');
    }
  }

  function loginAdmin(Request $request) {
    $usuario = $request->user;
    $clave = $request->password;
    
    try {
      $admin = DB::select('SELECT * FROM cme_admin t WHERE t.usuario = ?', [$usuario]);
      if (count($admin) == 0) {
        return CustomResponse::failure('Usuario no encontrado');
      } else {
        $password = DB::select('SELECT * FROM cme_admin t WHERE t.usuario = ? AND t.clave = ?', [$usuario, $clave]);
        if (count($password) == 0) {
          return CustomResponse::failure('Contraseña incorrecta');
        } else {
          return CustomResponse::success('Ingreso correcto', $password[0]);
        }
      }
    } catch (\Throwable $th) {
      error_log($th);
      return CustomResponse::failure('Error en los servidores');
    }
  }
}
