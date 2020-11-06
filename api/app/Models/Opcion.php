<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opcion extends Model
{
    use HasFactory;
    protected $table = "opcion";
    protected $primaryKey = 'Codigo';
    protected $fillable = [
        'Codigo',
        'Nombre',
        'Descripcion',
        'URL',
        'Vigencia',
        'CodigoSistema'
    ];
    public $timestamps = false;
}
