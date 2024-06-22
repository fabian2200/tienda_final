<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Producto;
use App\ProductoVendido;
use App\Venta;
use Illuminate\Http\Request;
use App\Http\Controllers\VentasController;
use DB;

use GuzzleHttp\Client;

use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Codedge\Fpdf\Fpdf\Fpdf;

class DomiciliosController extends Controller
{
    public function obtenerDomicilios(){
        return view("ventas.domicilios");
    }

    public function terminarVentaDomicilio(Request $request){
        $venta = new Venta();
        $venta->fecha_venta = date("Y-m-d");
        $id_cliente =  $request->input('id_cliente');
        $direccion_cliente =  $request->input('direccion_cliente');
        $id_pedido =  $request->input('id_pedido');
        $imprimir_factura = $request->input("imprimir_factura");
        $venta->metodo_pago = $request->input("metodo_pago");
        $productos = $request->input('productos');
        $venta->total_dinero =  $request->input('total_dinero');
        $venta->total_fiado =  $request->input('total_fiado');
        $venta->total_pagar =  $request->input('total_pagar');
        $venta->total_vueltos =  $request->input('total_vueltos');
        $venta->id_cliente = $id_cliente;

        $venta->id_vendedor = session('user_id');
        $venta->tipo_venta = session('user_tipo');
        $venta->total_con_domi = (double) $request->input("total_pagar_con_domi");
        $venta->valor_domicilio = $venta->total_con_domi - $venta->total_pagar;

        $tipo_venta = session('user_tipo');

        if($tipo_venta != 1){
       
            $venta->saveOrFail();

            if($venta->total_fiado > 0){
                $this->guardarFiado($venta->id_cliente, $venta->id,  $venta->total_fiado);
            }

            $idVenta = $venta->id;

            $lista_productos = [];
            foreach ($productos as $producto) {
                $productoVendido = new ProductoVendido();
                $productoVendido->fill([
                    "id_venta" => $idVenta,
                    "descripcion" => $producto["descripcion"],
                    "codigo_barras" => $producto["codigo_barras"],
                    "precio" => $producto["precio"],
                    "cantidad" => $producto["cantidad"],
                    "unidad" => $producto["unidad"] == "Kilos" ? "Kg" : ($producto["unidad"] == "Libras" ? "Lb" : "Und")
                ]);

                $productoVendido->saveOrFail();

                $productoActualizado = Producto::where('codigo_barras', $producto["codigo_barras"])->first();
                $productoActualizado->existencia -= $productoVendido->cantidad;
                
                
                DB::connection('mysql')->table('productos')
                ->where('codigo_barras', $producto["codigo_barras"])
                ->update([
                    'existencia' => $productoActualizado->existencia
                ]);
                

                $lista_productos[] = [
                    "codigo_barras" => $producto["codigo_barras"],
                    "existencia" => $productoActualizado->existencia
                ];
            }
            
            $myVariable = $this->ticket($idVenta, $imprimir_factura, $direccion_cliente);
            //$this->actualizarCantidadesProductos($lista_productos, $id_pedido);

            return response()->json([
                'status' => 'success',
                'message' => 'Venta terminada',
                'data' => [
                    'venta_id' => $idVenta,
                    'ticket' => $myVariable,
                ],
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'No puede realizar una venta como administrador.',
            ]);
        }
    }

    public function actualizarCantidadesProductos($lista_productos, $id_pedido){
        if (checkdnsrr('example.com', 'A')) {
            $client = new Client();

            $url = 'http://192.168.1.76/tienda2/actualizar_final.php';

            $data = [
                "productos" => json_encode($lista_productos),
                "id_pedido" => $id_pedido
            ];

            $response = $client->post($url, [
                'form_params' => $data
            ]);

            $response = $response->getBody();
            $body = json_decode($response, true);
            
            return $body;
        }
    }

    public function ticket($idVenta, $imprimir_factura, $direccion){
        $negocio = DB::connection('mysql')->table('negocio')->first();
        $usuario = DB::connection('mysql')->table('users')->where('id', session('user_id'))->first();
        $ipImpresora = $usuario->ip_impresora;
        $puertoImpresora = env("PUERTO_IMPRESORA");

        $venta = Venta::findOrFail($idVenta);

        if($imprimir_factura == "si"){
            $connector = new NetworkPrintConnector($ipImpresora, $puertoImpresora);
            $impresora = new Printer($connector);
            $impresora->setJustification(Printer::JUSTIFY_CENTER);
            $impresora->setEmphasis(true);
            $impresora->text("Ticket de venta: #".$idVenta."\n");
            $impresora->text($venta->created_at . "\n");
            $impresora->text($negocio->nombre."\n");
            $impresora->text("NIT ".$negocio->nit."\n");
            $impresora->text($negocio->direccion."\n");
            $impresora->text("Barrio ".$negocio->barrio."\n");
            $impresora->text("Cel: ".$negocio->telefono."\n");
            $impresora->setEmphasis(false);
            $impresora->text("\nCliente: ");
            $impresora->text($venta->cliente->nombre . "\n");
            $impresora->text("\nDetalle del domicilio\n");
            $impresora->text("\n____________________________________________\n\n");
            $total = 0;
            foreach ($venta->productos as $producto) {

                $subtotal = $producto->cantidad * $producto->precio;
                $total = $total + self::redondearAl100($subtotal);
                $impresora->setJustification(Printer::JUSTIFY_LEFT);
                $producto_text = sprintf("%.2f %s x %s", $producto->cantidad, $producto->unidad, $producto->descripcion);
                $precio_text = '$' . number_format(self::redondearAl100($subtotal), 2);
                $line_length = 48;
                $combined_text = $producto_text . str_repeat(' ', $line_length - strlen($producto_text) - strlen($precio_text)) . $precio_text;
                $impresora->text($combined_text . "\n");
            }
            $impresora->setJustification(Printer::JUSTIFY_RIGHT);
            $impresora->setEmphasis(true);
            $impresora->setTextSize(1, 1); 
            $impresora->text("\nSubtotal: $" . number_format(self::redondearAl100($total), 2) . "\n");
            $impresora->text("Domicilio: $" . number_format(self::redondearAl100($venta->valor_domicilio), 2) . "\n");
            $impresora->text("Total: $" . number_format(self::redondearAl100($venta->total_con_domi), 2) . "\n");
            $impresora->setJustification(Printer::JUSTIFY_CENTER);
            $impresora->text("\n____________________________________________\n");
            $impresora->setJustification(Printer::JUSTIFY_RIGHT);
            $impresora->text("Método de pago:".$venta->metodo_pago."\n");
            $impresora->setJustification(Printer::JUSTIFY_CENTER);
            $impresora->setTextSize(1, 1);
            $impresora->text("\nGracias por su compra\n");
            $impresora->feed(10);
            
            $impresora->pulse();
            $impresora->close();
        }

        return true;
    }

    function redondearAl100($numero) {
        return round($numero / 100) * 100;
    }

}
