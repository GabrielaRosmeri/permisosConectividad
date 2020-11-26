<?php

namespace App\Http\Controllers;

use App\Models\Local;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocalController extends Controller
{
    public function registrar(REQUEST $resquest)
    {
        $validacion = Validator::make($resquest->all(), [
            'Nombre' => 'required|max:100',
            'Direccion' => 'required|max:255',
            'Telefono' => 'required|max:9',
        ], [
            'required' => ':attribute es obligatorio',
            'max' => ':attribute llego al limite de letras'
        ]);

        if ($validacion->fails()) {
            return response()->json($validacion->errors()->first(), 400);
        }
        $buscarNombre = Local::where('Nombre', '=', $resquest->Nombre)->where('CodigoEmpresa', '=', $resquest->CodigoEmpresa)->get()->first();
        if (!$buscarNombre) {
            $Local = new Local();
            $Local->Nombre = $resquest->Nombre;
            $Local->CodigoEmpresa = $resquest->CodigoEmpresa;
            $Local->Direccion = $resquest->Direccion;
            $Local->Telefono = $resquest->Telefono;
            $Local->Vigencia = 1;
            $Local->save();
        } else {
            return response()->json(array("msg" => "Local ya existe."), 404); //el msg de aqui no sirve por ahora 
        }
        return response()->json($Local, 200);
    }
    public function actualizar(REQUEST $resquest, $Codigo)
    {
        $validacion = Validator::make($resquest->all(), [
            'Nombre' => 'required|max:100',
            'Direccion' => 'required|max:255',
            'Telefono' => 'required|max:9',
        ], [
            'required' => ':attribute es obligatorio',
            'max' => ':attribute llego al limite de letras'
        ]);

        if ($validacion->fails()) {
            return response()->json($validacion->errors()->first(), 400);
        }

        $buscarNombre = Local::where('Nombre', '=', $resquest->Nombre)->where('CodigoEmpresa', '=', $resquest->CodigoEmpresa)->where('Codigo', '!=', $Codigo)->get()->first();
        if (!$buscarNombre) {
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
        } else {
            return response()->json(array("msg" => "Local ya existe."), 404); //el msg de aqui no sirve por ahora 
        }
    }

    public function Listar()
    {
        $dato = Local::all();
        return response()->json($dato, 200);
    }

    public function leer($Codigo)
    {
        $dato = Local::find($Codigo);
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
