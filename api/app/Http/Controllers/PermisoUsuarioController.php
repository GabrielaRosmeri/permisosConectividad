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

    public function ListarOpcionesEmpleado(Request $request)
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
                array_push($resultado[$sistema->sistema]->opciones, array("opcion" => $sistema->Nombre, "codigo" => $sistema->Codigo));
            }
            return array_values($resultado);
        }
        $respuesta = [];
        $opciones = DB::table('sistema as s')
            ->join('opcion as o', 'o.CodigoSistema', '=', 's.Codigo')
            ->select('s.Codigo as sistema', 's.Nombre as sistemaNombre', 's.icono as icono', 'o.Nombre', 'o.Codigo')
            ->where('o.Vigencia', '=', 1)
            ->where('o.CodigoSistema', '!=', 1)
            ->where('o.CodigoSistema', '!=', 6)
            ->get();
        $opciones = agruparSistema($opciones);

        $personalOpciones = DB::table('usuario as u')
            ->join('permisousuario as pu', 'pu.CodigoUsuario', '=', 'u.Codigo')
            ->join('opcion as o', 'o.Codigo', '=', 'pu.CodigoOpcion')
            ->select('o.Codigo', 'o.Nombre')
            ->where('u.Codigo', '=', $request->get('usuario'))
            ->get();

        foreach ($opciones as $op) {
            $children = [];
            foreach ($op->opciones as $opO) {
                $valor = false;
                foreach ($personalOpciones as $po) {
                    if ($opO["codigo"] == $po->Codigo) {
                        $valor = true;
                    }
                }
                array_push($children, array("id" => $opO["codigo"], "name" => $opO["opcion"], "activatable" => $valor));
            }
            array_push($respuesta, array("id" => $op->sistema, "name" => $op->sistemaNombre, "children" => $children));
        }

        return response()->json($respuesta, 200);
    }
}
