<?php

namespace App\Http\Controllers;

use App\Models\DocumentoPersona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DocumentoPersonalController extends Controller
{
    public function listar()
    {
        $sql = 'SELECT @i:=@i+1 as contador, Nombre, CodigoSUNAT, Codigo, CantidadMaxima, CantidadMinima, Vigencia FROM documentopersona CROSS JOIN (SELECT @i := 0) r';

        $documentopersonal = DB::select($sql);

        return response()->json($documentopersonal, 200);
    }

    public function listarVigente()
    {
        $sql = 'SELECT Codigo, Nombre, CantidadMinima, CantidadMaxima FROM documentopersona
        WHERE Vigencia = 1';
        $doc = DB::select($sql);

        return response()->json($doc, 200);
    }

    public function registrar(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'Nombre' => 'required|max:100',
            'CantidadMinima' => 'required|max:100',
            'CantidadMaxima' => 'required|max:100',
            'CodigoSUNAT' => 'required|unique:documentopersona|max:100',
        ], [
            'unique' => ':attribute ya se encuentra registrado',
            'required' => ':attribute es obligatorio',
        ]);
        if ($validacion->fails()) {
            return response()->json($validacion->errors()->first(), 400);
        }

        $documentopersonal = new DocumentoPersona();

        $documentopersonal->Nombre = $request->get('Nombre');
        $documentopersonal->CodigoSUNAT = $request->get('CodigoSUNAT');
        $documentopersonal->CantidadMinima = $request->get('CantidadMinima');
        $documentopersonal->CantidadMaxima = $request->get('CantidadMaxima');
        $documentopersonal->Tipo = $request->get('Tipo');
        $documentopersonal->Vigencia = 1;

        $documentopersonal->save();

        return response()->json($documentopersonal, 201);
    }

    public function actualizar(Request $request, $id)
    {
        $validacion = Validator::make($request->all(), [
            'Nombre' => 'required|max:100',
            'CantidadMinima' => 'required|max:100',
            'CantidadMaxima' => 'required|max:100',
        ], [
            'required' => ':attribute es obligatorio',
        ]);
        if ($validacion->fails()) {
            return response()->json($validacion->errors()->first(), 400);
        }

        $documentopersonal = DocumentoPersona::findOrFail($id);
        $documentopersonal->Nombre = $request->get('Nombre');
        $documentopersonal->CodigoSUNAT = $request->get('CodigoSUNAT');
        $documentopersonal->CantidadMinima = $request->get('CantidadMinima');
        $documentopersonal->CantidadMaxima = $request->get('CantidadMaxima');
        $documentopersonal->Tipo = $request->get('Tipo');

        $documentopersonal->save();
        return response()->json($documentopersonal, 200);
    }

    public function cambiarVigencia($id)
    {
        $documentopersonal = DocumentoPersona::findOrFail($id);
        $documentopersonal->Vigencia = !$documentopersonal->Vigencia;
        $documentopersonal->save();

        return response()->json($documentopersonal, 200);
    }

    public function leer($id)
    {
        return DocumentoPersona::find($id);
    }
}
