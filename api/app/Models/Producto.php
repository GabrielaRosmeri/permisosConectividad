<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    protected $table = "producto";
    protected $primaryKey = 'Codigo';
    public $timestamps = false;
    protected $fillable = [
        'Codigo',
        'CodigoCategoria',
        'CodigoMarca',
        'Tipo',
        'Negociable',
        'Nombre',
        'TipoControl',
        'Vigencia'
    ];
}
