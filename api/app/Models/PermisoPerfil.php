<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermisoPerfil extends Model
{
    use HasFactory;
    protected $table = "permisoperfil";
    protected $primaryKey = 'Codigo';
    protected $fillable = [
        'Codigo',
        'Permitido',
        'CodigoOpcion',
        'CodigoPerfil'
    ];
    public $timestamps = false;
}
