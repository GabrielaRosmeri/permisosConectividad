<?php

namespace App\Http\Controllers;

use App\Models\Perfil;
use App\Models\Personal;
use App\Models\Usuario;
use Illuminate\Http\Request;
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
                $factory = JWTFactory::customClaims([
                    'sub' => env('API_id'),
                ]);
                $payload = $factory->make();
                $token = JWTAuth::encode($payload);
                return response()->json(array(
                    "perfil" => $usuario->CodigoPerfil,
                    "usuario" => $personal->Nombres . " " . $personal->ApellidoPaterno . " " . $personal->ApellidoMaterno,
                    "token" => $token->get()
                ), 200);
            } else {
                return response()->json(array("mensaje" => "clave_no_coincide"), 400);
            }
        } else {
            return response()->json(array("mensaje" => "usuario_no_registrado"), 400);
        }
    }
}
