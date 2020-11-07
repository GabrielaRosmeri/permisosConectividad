<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    use HasFactory;
    protected $table = "personal";
    protected $primaryKey = 'Codigo';
    public $timestamps = false;
    protected $fillable = [
        'Codigo',
        'Nombres',
        'ApellidoPaterno',
        'ApellidoMaterno',
        'CodigoDocuemntoPersona',
        'NumeroDocumento',
        'Direccion',
        'Telefono',
        'Correo',
        'Celular',
        'CodigoSistemaPensiones',
        'Vigencia'
    ];
}
