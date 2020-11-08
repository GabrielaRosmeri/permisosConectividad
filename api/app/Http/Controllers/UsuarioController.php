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
        $usuario = Usuario::where('Nombre', '=', $request->get('Nombre'))->get()->first();
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
                $opciones = DB::table('perfil as p')
                    ->join('permisoperfil as pp', 'pp.CodigoPerfil', '=', 'p.Codigo')
                    ->join('opcion as o', 'o.Codigo', '=', 'pp.CodigoOpcion')
                    ->join('sistema as s', 's.Codigo', '=', 'o.CodigoSistema')
                    ->select('s.Codigo as sistema', 's.Nombre as sistemaNombre', 's.icono as icono', 'o.Nombre', 'o.URL')
                    ->where('p.Codigo', '=', $usuario->CodigoPerfil)
                    ->where('pp.Permitido', '=', 1)
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
                    "opciones" => $opciones,
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
            ->select('u.Codigo', DB::raw('CONCAT(p.Nombres ,"  ", p.ApellidoPaterno ,"  ", p.ApellidoMaterno) as Nombres'), 'l.Nombre as Local', 'pf.Nombre as Perfil', 'u.Vigencia')
            ->where('pf.Codigo', '=', 2)
            ->where('e.Codigo', '=', $request->get('empresa'))
            ->Where($atributo, 'like', "%{$busqueda}%")
            ->whereIn('u.Vigencia', $request->get('vigencia'))
            ->get();

        return response()->json($usuario, 200);
    }
}
