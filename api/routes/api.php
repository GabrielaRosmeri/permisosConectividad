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

Route::post('login', [UsuarioController::class, 'login']);
Route::post('usuarios', [UsuarioController::class, 'lista'])->middleware('token');
Route::post('listaLocales', [PermisoUsuarioController::class, 'ListarLocal'])->middleware('token');
Route::post('listaEmpleados', [PermisoUsuarioController::class, 'ListarEmpleados'])->middleware('token');
