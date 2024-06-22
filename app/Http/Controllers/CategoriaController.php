<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias =  DB::connection('mysql')->table('categorias')->orderBy("categorias.nombre", "ASC")->get();
       
        return view('categorias.categorias_index', ["categorias" => $categorias]);
    }

    public function guardarCategoria(Request $request){
        $nombre = $request->input('nombre');
        
        $existeCategoria = DB::connection('mysql')->table('categorias')
        ->where('nombre', $nombre)
        ->exists();

        if (!$existeCategoria) {
            DB::connection('mysql')->table('categorias')
            ->insert([
                'nombre' => $nombre,
            ]);
        }else{
            return redirect()->back()->withErrors(['mensaje' => 'fallo']);
        }

        return redirect()->route("categorias");
    }

    public function editarCategoria(Request $request){
        $id = $request->input('id');
        $nombre = $request->input('nombre');
               
        DB::connection('mysql')->table('categorias')
        ->where("id", $id)
        ->update([
            'nombre' => $nombre,
        ]);
        

        return redirect()->route("categorias");
    }

    public function eliminarCategoria(Request $request){

        $id = $request->input('id');
               
        $cat = DB::connection('mysql')->table('categorias')
        ->where("id", $id)
        ->first();
        
        $cont = DB::connection('mysql')->table('productos')
        ->where("categoria", $cat->nombre)
        ->count();

        if($cont == 0){
            $deleted = DB::connection('mysql')->table('categorias')
            ->where("id", $id)
            ->delete();

            if($deleted){
                $response = [
                    'status' => 'success',
                    'message' => 'La categoría ha sido eliminada exitosamente.'
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'No se pudo eliminar la categoría.'
                ];
            }
        }else{
            $response = [
                'status' => 'error',
                'message' => 'No se pudo eliminar la categoría, ya que esta esta asociada a uno o varios productos.'
            ];
        }
        return response()->json($response);
    }

    public function listarCategorias(Request $request){
        $des =  $request->input('des');
        if($des == "todo"){
            $categorias =  DB::connection('mysql')->table('categorias')
            ->orderBy("categorias.nombre", "ASC")
            ->get();
        }else{
            $categorias =  DB::connection('mysql')->table('categorias')
            ->where("nombre", 'like', '%' . $des . '%')
            ->orderBy("categorias.nombre", "ASC")
            ->get();
        }
        return response()->json($categorias);
    }

    public function guardarCategoriaMovil(Request $request){
        $nombre = $request->input('nombre');
        
        $existeCategoria = DB::connection('mysql')->table('categorias')
        ->where('nombre', $nombre)
        ->exists();

        if (!$existeCategoria) {
            DB::connection('mysql')->table('categorias')
            ->insert([
                'nombre' => $nombre,
            ]);

            $response = [
                'success' => 1,
                'mensaje' => 'La categoría ha sido creada exitosamente.'
            ];

        }else{
            $response = [
                'success' => 0,
                'mensaje' => 'Ya existe una categoría con ese nombre'
            ];
        }

        return response()->json($response);
    }

    public function editarCategoriaMovil(Request $request){
        $id = $request->input('id');
        $nombre = $request->input('nombre');

        $cat = DB::connection('mysql')->table('categorias')
        ->where("id", $id)
        ->first();

        DB::connection('mysql')->table('productos')
        ->where("categoria", $cat->nombre)
        ->update([
            'categoria' => $nombre,
        ]);
               
        DB::connection('mysql')->table('categorias')
        ->where("id", $id)
        ->update([
            'nombre' => $nombre,
        ]);

        $response = [
            'success' => 1,
            'mensaje' => 'Se modifico la categoría correctamente.'
        ];

        return response()->json($response);
    }

    public function eliminarCategoriaMovil(Request $request){
        $id = $request->input('id');
               
        $cat = DB::connection('mysql')->table('categorias')
        ->where("id", $id)
        ->first();
        
        $cont = DB::connection('mysql')->table('productos')
        ->where("categoria", $cat->nombre)
        ->count();


        if($cont == 0){
            $deleted = DB::connection('mysql')->table('categorias')
            ->where("id", $id)
            ->delete();

            if($deleted){
                $response = [
                    'success' => 1,
                    'mensaje' => 'La categoría ha sido eliminada exitosamente.'
                ];
            } else {
                $response = [
                    'success' => 0,
                    'mensaje' => 'No se pudo eliminar la categoría.'
                ];
            }
        }else{
            $response = [
                'success' => 0,
                'mensaje' => 'No se pudo eliminar la categoría, ya que esta esta asociada a uno o varios productos.'
            ];
        }

        return response()->json($response);
    }

}
