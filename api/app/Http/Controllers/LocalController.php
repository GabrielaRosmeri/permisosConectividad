<?php

namespace App\Http\Controllers;

use App\Models\Local;
use Illuminate\Http\Request;

class LocalController extends Controller
{
    public function registrar(REQUEST $resquest)
    {
        $Local = new Local();
        $Local->Nombre = $resquest->Nombre;
        $Local->CodigoEmpresa = $resquest->CodigoEmpresa;
        $Local->Direccion = $resquest->Direccion;
        $Local->Telefono = $resquest->Telefono;
        $Local->Vigencia = 1;
        $Local->save();
        $dato = [
            "Registro" => "EXITOSO"
        ];
        return response()->json($dato, 200);
    }
    public function actualizar(REQUEST $resquest, $Codigo)
    {
        $Local = Local::find($Codigo);
        if ($Local != null) {
            $Local->Nombre = $resquest->Nombre;
            $Local->Direccion = $resquest->Direccion;
            $Local->Telefono = $resquest->Telefono;
            $Local->Vigencia = 1;
            $Local->save();
            $dato = [
                "Modificacion" => "EXITOSO"
            ];
            return response()->json($dato, 200);
        } else {
            $dato = [
                "Modificacion" => "FALLO"
            ];
            return response()->json($dato, 500);
        }
    }

    public function Listar()
    {
        $dato = Local::all();
        return response()->json($dato, 200);
    }

    public function cambiarEstadoLocal($Codigo)
    {
        $vigencia = -1;
        $Local = Local::findOrFail($Codigo);
        $Local->Vigencia = !$Local->Vigencia;
        $vigencia = !$Local->Vigencia;
        $Local->save();

        return response()->json($vigencia, 200);
    }
}
