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
        // ? VALIADACION POR PARTE DEL BACKEND
        $validacion = Validator::make($request->all(), [
            'Nombre' => 'required|max:30',
            'Clave' => 'required',
        ]);
        if ($validacion->fails()) {
            return response()->json($validacion, 400);
        }
        // ? BUSCAR NOMBRE INGRESANDO POR EL USUARIO
        $usuario = Usuario::where('Nombre', '=', $request->Nombre)->get()->first();
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
            ->select('u.Codigo', DB::raw('CONCAT(p.Nombres ,"  ", p.ApellidoPaterno ,"  ", p.ApellidoMaterno) as Nombres'), 'l.Nombre as Local', 'pf.Nombre as Perfil','u.Vigencia')
            ->where('pf.Codigo', '=', 2)
            ->where('e.Codigo', '=', $request->get('empresa'))
            ->Where($atributo, 'like', "%{$busqueda}%")
            ->whereIn('u.Vigencia', $request->get('vigencia'))
            ->get();

        return response()->json($usuario, 200);
    }
}
