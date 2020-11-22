<?php

use App\Http\Controllers\PermisoUsuarioController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//* LOGIN
Route::post('login', [UsuarioController::class, 'login']);
//* ******************************************************
//* PERMISOS PERFILES
Route::post('listaLocales', [PermisoUsuarioController::class, 'ListarLocal'])->middleware('token');
Route::post('listaEmpleados', [PermisoUsuarioController::class, 'ListarEmpleados'])->middleware('token');
Route::post('listaOpcionesEmpleados', [PermisoUsuarioController::class, 'ListarOpcionesEmpleado'])->middleware('token');
Route::post('registrarOpcion', [PermisoUsuarioController::class, 'registrarPermisos'])->middleware('token');
//* ************************************************************************************

//* CRUD DE USUARIOS
Route::post('usuarios', [UsuarioController::class, 'lista'])->middleware('token');
Route::get('usuariosperfil', [UsuarioController::class, 'listarPorPerfil'])->middleware('token');
Route::get('usuariospersonal', [UsuarioController::class, @'listarPorPersonal'])->middleware('token');
Route::post('usuarioslocal', [UsuarioController::class, 'listarPorLocal'])->middleware('token');
