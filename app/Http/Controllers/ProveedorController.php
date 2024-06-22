<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ProveedorController extends Controller
{
    public function index()
    {
        $proveedores =  DB::connection('mysql')->table('proveedores')->orderBy("proveedores.nombre", "ASC")->get();
       
        return view('proveedores.proveedores_index', ["proveedores" => $proveedores]);
    }

    public function guardarProveedor(Request $request){
        $nombre = $request->input('nombre');
        
        $existeCategoria = DB::connection('mysql')->table('proveedores')
        ->where('nombre', $nombre)
        ->exists();

        if (!$existeCategoria) {
            DB::connection('mysql')->table('proveedores')
            ->insert([
                'nombre' => $nombre,
            ]);
        }else{
            return redirect()->back()->withErrors(['mensaje' => 'fallo']);
        }

        return redirect()->route("proveedores");
    }

    public function editarProveedor(Request $request){
        $id = $request->input('id');
        $nombre = $request->input('nombre');
               
        DB::connection('mysql')->table('proveedores')
        ->where("id", $id)
        ->update([
            'nombre' => $nombre,
        ]);
        

        return redirect()->route("proveedores");
    }

    public function eliminarProveedor(Request $request){
        $id = $request->input('id');
               
        $hayCompras = DB::connection('mysql')->table('compras')
        ->where("proveedor", $id)
        ->count();

        if($hayCompras == 0){
            $deleted = DB::connection('mysql')->table('proveedores')
            ->where("id", $id)
            ->delete();
            
            if($deleted){
                $response = [
                    'status' => 'success',
                    'message' => 'El proveedor ha sido eliminado exitosamente.'
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'No se pudo eliminar el proveedor.'
                ];
            }
        }else{
            $response = [
                'status' => 'error',
                'message' => 'No se pudo eliminar el proveedor, ya que existen compras asociadas a este.'
            ];
        }
    
        return response()->json($response);
    }

    public function buscarProveedor(Request $request){
        $des =  $request->input('des');
        if($des == "todo"){
            $proveedores =  DB::connection('mysql')->table('proveedores')
            ->orderBy("proveedores.nombre", "ASC")
            ->get();
        }else{
            $proveedores =  DB::connection('mysql')->table('proveedores')
            ->where("nombre", 'like', '%' . $des . '%')
            ->orderBy("proveedores.nombre", "ASC")
            ->get();
        }
        return response()->json($proveedores);
    }

    public function registrarProveedorMovil(Request $request){
        $nombre = $request->input('nombre');
        
        $existeProveedor = DB::connection('mysql')->table('proveedores')
        ->where('nombre', $nombre)
        ->exists();

        if (!$existeProveedor) {
            DB::connection('mysql')->table('proveedores')
            ->insert([
                'nombre' => $nombre,
            ]);

            $response = [
                'success' => 1,
                'mensaje' => 'El proveedor se registro correctamente.'
            ];
        }else{
            $response = [
                'success' => 0,
                'mensaje' => 'El proveedor con ese nombre ya existe.'
            ];
        }

        return response()->json($response);
    }

    public function editarProveedorMovil(Request $request){
        $id = $request->input('id');
        $nombre = $request->input('nombre');
               
        DB::connection('mysql')->table('proveedores')
        ->where("id", $id)
        ->update([
            'nombre' => $nombre,
        ]);
        
        $response = [
            'success' => 1,
            'mensaje' => 'Se modifico el proveedor correctamente.'
        ];

        return response()->json($response);
    }

    public function eliminarProveedorMovil(Request $request){
        $id = $request->input('id');
               
        $hayCompras = DB::connection('mysql')->table('compras')
        ->where("proveedor", $id)
        ->count();

        if($hayCompras == 0){
            $deleted = DB::connection('mysql')->table('proveedores')
            ->where("id", $id)
            ->delete();
            
            if($deleted){
                $response = [
                    'success' => 1,
                    'mensaje' => 'El proveedor ha sido eliminado exitosamente.'
                ];
            } else {
                $response = [
                    'success' => 0,
                    'mensaje' => 'No se pudo eliminar el proveedor.'
                ];
            }
        }else{
            $response = [
                'success' => 0,
                'mensaje' => 'No se pudo eliminar el proveedor, ya que existen compras asociadas a este.'
            ];
        }
    
        return response()->json($response);
    }
}
