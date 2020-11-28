<?php

namespace App\Http\Controllers;

use App\Models\LocalPersonal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocalPersonalController extends Controller
{
    public function leer($id)
    {
        $sql01 = "SELECT Codigo FROM localpersonal
        WHERE CodigoPersonal = " . $id . " ORDER BY Codigo desc LIMIT 1";

        $personalID = DB::select($sql01);
        $auxCodigo = $personalID[0]->Codigo;

        return LocalPersonal::find($auxCodigo);
    }
}
