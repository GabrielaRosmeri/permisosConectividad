<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    use HasFactory;
    protected $table = "perfil";
    protected $primaryKey = 'Codigo';
    protected $fillable = [
        'Codigo',
        'Nombre',
        'Descripcion',
        'Vigencia'
    ];
    public $timestamps = false;
}
