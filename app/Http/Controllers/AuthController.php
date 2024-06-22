<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use DB;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users',
            'password' => 'required|string|confirmed',
        ]);
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $user->save();
        return response()->json([
            'message' => 'Successfully created user!'], 201);
    }

    public function login(Request $request)
    {
    
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
            'remember_me' => 'boolean',
        ]);
        
        $credentials = request(['email', 'password']);
        if(Auth::attempt($credentials)) {
            $user = $request->user();

            $tipo_user =  DB::connection('mysql')->table('tipo_usuario')
            ->where("tipo", $user->tipo)
            ->first();

            session(['user_id' => $user->id]);
            session(['user_tipo' => $user->tipo]);
            session(['tipo_usuario' => $tipo_user->tipo_desc]);

            return redirect()->route("home");
        }else{
            return redirect()->back()->withErrors(['mensaje' => 'Credenciales incorrectas. Por favor, inténtalo de nuevo.']);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' =>
            'Successfully logged out']);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function listarImpresoras(){
        $impresoras =  DB::connection('mysql')->table('impresoras')
        ->get();

        return response()->json($impresoras);
    }
}
