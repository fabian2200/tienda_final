<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;

class RecargasController extends Controller
{
    public function index(Request $request){  
        $fecha1 = $request->input("fecha1");
        $fecha2 = $request->input("fecha2");

        
        $recargas = DB::connection('mysql')->table('recargas')
        ->select("recargas.*")
        ->whereBetween("recargas.fecha", [$fecha1, $fecha2])
        ->orderByRaw("CONCAT(recargas.fecha, ' ', recargas.hora) DESC")
        ->get();


        $total = DB::connection('mysql')->table('recargas')
        ->whereBetween("recargas.fecha", [$fecha1, $fecha2])
        ->sum('recargas.monto');
       
        return view("retirosyrecargas.recarga", [
            "recargas" => $recargas,
            "total" => $total, 
        ]);
    }

    public function guardarRecargaPaquete(Request $request){
        $monto = $request->input("monto");
        $operador = $request->input("operador");
        $tipo = $request->input("tipo");
        $id_usuario = session("user_id");

        $insertado = DB::connection('mysql')->table('recargas')
        ->insert(
            [
                "monto" => $monto,
                "operador" => $operador,
                "tipo" => $tipo,
                "id_usuario" => $id_usuario,
                "fecha" => date('Y-m-d'),
                "fecha_bien" => date("d/m/Y"),
                "hora" => date("H:i:s")
            ]
        );

        if($insertado){
            $response = [
                'status' => 'success',
                'message' => 'Se ha registrado el movimiento exitosamente.'
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Ocurrió un error, intente mas tarde.'
            ];
        }
    
        return response()->json($response);
    }

    public function editarRecargaPaquete(Request $request){
        $id_recarga = $request->input("id_recarga");
        $monto = $request->input("monto");
        $operador = $request->input("operador");
        $tipo = $request->input("tipo");

        $actualizado = DB::connection('mysql')->table('recargas')
        ->where("id", $id_recarga)
        ->update(
            [
                "monto" => $monto,
                "operador" => $operador,
                "tipo" => $tipo,
            ]
        );

        if($actualizado){
            $response = [
                'status' => 'success',
                'message' => 'Se ha modificado el movimiento exitosamente.'
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Ocurrió un error, intente mas tarde.'
            ];
        }
    
        return response()->json($response);
    }

    public function eliminarRecargaPaquete(Request $request){
        $id_recarga = $request->input("id_recarga");

        $actualizado = DB::connection('mysql')->table('recargas')
        ->where("id", $id_recarga)
        ->delete();

        if($actualizado){
            $response = [
                'status' => 'success',
                'message' => 'Se ha eliminado el movimiento exitosamente.'
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Ocurrió un error, intente mas tarde.'
            ];
        }
    
        return response()->json($response);
    }

    public function index2(Request $request){  
        $fecha1 = $request->input("fecha1");
        $fecha2 = $request->input("fecha2");

        
        $consignacion_retiro = DB::connection('mysql')->table('consignacion_retiro')
        ->select("consignacion_retiro.*")
        ->whereBetween("consignacion_retiro.fecha", [$fecha1, $fecha2])
        ->orderByRaw("CONCAT(consignacion_retiro.fecha, ' ', consignacion_retiro.hora) DESC")
        ->get();


        $total1 = DB::connection('mysql')->table('consignacion_retiro')
        ->where("tipo", "Retiro")
        ->whereBetween("consignacion_retiro.fecha", [$fecha1, $fecha2])
        ->sum('consignacion_retiro.monto');

        $total2 = DB::connection('mysql')->table('consignacion_retiro')
        ->where("tipo", "Consignacion")
        ->whereBetween("consignacion_retiro.fecha", [$fecha1, $fecha2])
        ->sum('consignacion_retiro.monto');
       
        return view("retirosyrecargas.consignacionesretiros", [
            "consignacion_retiro" => $consignacion_retiro,
            "total1" => $total1, 
            "total2" => $total2, 
        ]);
    }

    public function guardarMovimiento(Request $request){
        $monto = $request->input("monto");
        $banco = $request->input("banco");
        $tipo = $request->input("tipo");
        $id_usuario = session("user_id");

        $insertado = DB::connection('mysql')->table('consignacion_retiro')
        ->insert(
            [
                "monto" => $monto,
                "banco" => $banco,
                "tipo" => $tipo,
                "id_usuario" => $id_usuario,
                "fecha" => date('Y-m-d'),
                "fecha_bien" => date("d/m/Y"),
                "hora" => date("H:i:s")
            ]
        );

        if($insertado){
            $response = [
                'status' => 'success',
                'message' => 'Se ha registrado el movimiento exitosamente.'
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Ocurrió un error, intente mas tarde.'
            ];
        }
    
        return response()->json($response);
    }

    public function editarMovimiento(Request $request){
        $id_movimiento = $request->input("id_movimiento");
        $monto = $request->input("monto");
        $banco = $request->input("banco");
        $tipo = $request->input("tipo");

        $insertado = DB::connection('mysql')->table('consignacion_retiro')
        ->where("id", $id_movimiento)
        ->update(
            [
                "monto" => $monto,
                "banco" => $banco,
                "tipo" => $tipo,
            ]
        );

        if($insertado){
            $response = [
                'status' => 'success',
                'message' => 'Se ha modificado el movimiento exitosamente.'
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Ocurrió un error, intente mas tarde.'
            ];
        }
    
        return response()->json($response);
    }

    public function eliminarMovimiento(Request $request){
        $id_movimiento = $request->input("id_movimiento");

        $actualizado = DB::connection('mysql')->table('consignacion_retiro')
        ->where("id", $id_movimiento)
        ->delete();

        if($actualizado){
            $response = [
                'status' => 'success',
                'message' => 'Se ha eliminado el movimiento exitosamente.'
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Ocurrió un error, intente mas tarde.'
            ];
        }
    
        return response()->json($response);
    }

}
