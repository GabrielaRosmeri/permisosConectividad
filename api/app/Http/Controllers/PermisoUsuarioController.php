<?php

namespace App\Http\Controllers;

use App\Models\PermisoUsuario;
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
            ->select('p.Nombres', 'p.ApellidoPaterno', 'p.ApellidoMaterno', 'u.Codigo')
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
        $active = [];
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
            $valor = false;
            foreach ($op->opciones as $opO) {
                foreach ($personalOpciones as $po) {
                    if ($po->Codigo == $opO["codigo"]) {
                        $valor = true;
                    }
                }
                if ($valor == true) {
                    array_push($active, $opO["codigo"]);
                }
                array_push($children, array("id" => $opO["codigo"], "name" => $opO["opcion"]));
                $valor = false;
            }
            array_push($respuesta, array("id" => $op->sistemaNombre, "name" => $op->sistemaNombre, "children" => $children));
        }

        return response()->json(array("opcion" => $respuesta, "active" => $active), 200);
    }

    public function registrarPermisos(Request $request)
    {
        $opcion = $request->get('opcion');
        $usuario = $request->get('usuario');
        // ? BUSCAR OPCIONES DEL USUARIO
        $opcionEmpleado = DB::table('permisousuario as pu')
            ->select('pu.Permitido', 'pu.CodigoOpcion', 'pu.Codigo')
            ->where('pu.CodigoUsuario', '=', $usuario)
            ->get();
        // ? CAMBIAR ESTADO
        foreach ($opcionEmpleado as $opE) {
            $valor = true;
            foreach ($opcion as $op) {
                if ($op == $opE->CodigoOpcion) {
                    $valor = false;
                }
            }
            if (sizeof($opcion) != 0) {
                if ($valor) {
                    $permisousuario = PermisoUsuario::findOrFail($opE->codigo);
                    $permisousuario->Permitido = 0;
                    $permisousuario->save();
                }
            }
        }
        // ? AGREGAR OPCIONES
        foreach ($opcion as $op) {
            $valorN = true;
            foreach ($opcionEmpleado as $opE) {
                if ($opE->CodigoOpcion == $op) {
                    $valorN = false;
                }
            }
            if ($valorN) {
                $permisousuario = new PermisoUsuario();
                $permisousuario->CodigoUsuario = $usuario;
                $permisousuario->CodigoOpcion = $op;
                $permisousuario->save();
            }
        }
        return response()->json($opcionEmpleado, 200);
    }
}
