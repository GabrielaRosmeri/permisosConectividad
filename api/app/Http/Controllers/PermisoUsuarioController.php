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
}
