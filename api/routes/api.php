<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\LocalController;
use App\Http\Controllers\PermisoUsuarioController;
use App\Http\Controllers\ProductoController;
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
Route::post('usuariosLista', [UsuarioController::class, 'lista'])->middleware('token');
Route::get('usuariosperfil', [UsuarioController::class, 'listarPorPerfil'])->middleware('token');
Route::post('usuariospersonal', [UsuarioController::class, @'listarPorPersonal'])->middleware('token');
Route::post('usuarioslocal', [UsuarioController::class, 'listarPorLocal'])->middleware('token');
Route::get('usuarios/{id}', [UsuarioController::class, 'leer'])->middleware('token');
Route::post('usuarios', [UsuarioController::class, 'registrar'])->middleware('token');
Route::put('usuarios/{id}', [UsuarioController::class, 'actualizar'])->middleware('token');
Route::patch('usuario/{id}', [UsuarioController::class, 'cambiarVigencia'])->middleware('token');
Route::put('reestablecer/{id}', [UsuarioController::class, 'reestablecerContraseña'])->middleware('token');
Route::post('usuarioDatos', [UsuarioController::class, 'datosUsuario'])->middleware('token');
Route::put('actualizarDatos/{id}', [UsuarioController::class, 'actualizarDatosUsuario'])->middleware('token');

//* CRUD DE LOCAL
Route::post('local', [LocalController::class, 'registrar'])->middleware('token');
Route::get('locales/{Codigo}', [LocalController::class, 'leer'])->middleware('token');
Route::put('locales/{Codigo}', [LocalController::class, 'actualizar'])->middleware('token');
Route::post("locales", [LocalController::class, 'Listar'])->middleware('token');
Route::patch("locales/{Vigencia}", [LocalController::class, 'cambiarEstadoLocal'])->middleware('token');

//* CRUD DE CATEGORIA
Route::post('categorias', [CategoriaController::class, 'listar'])->middleware('token');
Route::get('categorias/{id}', [CategoriaController::class, 'leer'])->middleware('token');
Route::post('categoria', [CategoriaController::class, 'registrar'])->middleware('token');
Route::put('categorias/{id}', [CategoriaController::class, 'actualizar'])->middleware('token');
Route::patch('categoria/{id}', [CategoriaController::class, 'cambiarVigencia'])->middleware('token');
// *********************************************************

//* CRUD DE PRODUCTO
Route::post('productos', [ProductoController::class, 'listar'])->middleware('token');
Route::get('producto/{id}', [ProductoController::class, 'leer'])->middleware('token');
Route::post('producto', [ProductoController::class, 'registrar'])->middleware('token');
Route::put('producto/{id}', [ProductoController::class, 'actualizar'])->middleware('token');
Route::patch('producto/{id}', [ProductoController::class, 'cambiarVigencia'])->middleware('token');
Route::post('productocategoria', [ProductoController::class, 'mostrarCategoria'])->middleware('token');
Route::get('productomarca', [ProductoController::class, 'mostrarMarca'])->middleware('token');
//* *********************************************************