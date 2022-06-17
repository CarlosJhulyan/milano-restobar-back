<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\core\CustomResponse;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
  function createUser(Request $request) {
    $nombre = $request->input('nombre');
    $apellidom = $request->input('apellidom');
    $apellidop = $request->input('apellidop');
    $correo = $request->input('correo');
    $celular = $request->input('celular');
    $fechanac = $request->input('fechaNac');
    $dni = $request->input('dni');
    $direccion = $request->input('direccion');
    $avatar = $request->file('avatar');

    $validator = Validator::make($request->all(), [
      'nombre' => 'required',
      'apellidop' => 'required',
      'apellidom' => 'required',
      'correo' => 'required',
      'celular' => 'required',
      'fechaNac' => 'required'
    ]);

    if ($validator->fails()) {
      return CustomResponse::failure('Datos faltantes');
    }

    try {
      if ($avatar) {
        $nameImage = uniqid('IMG', false) . '.' .  $avatar->extension();
      } else {
        $nameImage = null;
      }

      $usuario = User::create([
        'nombre' => $nombre,
        'apellido_paterno' => $apellidop,
        'apellido_materno' => $apellidom,
        'correo' => $correo,
        'celular' => $celular,
        'fecha_nacimiento' => $fechanac,
        'direccion' => $direccion,
        'dni' => $dni,
        'avatar' => $nameImage
      ]);

      if ($usuario) {
        $avatar->move(public_path('avatars/'), $nameImage);
      }

      return CustomResponse::success('Usuario creado');
    } catch (\Throwable $th) {
      error_log($th);
      return CustomResponse::failure();
    }
  }

  function getUsers() {
    try {
      $data = User::select(
        'id_cme_usuario as key',
        'id_cme_usuario',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'correo',
        'fecha_nacimiento',
        'direccion',
        'tipo_usuario',
        'avatar',
        'fecha_creacion',
        'celular'
      )
        ->orderByDesc('fecha_creacion')
        ->get();

      return CustomResponse::success('Lista de usuarios', $data);
    } catch (\Throwable $th) {
      error_log($th);
      return CustomResponse::failure();
    }
  }

  function updateRolUser(Request $request) {
    $id = $request->input('id_usuario');
    $rol = $request->input('tipo_usuario');

    $validator = Validator::make($request->all(), [
      'id_usuario' => 'required',
      'tipo_usuario' => 'required'
    ]);

    if ($validator->fails()) {
      return  CustomResponse::failure('Datos faltantes');
    }

    try {
      User::where('id_cme_usuario', $id)
        ->update(['tipo_usuario' => $rol]);

      return CustomResponse::success('Rol de usuario actualizado');
    } catch (\Throwable $th) {
      error_log($th->getMessage());
      return CustomResponse::failure();
    }
  }
}
