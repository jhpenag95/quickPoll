<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;




class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('index');
    }

    public function login(Request $request)
    {
        // aqui se validan los datos de entrada
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // aqui se verifica si las credenciales son correctas
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            // si es una solicitud ajax o json se retorna una respuesta json
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'redirect' => route('dashboard')]);
            }
            
            return redirect()->intended(route('dashboard'));
        }

        // si no es una solicitud ajax o json se retorna una respuesta con un mensaje de error y los datos de entrada
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false, 
                'message' => 'Las credenciales proporcionadas no coinciden con nuestros registros.'
            ], 401);
        }

        // si no es una solicitud ajax o json se redirige al formulario de inicio de sesión con un mensaje de error
        return back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors(['email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

}
