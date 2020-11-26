<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Controller
{

    public function leer($id)
    {
        return Producto::find($id);
    }

    //REGISTRAR PRODUCTO
    public function registrar(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'CodigoCategoria' => 'required',
            'CodigoMarca' => 'required',
            'Tipo' => 'required|max:50',
            'Nombre' => 'required|max:100',
            'TipoControl' => 'required|max:50',
        ], [
            'unique' => ':attribute ya se encuentra registrado',
            'required' => ':attribute es obligatorio',
            'max' => ':attribute llego al limite de letras'
        ]);
        if ($validacion->fails()) {
            return response()->json($validacion->errors()->first(), 400);
        }
        $producto = new producto();

        $producto->CodigoCategoria = $request->get('CodigoCategoria');
        $producto->CodigoMarca = $request->get('CodigoMarca');
        $producto->Tipo = $request->get('Tipo');
        $producto->Nombre = $request->get('Nombre');
        $producto->TipoControl = $request->get('TipoControl');
        $producto->save();

        return response()->json($producto, 201);
    }

    //ACTUALIZAR
    public function actualizar(Request $request, $id)
    {
        $validacion = Validator::make($request->all(), [
            'CodigoCategoria' => 'required',
            'CodigoMarca' => 'required|max:6',
            'Tipo' => 'max:1',
            'Nombre' => 'max:100',
            'TipoControl' => 'max:50',
            'Negociable' => 'max:1',
        ]);
        if ($validacion->fails()) {
            return response()->json($validacion, 400);
        }
        $producto = producto::findOrFail($id);
        $producto->CodigoCategoria = $request->get('CodigoCategoria');
        $producto->CodigoMarca = $request->get('CodigoMarca');
        $producto->Tipo = $request->get('Tipo');
        $producto->Nombre = $request->get('Nombre');
        $producto->TipoControl = $request->get('TipoControl');
        $producto->Negociable = $request->get('Negociable');
        $producto->save();
        return response()->json($producto, 200);
    }

    public function cambiarVigencia($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->Vigencia = !$producto->Vigencia;
        $producto->save();

        return response()->json($producto, 200);
    }

    public function listar(Request $request)
    {
        $producto = DB::table('producto as p')
            ->join('categoria as c', 'c.Codigo', '=', 'p.CodigoCategoria')
            ->join('marca as m', 'm.Codigo', '=', 'p.CodigoMarca')
            ->select('p.Codigo', 'c.Nombre as nombreCategoria', 'm.Nombre as nombreMarca', 'p.Nombre as nombreProducto', 'p.Tipo as Tipo')
            ->where('c.CodigoEmpresa', '=', $request->get('empresa'))
            ->get();

        return response()->json($producto, 200);
    }

    //MOSTRAR PRODUCTO
    public function mostrar($id)
    {
        return producto::find($id);
    }

    // MOSTRAR CATEGORIA
    public function mostrarCategoria()
    {
        $respuesta = [];
        $categoria = DB::table('categoria as c')
            ->select('c.Codigo', 'c.Nombre')
            ->get();
        foreach ($categoria as $c) {
            array_push($respuesta, array("value" => $c->Codigo, "text" => $c->Nombre));
        }
        return response()->json($respuesta, 200);
    }

    // MOSTRAR MARCA
    public function mostrarMarca()
    {
        $respuesta = [];
        $marca = DB::table('marca as m')
            ->select('m.Codigo', 'm.Nombre')
            ->get();
        foreach ($marca as $m) {
            array_push($respuesta, array("value" => $m->Codigo, "text" => $m->Nombre));
        }
        return response()->json($respuesta, 200);
    }
}
