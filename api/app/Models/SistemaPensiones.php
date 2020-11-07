<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SistemaPensiones extends Model
{
    use HasFactory;
    protected $table = "sistemapensiones";
    protected $primaryKey = 'Codigo';
    public $timestamps = false;
    protected $fillable = [
        'Codigo',
        'Nombre',
        'Siglas',
        'Vigencia'
    ];
}
