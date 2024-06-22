<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function configurarNegocio(){
        $negocio = DB::connection('mysql')->table('negocio')->first();
        return view('config', ["negocio" => $negocio]);
    }

    public function editarNegocio(Request $request){
        $nombre = $request->input('nombre');
        $nit = $request->input('nit');
        $direccion = $request->input('direccion');
        $telefono = $request->input('telefono');
        $barrio = $request->input('barrio');
        $propietario = $request->input('propietario');
        $resolucion = $request->input('resolucion');

        DB::connection('mysql')->table('negocio')
        ->where('id', 1)
        ->update([
            'nombre' => $nombre,
            'nit' => $nit,
            'direccion' => $direccion,
            'telefono' => $telefono,
            'barrio' => $barrio,
            'propietario' => $propietario,
            'resolucion' => $resolucion,
        ]);

        $negocio = DB::connection('mysql')->table('negocio')->first();
        return view('config', ["negocio" => $negocio]);
    }
}
