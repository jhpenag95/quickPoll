<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Encuestas extends Model
{
    protected $table = 'encuestas';
    protected $primaryKey = 'idEncuesta';
    public $timestamps = false;
    protected $fillable = [
        'nombre',
        'descripcion',
        'fechaInicio',
        'fechaFin',
        'estado',
        'enlaceLargo',
        'enlaceCorto',
        'codigoQR',
        'idEmpresa',
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
