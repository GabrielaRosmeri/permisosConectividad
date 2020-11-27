<?php

namespace App\Http\Controllers;

use App\Models\Perfil;
use App\Models\Personal;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;

class UsuarioController extends Controller
{
    public function login(Request $request)
    {
        function agruparSistema($array)
        {
            $resultado = array();
            foreach ($array as $sistema) {;
                if (!isset($resultado[$sistema->sistema])) {
                    $resultado[$sistema->sistema] = $sistema;
                }
                if (!isset($resultado[$sistema->sistema]->opciones)) {
                    $resultado[$sistema->sistema]->opciones = array();
                }
                array_push($resultado[$sistema->sistema]->opciones, array("opcion" => $sistema->Nombre, "url" => $sistema->URL));
            }
            return array_values($resultado);
        }
        // ? VALIADACION POR PARTE DEL BACKEND
        $validacion = Validator::make($request->all(), [
            'Nombre' => 'required|max:30',
            'Clave' => 'required',
        ]);
        if ($validacion->fails()) {
            return response()->json($validacion, 400);
        }
        // ? BUSCAR NOMBRE INGRESANDO POR EL USUARIO
        $usuario = Usuario::where('Nombre', '=', $request->get('Nombre'))->where('Vigencia', '=', 1)->get()->first();
        if ($usuario) {
            // ? COMPARAR CLAVE
            if (Hash::check($request->get('Clave'), $usuario->Clave)) {
                $personal = Personal::findOrFail($usuario->CodigoPersonal);
                $empresa = DB::table('usuario as u')
                    ->join('local as l', 'l.Codigo', '=', 'u.CodigoLocal')
                    ->join('empresa as e', 'e.Codigo', '=', 'l.CodigoEmpresa')
                    ->select('e.Logo', 'e.RazonSocial', 'e.Codigo')
                    ->where('u.Codigo', '=', $usuario->Codigo)
                    ->get()
                    ->first();
                $opciones = DB::table('usuario as u')
                    ->join('permisousuario as pu', 'pu.CodigoUsuario', '=', 'u.Codigo')
                    ->join('opcion as o', 'o.Codigo', '=', 'pu.CodigoOpcion')
                    ->join('sistema as s', 's.Codigo', '=', 'o.CodigoSistema')
                    ->select('s.Codigo as sistema', 's.Nombre as sistemaNombre', 's.icono as icono', 'o.Nombre', 'o.URL')
                    ->where('u.Codigo', '=', $usuario->Codigo)
                    ->where('pu.Permitido', '=', 1)
                    ->get();
                // dd($opciones);
                $opciones = agruparSistema($opciones);
                $factory = JWTFactory::customClaims([
                    'sub' => env('API_id'),
                ]);
                $payload = $factory->make();
                $token = JWTAuth::encode($payload);
                return response()->json(array(
                    "perfil" => $usuario->CodigoPerfil,
                    "usuario" => $personal->Nombres . " " . $personal->ApellidoPaterno . " " . $personal->ApellidoMaterno,
                    "correo" => $personal->Correo,
                    "logo" => $empresa->Logo,
                    "empresa" => $empresa->RazonSocial,
                    "empresaId" => $empresa->Codigo,
                    "modulos" => $opciones,
                    "usuarioId" => $usuario->Codigo,
                    "local" => $usuario->CodigoLocal,
                    "token" => $token->get()
                ), 200);
            } else {
                return response()->json(array("mensaje" => "clave_no_coincide"), 400);
            }
        } else {
            return response()->json(array("mensaje" => "usuario_no_registrado"), 400);
        }
    }

    public function lista(Request $request)
    {
        $atributo = $request->get('atributo');
        $busqueda = $request->get('busqueda');
        $usuario = DB::table('usuario as u')
            ->join('perfil as pf', 'pf.Codigo', '=', 'u.CodigoPerfil')
            ->join('personal as p', 'p.Codigo', '=', 'u.CodigoPersonal')
            ->join('local as l', 'l.Codigo', '=', 'u.CodigoLocal')
            ->join('empresa as e', 'e.Codigo', '=', 'l.CodigoEmpresa')
            ->select(
                'u.Codigo',
                'u.Nombre as Usuario',
                DB::raw('CONCAT(p.Nombres ,"  ", p.ApellidoPaterno ,"  ", p.ApellidoMaterno) as Nombres'),
                'l.Nombre as Local',
                'pf.Nombre as Perfil',
                'u.Vigencia'
            )
            ->where('pf.Codigo', '=', 2)
            ->where('e.Codigo', '=', $request->get('empresa'))
            ->Where($atributo, 'like', "%{$busqueda}%")
            ->get();

        return response()->json($usuario, 200);
    }

    public function listarPorPerfil()
    {
        $respuesta = [];
        $perfil = DB::table('perfil as p')
            ->select('p.Codigo', 'p.Nombre')
            ->get();
        foreach ($perfil as $p) {
            array_push($respuesta, array("value" => $p->Codigo, "text" => $p->Nombre));
        }
        return response()->json($respuesta, 200);
    }

    public function listarPorPersonal(Request $request)
    {
        $respuesta = [];
        $personal = DB::table('local as l')
            ->join('localpersonal as lp', 'lp.CodigoLocal', '=', 'l.Codigo')
            ->join('personal as p', 'p.Codigo', '=', 'lp.CodigoPersonal')
            ->select('p.Codigo', 'p.Nombres', 'p.ApellidoPaterno', 'p.ApellidoMaterno')
            ->where('l.Codigo', '=', $request->get('local'))
            ->get();
        foreach ($personal as $p) {
            array_push($respuesta, array("value" => $p->Codigo, "text" => $p->Nombres . " " . $p->ApellidoPaterno . " " . $p->ApellidoMaterno));
        }
        return response()->json($respuesta, 200);
    }

    public function listarPorLocal(Request $request)
    {
        $respuesta = [];
        $empresa = $request->get('empresa');
        $local = DB::table('local as l')
            ->select('l.Codigo', 'l.Nombre')
            ->where('l.CodigoEmpresa', '=', $empresa)
            ->get();
        foreach ($local as $l) {
            array_push($respuesta, array("value" => $l->Codigo, "text" => $l->Nombre));
        }
        return response()->json($respuesta, 200);
    }

    public function leer($id)
    {
        $usuario = DB::table('usuario as u')
            ->join('personal as p', 'p.Codigo', '=', 'u.CodigoPersonal')
            ->join('local as l', 'l.Codigo', '=', 'u.CodigoLocal')
            ->join('perfil as pf', 'pf.Codigo', '=', 'u.CodigoPerfil')
            ->select(
                'u.Nombre as Nombre',
                DB::raw('CONCAT(p.Nombres ,"  ", p.ApellidoPaterno ,"  ", p.ApellidoMaterno) as Personal'),
                'l.Nombre as Local',
                'pf.Nombre as Perfil'
            )
            ->where('u.Codigo', '=', $id)
            ->get()
            ->first();
        return response()->json($usuario, 200);
    }

    public function registrar(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'CodigoPersonal' => 'required',
            'CodigoPerfil' => 'required',
            'Nombre' => 'required|max:100',
            'Clave' => 'required|max:255',
            'CodigoLocal' => 'required'
        ], [
            'required' => ':attribute es obligatorio',
            'max' => ':attribute llego al limite de letras'
        ]);

        if ($validacion->fails()) {
            return response()->json($validacion->errors()->first(), 400);
        }

        $nombreUsuario = DB::table('usuario as u')
            ->join('local as l', 'l.Codigo', '=', 'u.CodigoLocal')
            ->join('empresa as e', 'e.Codigo', '=', 'l.CodigoEmpresa')
            ->where('u.Nombre', '=', $request->get('Nombre'))
            ->where('e.Codigo', '=', $request->get('empresa'))
            ->get()
            ->first();
        if (!$nombreUsuario) {
            $usuarioBuscar = Usuario::where('CodigoPersonal', '=', $request->get('CodigoPersonal'))->where('CodigoLocal', '=', $request->get('CodigoLocal'))->get()->first();
            if (!$usuarioBuscar) {
                $usuario = new Usuario();
                $usuario->CodigoPersonal = $request->get('CodigoPersonal');
                $usuario->CodigoPerfil = $request->get('CodigoPerfil');
                $usuario->Nombre = $request->get('Nombre');
                $usuario->Clave = Hash::make($request->get('Clave'));
                $usuario->CodigoLocal = $request->get('CodigoLocal');
                $usuario->Tipo = $request->get('Tipo');
                $usuario->save();

                return response()->json($usuario, 201);
            } else {
                return response()->json(array("msg" => "Usuario ya existe."), 501);
            }
        } else {
            return response()->json(array("msg" => "Usuario ya existe."), 404); //el msg de aqui no sirve por ahora 
        }
    }

    public function actualizar(Request $request, $id)
    {
        // luego lo pones
        $clave = DB::table('usuario as u')
            ->select('u.Clave')
            ->where('u.Codigo', '=', $id)
            ->get()
            ->first();

        if ($request->get('ComprobarClave') != '') {
            $respuesta = Hash::check($request->get('ComprobarClave'), $clave->Clave);
            if ($respuesta == false) {
                return response()->json(array("msg" => "Contrase;a no coincide"), 501); //el msg de aqui no sirve por ahora 
            }
        }
        $nombreBuscar = DB::table('usuario as u')
            ->join('local as l', 'l.Codigo', '=', 'u.CodigoLocal')
            ->join('empresa as e', 'e.Codigo', '=', 'l.CodigoEmpresa')
            ->where('u.Nombre', '=', $request->get('Nombre'))
            ->where('e.Codigo', '=', $request->get('empresa'))
            ->where('u.Codigo', '!=', $id)
            ->get()
            ->first();
        if (!$nombreBuscar) {
            $usuario = Usuario::findOrFail($id);
            $usuario->Nombre = $request->get('Nombre');
            if ($request->get('Clave') != '') {
                $usuario->Clave = Hash::make($request->get('Clave'));
            }
            $usuario->save();

            return response()->json($usuario, 200);
        } else {
            return response()->json(array("msg" => "Usuario ya existe."), 404); //el msg de aqui no sirve por ahora 
        }
    }


    public function cambiarVigencia($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->Vigencia = !$usuario->Vigencia;
        $usuario->save();

        return response()->json($usuario, 200);
    }

    public function reestablecerContraseña(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->Clave = Hash::make($request->get('clave'));
        $usuario->save();

        return response()->json($usuario, 200);
    }

    public function datosUsuario(Request $request)
    {
        $usuario = $request->get('usuario');
        $local = $request->get('local');

        $datos = DB::table('usuario as u')
            ->join('personal as p', 'p.Codigo', '=', 'u.CodigoPersonal')
            ->join('localpersonal as lp', 'lp.CodigoPersonal', '=', 'p.Codigo')
            ->join('documentopersona as dp', 'dp.Codigo', '=', 'p.CodigoDocumentoPersona')
            ->join('sistemapensiones as sp', 'sp.Codigo', '=', 'p.CodigoSistemaPensiones')
            ->select(
                'lp.Cargo',
                'lp.CorreoEmpresarial',
                'lp.CelularEmpresarial',
                'p.Nombres',
                'p.ApellidoPaterno',
                'p.ApellidoMaterno',
                'p.NumeroDocumento',
                'p.Direccion',
                'p.Telefono',
                'p.Correo',
                'p.Celular',
                'dp.Nombre as Documento',
                'sp.Siglas'
            )
            ->where('u.Codigo', '=', $usuario)
            ->where('lp.CodigoLocal', '=', $local)
            ->get()
            ->first();

        return response()->json($datos, 200);
    }

    public function actualizarDatosUsuario(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);
        $personal = Personal::findOrFail($usuario->CodigoPersonal);

        $personal->Nombres = $request->get('Nombres');
        $personal->ApellidoPaterno = $request->get('ApellidoPaterno');
        $personal->ApellidoMaterno = $request->get('ApellidoMaterno');
        $personal->Direccion = $request->get('Direccion');
        $personal->Telefono = $request->get('Telefono');
        $personal->Celular = $request->get('Celular');
        $personal->Correo = $request->get('Correo');

        $personal->save();

        return response()->json($personal, 200);
    }
}
