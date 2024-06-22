<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use App\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComprasController extends Controller
{
    public function index(){        
     
        $date = date("d/m/Y");

        $tipo_logueado = session('user_tipo');

        $proveedores = DB::connection('mysql')->table('proveedores')
        ->orderBy("nombre", "ASC")
        ->get();

        $tipos_usuarios = DB::connection('mysql')->table('tipo_usuario')
        ->where("tipo", "<>", 1)
        ->get();

        if($tipo_logueado == 1){
            $compras = DB::connection('mysql')->table('compras')
            ->join("proveedores", "proveedores.id", "proveedor")
            ->select("compras.*", "proveedores.nombre")
            ->orderBy("compras.id", "DESC")
            ->get();
    
            $totalCompradoHoy = DB::connection('mysql')->table('compras')
            ->where("fecha", $date)
            ->sum("total");
    
            $totalComprado = DB::connection('mysql')->table('compras')
            ->sum("total");
        }else{
            $compras = DB::connection('mysql')->table('compras')
            ->join("proveedores", "proveedores.id", "proveedor")
            ->select("compras.*", "proveedores.nombre")
            ->where("tipo_compra", $tipo_logueado)
            ->orderBy("compras.id", "DESC")
            ->get();
    
            $totalCompradoHoy = DB::connection('mysql')->table('compras')
            ->where("fecha", $date)
            ->where("tipo_compra", $tipo_logueado)
            ->sum("total");
    
            $totalComprado = DB::connection('mysql')->table('compras')
            ->where("tipo_compra", $tipo_logueado)
            ->sum("total");
        }
        

        return view("compras.compras_index", [
            "proveedores" => $proveedores,
            "compras" => $compras, 
            "totalCompradoHoy" => $totalCompradoHoy,
            "totalComprado" => $totalComprado,
            "tipos_usuarios" => $tipos_usuarios
        ]);
    }

    public function guardarCompra(Request $request){
        $total = $request->input('total');
        $proveedor = $request->input('proveedor');
        $date = $request->input('fecha_compra');
        $tipo_compra = session('user_tipo');

        if($tipo_compra != 1){
            $tipo_compra = session('user_tipo');
        }else{
            $tipo_compra = $request->input('tipo_compra');
        }

        $partesFecha = explode('/', $date);
        $fechaConvertida = $partesFecha[2] . '-' . $partesFecha[1] . '-' . $partesFecha[0];

        DB::connection('mysql')->table('compras')
        ->insert([
            'proveedor' => $proveedor,
            'total' => $total,
            'fecha' => $date,
            'fecha_b' => $fechaConvertida,
            'tipo_compra' => $tipo_compra
        ]);

        return redirect()->route("compras.index");
    }


    public function eliminarCompra(Request $request){
        $id_compra = $request->input('id_compra');
        
        DB::connection('mysql')->table('compras')
        ->where("id", $id_compra)
        ->delete();

        return redirect()->route("compras.index");
    }
}
