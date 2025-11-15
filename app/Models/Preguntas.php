<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Preguntas extends Model
{
    //
    protected $table = 'preguntas';
    protected $primaryKey = 'idPregunta';
    public $timestamps = false;
    protected $fillable = [
        'idEncuesta',
        'textoPregunta',
        'tipoPregunta',
        'obligatoria',
        'orden',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
