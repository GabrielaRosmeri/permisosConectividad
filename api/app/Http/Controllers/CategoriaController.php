<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriaController extends Controller
{
    public function listar(Request $request)
    {
        return Categoria::select('Codigo', 'Nombre', 'Descripcion', 'Vigencia')->where('CodigoEmpresa', '=', $request->get('empresa'))->orderBy('Nombre', 'asc')->get();
    }
    public function leer($id)
    {
        return Categoria::find($id);
    }


    public function mostrarCategorias()
    {
        return Categoria::all();
    }

    public function registrar(Request $request)
    {
        // ? VALIADACION POR PARTE DEL BACKEND
        $validacion = Validator::make($request->all(), [
            'Nombre' => 'required|max:30',
            'Descripcion' => 'required|max:200',
        ], [
            'required' => ':attribute es obligatorio',
            'max' => ':attribute llego al limite de letras'
        ]);
        if ($validacion->fails()) {
            return response()->json($validacion->errors()->first(), 400);
        }

        $buscarCategoria = Categoria::where('Nombre', '=', $request->get('Nombre'))->where('CodigoEmpresa', '=', $request->get("CodigoEmpresa"))->get()->first();
        if (!$buscarCategoria) {
            $categoria = new Categoria();
            $categoria->CodigoEmpresa = $request->get("CodigoEmpresa");
            $categoria->Nombre = $request->get('Nombre');
            $categoria->Descripcion = $request->get('Descripcion');
            $categoria->Vigencia = 1;
            $categoria->save();
            return response()->json($categoria, 201);
        } else {
            return response()->json(array("msg" => "Categoria ya existe."), 404);
        }
    }

    public function actualizar(Request $request, $id)
    {
        $validacion = Validator::make($request->all(), [
            'Nombre' => 'required|max:30',
            'Descripcion' => 'required|max:200',
        ]);
        if ($validacion->fails()) {
            return response()->json($validacion, 400);
        }
        $buscarCategoria = Categoria::where('Nombre', '=', $request->get('Nombre'))->where('CodigoEmpresa', '=', $request->get("CodigoEmpresa"))->where('Codigo', '!=', $id)->get()->first();
        if (!$buscarCategoria) {
            $categoria = Categoria::findOrFail($id);
            $categoria->Nombre = $request->get('Nombre');
            $categoria->Descripcion = $request->get('Descripcion');
            $categoria->update();
            return response()->json($categoria, 200);
        } else {
            return response()->json(array("msg" => "Categoria ya existe."), 404);
        }
    }

    public function cambiarVigencia($id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->Vigencia = !$categoria->Vigencia;
        $categoria->save();

        return response()->json($categoria, 200);
    }
}
