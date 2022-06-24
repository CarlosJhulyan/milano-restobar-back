<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\core\CustomResponse;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
  function createUser(Request $request)
  {
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

      if ($avatar) {
        if ($usuario) {
          $avatar->move(public_path('avatars/'), $nameImage);
        }
      }

      return CustomResponse::success('Usuario creado');
    } catch (\Throwable $th) {
      error_log($th);
      return CustomResponse::failure();
    }
  }
  function updateUser(Request $request)
  {
    $id_cme_usuario = $request->input('idusuario');
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
      'idusuario' => 'required',
      'nombre' => 'required',
      'apellidop' => 'required',
      'apellidom' => 'required',
      'correo' => 'required',
      'celular' => 'required',
      'fechaNac' => 'required'
    ]);

    if ($validator->fails()) {
      return  CustomResponse::failure('Datos faltantes');
    }

    try {
      if ($avatar) {
        $nameImage = uniqid('IMG', false) . '.' .  $avatar->extension();
      } else {
        $nameImage = null;
      }

      $usuario = User::where('id_cme_usuario', $id_cme_usuario);
      if ($usuario) {
        if ($nameImage !== null) {
          $usuario->update([
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
        } else {
          $usuario->update([
            'nombre' => $nombre,
            'apellido_paterno' => $apellidop,
            'apellido_materno' => $apellidom,
            'correo' => $correo,
            'celular' => $celular,
            'fecha_nacimiento' => $fechanac,
            'direccion' => $direccion,
            'dni' => $dni
          ]);
        }
      }

      if ($avatar) {
        if ($usuario) {
          $avatar->move(public_path('avatars/'), $nameImage);
        }
      }

      return CustomResponse::success('Usuario actualizado');
    } catch (\Throwable $th) {
      error_log($th);
      return CustomResponse::failure();
    }
  }

  function deleteUser($idusuario)
  {
    try {
      $data = User::where('id_cme_usuario', $idusuario)->delete();

      return CustomResponse::success('Usuario eliminado', $data);
    } catch (\Throwable $th) {
      error_log($th);
      return CustomResponse::failure();
    }
  }

  function getUsers()
  {
    try {
      $data = User::select(
        '*',
        'id_cme_usuario as key',
      )
        ->orderByDesc('fecha_creacion')
        ->get();

      return CustomResponse::success('Lista de usuarios', $data);
    } catch (\Throwable $th) {
      error_log($th);
      return CustomResponse::failure();
    }
  }

  function updateRolUser(Request $request)
  {
    $id = $request->input('id_cme_usuario');
    $rol = $request->input('tipo_usuario');

    $validator = Validator::make($request->all(), [
      'id_cme_usuario' => 'required',
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
