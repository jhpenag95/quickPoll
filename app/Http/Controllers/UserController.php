<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        return view('usuarios.usuarios');
    }

    public function agregar_usuario()
    {
        return view('usuarios.agregarUsuario');
    }

    public function editar($id)
    {
        $user = User::findOrFail($id);
        return view('usuarios.editarUsuario', [
            'user' => $user,
        ]);
    }

    public function store(Request $request)
    {   
        // return $request->all();

        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'rol' => 'required|string|max:255',
            'estado' => 'required|string|max:255',
            'password' => 'required|string|min:8|max:255|confirmed',
            'idEmpresa' => 'required|integer|exists:empresas,idEmpresa',
        ], [
            'idEmpresa.exists' => 'La empresa seleccionada no existe.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'email.unique' => 'El correo electrónico ya está en uso.',
            'rol.in' => 'El rol seleccionado no es válido.',
            'estado.in' => 'El estado seleccionado no es válido.',
        ]);

        $user = User::create([
            'name' => $request->nombre,
            'email' => $request->email,
            'password' => $request->password,
            'rol' => $request->rol,
            'estado' => $request->estado,
            'fechaRegistro' => now()->toDateString(),
            'empresa_id' => $request->idEmpresa,
            'remember_token'    => Str::random(60),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($user) {
            if ($request->ajax()) {
                return response()->json(['status' => 'success']);
            }
        }else{
            return response()->json([
                'status' => 'error',
                'message' => $request->all()
            ]);
        }
    }

    public function listar(Request $request)
    {
        $usuarios = User::select('id', 'name as nombre', 'email', 'rol', 'estado', 'created_at')->get();
        return response()->json($usuarios); 
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'rol' => 'required|string|max:255',
            'estado' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|max:255|confirmed',
            'idEmpresa' => 'nullable|integer|exists:empresas,idEmpresa',
        ], [
            'idEmpresa.exists' => 'La empresa seleccionada no existe.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'email.unique' => 'El correo electrónico ya está en uso.',
        ]);

        $user->name = $request->nombre;
        $user->email = $request->email;
        $user->rol = $request->rol;
        $user->estado = $request->estado;
        if ($request->filled('idEmpresa')) {
            $user->empresa_id = $request->idEmpresa;
        }
        if ($request->filled('password')) {
            $user->password = $request->password;
        }
        $user->updated_at = now();
        $user->save();

        if ($request->ajax()) {
            return response()->json(['status' => 'success']);
        }
        return redirect()->route('usuarios')->with('status', 'Usuario actualizado correctamente');
    }
}
