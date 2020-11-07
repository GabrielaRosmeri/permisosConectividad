<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentoPersona extends Model
{
    use HasFactory;
    protected $table = "documentopersona";
    protected $primaryKey = 'Codigo';
    public $timestamps = false;
    protected $fillable = [
        'Codigo',
        'Nombre',
        'CodigoSUNAT',
        'Tipo',
        'CantidadMinima',
        'CantidadMaxima',
        'Vigencia'
    ];
}
