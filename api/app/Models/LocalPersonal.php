<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocalPersonal extends Model
{
    use HasFactory;
    protected $table = "localpersonal";
    protected $primaryKey = 'Codigo';
    public $timestamps = false;
    protected $fillable = [
        'Codigo',
        'CodigoPersonal',
        'CodigoLocal',
        'Cargo',
        'Sueldo',
        'CorreoEmpresarial',
        'CelularEmpresarial',
        'FechaInicio',
        'FechaFin',
        'Vigencia',
    ];
}
