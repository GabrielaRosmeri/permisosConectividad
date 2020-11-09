<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermisoUsuarioController extends Controller
{
    public function ListarLocal(Request $request)
    {
        $respuesta = [];
        $locales = DB::table('local as l')
            ->select('l.Nombre', 'l.Codigo')
            ->where('l.CodigoEmpresa', '=', $request->get('empresa'))
            ->where('l.Vigencia', '=', 1)
            ->get();
        foreach ($locales as $local) {
            array_push($respuesta, array("text" => $local->Nombre, "value" => $local->Codigo));
        }

        return response()->json($respuesta, 200);
    }

    public function ListarEmpleados(Request $request)
    {
        $respuesta = [];
        $empleados = DB::table('usuario as u')
            ->join('personal as p', 'p.Codigo', '=', 'u.CodigoPersonal')
            ->select('p.Nombres', 'p.ApellidoPaterno', 'p.ApellidoMaterno', 'p.Codigo')
            ->where('u.CodigoLocal', '=', $request->get('local'))
            ->where('u.CodigoPerfil', '!=', 1)
            ->get();
        foreach ($empleados as $emple) {
            array_push($respuesta, array("text" => $emple->Nombres . " " . $emple->ApellidoPaterno . " " . $emple->ApellidoMaterno, "value" => $emple->Codigo));
        }

        return response()->json($respuesta, 200);
    }
}
