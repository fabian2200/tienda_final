<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DNS1D;
use DB;

use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;

class BarcodeController extends Controller
{

    public function index(){  
        $codigos = DB::connection('mysql')->table('codigos_barra')
        ->select("codigos_barra.*")
        ->orderByRaw("CONCAT(codigos_barra.fecha, ' ', codigos_barra.hora) DESC")
        ->get();
       
        return view("codigos_barra.codigo_barra_index", [
            "codigos" => $codigos,
        ]);
    }

    public function generateBarcode(Request $request){

        $numero = $request->input("codigo");
        $descripcion = $request->input("descripcion");

        $barcode = DNS1D::getBarcodePNG($numero, 'C128');
        $barcodeImage = 'data:image/png;base64,' . $barcode;

        $insertado = DB::connection('mysql')->table('codigos_barra')
        ->insert(
            [
                "numero" => $numero,
                "descripcion" => $descripcion,
                "imagen" => $barcodeImage,
                "fecha" => date("d/m/Y"),
                "hora" => date("H:i:s")
            ]
        );

        if($insertado){
            $response = [
                'status' => 'success',
                'message' => 'Se ha registrado el código de barras exitosamente.'
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Ocurrió un error, intente mas tarde.'
            ];
        }

        return response()->json($response);
    }

    public function editarCodigo(Request $request){
        $id_codigo = $request->input("id_codigo");
        $descripcion = $request->input("descripcion");

        $actualizado = DB::connection('mysql')->table('codigos_barra')
        ->where("id", $id_codigo)
        ->update(
            [
                "descripcion" => $descripcion
            ]
        );

        if($actualizado){
            $response = [
                'status' => 'success',
                'message' => 'Se ha modificado el código de barras exitosamente.'
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Ocurrió un error, intente mas tarde.'
            ];
        }
    
        return response()->json($response);
    }


    public function eliminarCodigo(Request $request){
        $id_codigo = $request->input("id_codigo");

        $codigo_eliminar = DB::connection('mysql')->table('codigos_barra')
        ->where("id", $id_codigo)
        ->first();

        $asociado = DB::connection('mysql')->table('productos')
        ->where("codigo_barras", $codigo_eliminar->numero)
        ->count();

        if($asociado == 0){
            $eliminado = DB::connection('mysql')->table('codigos_barra')
            ->where("id", $id_codigo)
            ->delete();

            if($eliminado){
                $response = [
                    'status' => 'success',
                    'message' => 'Se ha eliminado el código de barras exitosamente.'
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Ocurrió un error, intente mas tarde.'
                ];
            }
        }else{
            $response = [
                'status' => 'error',
                'message' => 'Este código de barras esta asociado a un producto'
            ];
        }
    
        return response()->json($response);
    }

    public function imprimirCodigoBarra(Request $request){
        $numCopies = $request->input("numero");
        $barcodeData = $request->input("codigo");
        $productName = $request->input("nombre");

        $ipAddress = "192.168.1.100"; 
        $port = 9100;
        $connector = new NetworkPrintConnector($ipAddress, $port);
        $printer = new Printer($connector);
        $printer->initialize();

        $barcodeHeight = 60; 
        $barcodeWidth = 2;

        for ($i = 0; $i < $numCopies; $i++) {
            $labelWidth = 320; 
            $labelHeight = 250;
        
            $marginLeft = ($labelWidth - ($barcodeWidth * strlen($barcodeData))) / 2;
            $marginTop = ($labelHeight - $barcodeHeight) / 2;
        
            $printer->feed($marginTop); 
            $printer->setJustification(Printer::JUSTIFY_CENTER);
        
            $printer->text($productName."\n");
            $printer->setBarcodeHeight($barcodeHeight);
            $printer->setBarcodeWidth($barcodeWidth);
            $printer->barcode($barcodeData, Printer::BARCODE_CODE128);
            $printer->text("\n".$barcodeData."\n");
        
            $printer->feed();
        }
        
        $printer->cut();
        $printer->close();

        $response = [
            'status' => 'error',
            'message' => 'Se imprimió el código de barras correctamente'
        ];

        return $response;
    }
}