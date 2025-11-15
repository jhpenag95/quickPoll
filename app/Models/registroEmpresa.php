<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class registroEmpresa extends Model
{
    protected $table = 'empresas';
    protected $primaryKey = 'idEmpresa';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'nombre',
        'nit',
        'direccion',
        'telefono',
        'email',
        'fechaRegistro',
        'estado',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'empresa_id', 'idEmpresa');
    }
}
