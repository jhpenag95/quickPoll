<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\registroEmpresa;
use App\Models\User;
use Illuminate\Support\Str;

class RegistroEmpresaController extends Controller
{
    public function registroEmpresa()
    {
        return view('registroEmpresa');
    }

    public function store(Request $request)
    {

        // Validaciones con mensajes claros
        $request->validate([
            'nombreEmpresa' => 'required|string|max:255',
            'nit'           => 'required|string|max:50|unique:empresas,nit',
            'direccion'     => 'required|string|max:255',
            'telefono'      => 'required|string|max:50',
            'emailEmpresa'  => 'required|email|max:255|unique:empresas,email',
            'nombreUser'    => 'required|string|max:255',
            'emailUser'     => 'required|email|max:255|unique:users,email',
            'password'      => 'required|string|min:6',
        ], [
            'nombreEmpresa.required' => 'El nombre de la empresa es obligatorio.',
            'nit.required'           => 'El NIT es obligatorio.',
            'nit.unique'             => 'El NIT ingresado ya está registrado.',
            'direccion.required'     => 'La dirección es obligatoria.',
            'telefono.required'      => 'El teléfono es obligatorio.',
            'emailEmpresa.required'  => 'El correo de la empresa es obligatorio.',
            'emailEmpresa.email'     => 'El correo de la empresa no tiene un formato válido.',
            'emailEmpresa.unique'    => 'El correo de la empresa ya está registrado.',
            'nombreUser.required'    => 'El nombre del usuario es obligatorio.',
            'emailUser.required'     => 'El correo del usuario es obligatorio.',
            'emailUser.email'        => 'El correo del usuario no tiene un formato válido.',
            'emailUser.unique'       => 'El correo del usuario ya está registrado.',
            'password.required'      => 'La contraseña es obligatoria.',
            'password.min'           => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        $fechaRegistroEmpresa = date('Y-m-d');
        $created_at           = date('Y-m-d H:i:s');
        $updated_at           = date('Y-m-d H:i:s');

        $empresa = registroEmpresa::create([
            'nombre'            => $request->nombreEmpresa,
            'nit'               => $request->nit,
            'direccion'         => $request->direccion,
            'telefono'          => $request->telefono,
            'email'             => $request->emailEmpresa,
            'fechaRegistro'     => $fechaRegistroEmpresa,
            'estado'            => 'ACTIVO',
            'created_at'        => $created_at,
            'updated_at'        => $updated_at,
        ]);

        $user = $empresa->users()->create([
            'name'              => $request->nombreUser,
            'email'             => $request->emailUser,
            'password'          => bcrypt($request->password),
            'rol'               => $request->rol,
            'estado'            => 'ACTIVO',
            'fechaRegistro'     => $fechaRegistroEmpresa,
            'remember_token'    => Str::random(60),
            'created_at'        => $created_at,
            'updated_at'        => $updated_at,
        ]);

        if ($user && $empresa) {
            if ($request->ajax()) {
                return response()->json(['status' => 'success']);
            }
            return redirect()->route('index');
        } else {
            return response()->json([
                'status' => 'error',
                'message' => $request->all()
            ]);
        }

    }
}
