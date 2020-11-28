<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SistemaPensionesController extends Controller
{
    public function listarVigente()
    {
        $sql = 'SELECT Codigo, Siglas FROM `sistemapensiones`
        WHERE Vigencia = 1';
        $data = DB::select($sql);

        return response()->json($data, 200);
    }
}
