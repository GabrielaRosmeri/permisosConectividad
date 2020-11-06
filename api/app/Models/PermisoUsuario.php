<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermisoUsuario extends Model
{
    use HasFactory;
    protected $table = "permisousuario";
    protected $primaryKey = 'Codigo';
    protected $fillable = [
        'Codigo',
        'Permitido',
        'CodigoUsuario',
        'CodigoOpcion'
    ];
    public $timestamps = false;
}
