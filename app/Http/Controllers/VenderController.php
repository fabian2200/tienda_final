<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Producto;
use App\ProductoVendido;
use App\Venta;
use Illuminate\Http\Request;
use App\Http\Controllers\VentasController;
use DB;
use App\Http\Controllers\DomiciliosController;

class VenderController extends Controller{
    public function terminarOCancelarVenta(Request $request){
        if ($request->input("accion") == "terminar") {
            return $this->terminarVenta($request);
        } else {
            return $this->cancelarVenta();
        }
    }

    public function terminarVenta(Request $request){
        $tipo_venta = session('user_tipo');

        if($tipo_venta != 1){
            $venta = new Venta();
            $venta->id_cliente =  $request->input('id_cliente');
            $venta->total_pagar =  $request->input('total_pagar');
            $venta->total_dinero =  $request->input('total_dinero');
            $venta->total_fiado =  $request->input('total_fiado');
            $venta->total_vueltos =  $request->input('total_vueltos');
            $imprimir_factura = $request->input("imprimir_factura");
            $venta->fecha_venta = date("Y-m-d");
            $venta->id_vendedor = session('user_id');
            $venta->tipo_venta = session('user_tipo');
            $venta->total_con_domi = (double) $request->input("total_pagar_con_domi");
            $venta->valor_domicilio = $venta->total_con_domi - $venta->total_pagar;
            $venta->metodo_pago = $request->input("metodo_pago");
            $venta->saveOrFail();

            if($venta->total_fiado > 0){
                $this->guardarFiado($venta->id_cliente, $venta->id,  $venta->total_fiado);
            }

            $idVenta = $venta->id;
            $productos = $this->obtenerProductos();

            $lista_productos = [];
            foreach ($productos as $producto) {
                $productoVendido = new ProductoVendido();
                $productoVendido->fill([
                    "id_venta" => $idVenta,
                    "descripcion" => $producto->descripcion,
                    "codigo_barras" => $producto->codigo_barras,
                    "precio" => $producto->precio_venta,
                    "cantidad" => $producto->cantidad,
                    "unidad" => $producto->unidad_medida== "Kilos" ? "Kg" : ($producto->unidad_medida == "Libras" ? "Lb" : "Und")
                ]);
                $productoVendido->saveOrFail();

                $productoActualizado = Producto::find($producto->id);
                $productoActualizado->existencia -= $productoVendido->cantidad;
                $productoActualizado->saveOrFail();


                $lista_productos[] = [
                    "codigo_barras" => $productoActualizado->codigo_barras,
                    "existencia" => $productoActualizado->existencia
                ];
            }

            $objeto = new VentasController();
            $myVariable = $objeto->ticket($idVenta, $imprimir_factura);

            //$actualizar = new DomiciliosController();
            //$actualizar->actualizarCantidadesProductos($lista_productos, null);
            
            $this->vaciarProductos();

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

    public function guardarFiado($id_cliente, $id_factura, $total_fiado){
        $datos = [
            'id_cliente' => $id_cliente,
            'id_factura' => $id_factura,
            'total_fiado' => $total_fiado,
            'fecha' => date('Y-m-d')
        ];

        DB::connection('mysql')->table('fiados')->insert(
            $datos 
        );
    }

    private function obtenerProductos(){
        $productos = session("productos");
        if (!$productos) {
            $productos = [];
        }
        return $productos;
    }

    private function vaciarProductos(){
        $this->guardarProductos(null);
    }

    private function guardarProductos($productos){
        session(["productos" => $productos]);
    }

    public function cancelarVenta(){
        $this->vaciarProductos();

        return response()->json([
            'status' => 'success',
            'message' => 'Venta cancelada',
        ]);
    }

    public function quitarProductoDeVenta(Request $request){
        $indice = $request->post("indice");
        $productos = $this->obtenerProductos();
        array_splice($productos, $indice, 1);
        $this->guardarProductos($productos);
        if(count($productos) == 0){
            $this->vaciarProductos();
        }

        return redirect()->route("vender.index");
    }

    public function agregarProductoVenta(Request $request){        
        $codigo = $request->post("codigo");
        $cantidad = $request->post("cantidad");
        $producto = Producto::where("codigo_barras", "=", $codigo)->first();
        if (!$producto) {
            return response()->json([
                'status' => 'error',
                'message' => 'Producto no encontrado',
            ]);
        }
        
        $respuesta = $this->agregarProductoACarrito($producto, $cantidad);

        return $respuesta;
    }

    private function agregarProductoACarrito($producto, $cantidad){
        if ($producto->existencia <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'No hay existencias del producto',
            ]);
        }else{

            $productos = $this->obtenerProductos();
            $posibleIndice = $this->buscarIndiceDeProducto($producto->codigo_barras, $productos);
    
            if ($posibleIndice === -1) {
                if ($cantidad <= $producto->existencia) {
                    $producto->cantidad = $cantidad;
                    $producto->precio_total = self::redondearAl100($producto->cantidad * $producto->precio_venta);
                    array_unshift($productos, $producto);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'No se pueden agregar más productos de este tipo, se quedarían sin existencia',
                    ]);
                }
            } else {
                if ($productos[$posibleIndice]->cantidad + $cantidad > $producto->existencia) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'No se pueden agregar más productos de este tipo, se quedarían sin existencia',
                    ]);
                }
                $productos[$posibleIndice]->cantidad += $cantidad;
                $productos[$posibleIndice]->cantidad = round($productos[$posibleIndice]->cantidad, 3);
                $productos[$posibleIndice]->precio_total =  self::redondearAl100($productos[$posibleIndice]->cantidad * $productos[$posibleIndice]->precio_venta);
            }
            $this->guardarProductos($productos);

            return response()->json([
                'status' => 'success',
                'message' => 'Producto agregado a la venta',
            ]);
        }

    }

    function redondearAl100($numero) {
        return round($numero / 100) * 100;
    }

    private function buscarIndiceDeProducto(string $codigo, array &$productos){
        foreach ($productos as $indice => $producto) {
            if ($producto->codigo_barras === $codigo) {
                return $indice;
            }
        }
        return -1;
    }

    public function actualizarProductoDeVenta(Request $request){
        $codigo = $request->post("codigo");
        $indice = $request->post("indice");
        $cantidad = $request->post("cantidad");
        
        $productos = $this->obtenerProductos();
        $producto = Producto::where("codigo_barras", "=", $codigo)->first();

        if ($cantidad > $producto->existencia) {
            return response()->json([
                    "message" => "No se pueden agregar más productos de este tipo, se quedarían sin existencia",
                    "status" => "error"
                ]);
        }

        $producto_editar = $productos[$indice];
        $producto_editar->cantidad = $cantidad;
        $producto_editar->precio_total = self::redondearAl100($producto_editar->cantidad * $producto_editar->precio_venta);
        $productos[$indice] = $producto_editar;

        $this->guardarProductos($productos);

        return response()->json([
            "message" => "Cantidad Actualizada correctamente",
            "status" => "success"
        ]);
    }


    public function index(){
        return view("vender.vender",[
            "clientes" => Cliente::orderBy('nombre', 'asc')->get(),
        ]);
    }

    public function obtenerProductosCarritoJson(){
        $total = 0;
        foreach ($this->obtenerProductos() as $producto) {
            $total += self::redondearAl100($producto->cantidad * $producto->precio_venta);
        }

        return response()->json([
            'total' => $total,
            'productosCarrito' => $this->obtenerProductos() ,
        ]);
    }
}
