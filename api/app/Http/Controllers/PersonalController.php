<?php

namespace App\Http\Controllers;

use App\Models\LocalPersonal;
use App\Models\Personal;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PersonalController extends Controller
{
    public function registrar(Request $request)
    {
        //$auxDocPersonal = $request->get('NumeroDocumento');

        $validacion = Validator::make($request->all(), [
            'Nombres' => 'required|max:100',
            'ApellidoPaterno' => 'required|max:100',
            'ApellidoMaterno' => 'required|max:100',
            'Direccion' => 'required|max:100',
            'Correo' => 'required|max:100',
            'NumeroDocumento' => 'required|unique:personal|max:100',
        ], [
            'unique' => ':attribute ya se encuentra registrado',
            'required' => ':attribute es obligatorio',
        ]);
        if ($validacion->fails()) {
            return response()->json($validacion->errors()->first(), 400);
        }

        $personal = new Personal();

        $personal->Nombres = $request->get('Nombres');
        $personal->ApellidoPaterno = $request->get('ApellidoPaterno');
        $personal->ApellidoMaterno = $request->get('ApellidoMaterno');
        $personal->CodigoDocumentoPersona = $request->get('Documento');
        $personal->NumeroDocumento = $request->get('NumeroDocumento');
        $personal->Direccion = $request->get('Direccion');
        $personal->Telefono = $request->get('Telefono');
        $personal->Correo = $request->get('Correo');
        $personal->Celular = $request->get('Celular');
        $personal->CodigoSistemaPensiones = $request->get('Siglas');
        $personal->save();
        /** REGISTRO DE LA TABLA LOCAL PERSONAL */
        $sql01 = "SELECT Codigo FROM personal 
                      ORDER BY Codigo desc LIMIT 1";

        $personalID = DB::select($sql01);

        $LocalPersonal = new LocalPersonal();

        $LocalPersonal->CodigoPersonal = $personalID[0]->Codigo;
        $LocalPersonal->CodigoLocal = $request->get('idLocal');
        $LocalPersonal->Cargo = $request->get('Cargo');
        $LocalPersonal->Sueldo = $request->get('Sueldo');
        $LocalPersonal->CorreoEmpresarial = $request->get('CorreoEmpresarial');
        $LocalPersonal->CelularEmpresarial = $request->get('CelularEmpresarial');
        $LocalPersonal->FechaInicio = $request->get('FechaInicio');
        $LocalPersonal->FechaFin = $request->get('FechaInicio');
        $LocalPersonal->Vigencia = 1;
        $LocalPersonal->save();

        return response()->json($personal, 201);
        //}
    }

    public function actualizar(Request $request, $id)
    {

        $validacion = Validator::make($request->all(), [
            'Nombres' => 'required|max:100',
            'ApellidoPaterno' => 'required|max:100',
            'ApellidoMaterno' => 'required|max:100',
            'Direccion' => 'required|max:100',
            'Correo' => 'required|max:100',
        ], [
            'required' => ':attribute es obligatorio',
        ]);
        if ($validacion->fails()) {
            return response()->json($validacion->errors()->first(), 400);
        }

        $personal = personal::findOrFail($id);
        $personal->Nombres = $request->get('Nombres');
        $personal->ApellidoPaterno = $request->get('ApellidoPaterno');
        $personal->ApellidoMaterno = $request->get('ApellidoMaterno');
        $personal->CodigoDocumentoPersona = $request->get('Documento');
        $personal->NumeroDocumento = $request->get('NumeroDocumento');
        $personal->Direccion = $request->get('Direccion');
        $personal->Telefono = $request->get('Telefono');
        $personal->Correo = $request->get('Correo');
        $personal->Celular = $request->get('Celular');
        $personal->CodigoSistemaPensiones = $request->get('Siglas');

        $personal->save();

        $sql01 = "SELECT Codigo FROM localpersonal
        WHERE CodigoPersonal = " . $id . " ORDER BY Codigo desc LIMIT 1";

        $sql01 = DB::select($sql01);

        $LocalP = localpersonal::findOrFail($sql01[0]->Codigo);
        $LocalP->CodigoLocal = $request->get('idLocal');
        $LocalP->Cargo = $request->get('Cargo');
        $LocalP->Sueldo = $request->get('Sueldo');
        $LocalP->CorreoEmpresarial = $request->get('CorreoEmpresarial');
        $LocalP->CelularEmpresarial = $request->get('CelularEmpresarial');
        $LocalP->FechaInicio = $request->get('FechaInicio');
        $LocalP->FechaFin = $request->get('FechaInicio');
        $LocalP->save();

        return response()->json($personal, 200);
    }

    public function cambiarVigencia($id)
    {
        $sql00 = "SELECT Codigo FROM localpersonal
        WHERE CodigoPersonal = " . $id . " ORDER BY Codigo desc LIMIT 1";

        $sql0001 = DB::select($sql00);

        $codigoaux = $sql0001[0]->Codigo;

        $Lpersonal = localpersonal::findOrFail($codigoaux);
        $Lpersonal->Vigencia = !$Lpersonal->Vigencia;
        $Lpersonal->save();
        /*Cambiar estado al usuario*/

        $sql02 = "SELECT Codigo FROM usuario
        WHERE CodigoPersonal = " . $id . " ORDER BY Codigo desc LIMIT 1";
        $sql03 = DB::select($sql02);

        // $codigoaux01 = $sql03[0]->Codigo;
        // $usu = Usuario::findOrFail($codigoaux01);
        // $usu->Vigencia = !$usu->Vigencia;
        // $usu->save();

        return response()->json($Lpersonal, 200);
    }
    public function listar($txt, $op)
    {
        if ($op == "Nombres") {

            $sql = 'SELECT @i:=@i+1 as contador, pe.Codigo, lp.Vigencia, pe.Nombres, pe.ApellidoPaterno, pe.ApellidoMaterno, doc.Nombre as Documento, pe.NumeroDocumento, pe.Direccion, pe.Telefono,pe.Correo, pe.Celular, lo.Nombre as nomlocal FROM personal as pe
            INNER JOIN documentopersona as doc on doc.Codigo = pe.CodigoDocumentoPersona
            INNER JOIN localpersonal as lp on lp.CodigoPersonal = pe.Codigo
            INNER JOIN `local` as  lo on lo.Codigo = lp.CodigoLocal
            CROSS JOIN (SELECT @i := 0) r
            WHERE pe.Nombres LIKE "%' . $txt . '%" or pe.ApellidoPaterno LIKE "%' . $txt . '%" or pe.ApellidoMaterno LIKE "%' . $txt . '%"';
        }

        if ($op == "Documento") {

            $sql = 'SELECT @i:=@i+1 as contador, pe.Codigo, lp.Vigencia, pe.Nombres, pe.ApellidoPaterno, pe.ApellidoMaterno, doc.Nombre as Documento, pe.NumeroDocumento, pe.Direccion, pe.Telefono,pe.Correo, pe.Celular, lo.Nombre as nomlocal FROM personal as pe
            INNER JOIN documentopersona as doc on doc.Codigo = pe.CodigoDocumentoPersona
            INNER JOIN localpersonal as lp on lp.CodigoPersonal = pe.Codigo
            INNER JOIN `local` as  lo on lo.Codigo = lp.CodigoLocal
            CROSS JOIN (SELECT @i := 0) r
            WHERE  pe.NumeroDocumento = ' . $txt . ' ';
        }

        $personal = DB::select($sql);

        return response()->json($personal, 200);
    }

    public function leer($id)
    {
        return Personal::find($id);
    }

    public function listarVigente(Request $request)
    {
        $sql = "SELECT @i:=@i+1 as contador, Nombres, ApellidoPaterno, ApellidoMaterno, Codigo FROM personal
        CROSS JOIN (SELECT @i := 0) r
        WHERE Vigencia = 1";

        $personal = DB::select($sql);

        return response()->json($personal, 200);
    }
}
