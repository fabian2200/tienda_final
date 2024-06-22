<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;

class ContabilidadController extends Controller
{
    public function index(Request $request){
        $fecha1 = $request->input("fecha1");
        $fecha2 = $request->input("fecha2");

        $ventasEfectivoTransferencia = self::ventasEfectivoTransferencia($fecha1, $fecha2);
        $deuda = self::deuda($fecha1, $fecha2);
        $compras = self::compras($fecha1, $fecha2);
        $recargasYPaquetes = self::recargasYPaquetes($fecha1, $fecha2);
        $consignacionesYRetiros = self::consignacionesYRetiros($fecha1, $fecha2);
        $domicilios = self::domicilios($fecha1, $fecha2);

        $total_en_ventas = 0;
        foreach ($ventasEfectivoTransferencia as $key) {
            $total_en_ventas += $key["total"];
        }

        $total_domicilios = 0;
        foreach ($domicilios as $key) {
            $total_domicilios += $key["total"];
        }

        $total_compras = 0;
        foreach ($compras as $key) {
            $total_compras += $key["total"];
        }

        $objeto = [
            "ventas" => $ventasEfectivoTransferencia,
            "total_en_ventas" => $total_en_ventas,
            "deudores" => $deuda,
            "compras" => $compras,
            "total_compras" => $total_compras,
            "recargas_y_paquetes" => $recargasYPaquetes,
            "consignaciones_y_retiros" => $consignacionesYRetiros,
            "domicilios" => $domicilios,
            "total_domicilios" => $total_domicilios
        ];

        //dd($objeto);
        
        return view(
            "contabilidad", 
            [
              'contabilidad' => $objeto
            ]
        );
    }

    public function ventasEfectivoTransferencia($fecha1, $fecha2){
        $ventas_efectivo = [];

        $tipos_vendedores = DB::connection('mysql')->table('tipo_usuario')
        ->where("tipo", "!=", 1)
        ->get();

        
        foreach ($tipos_vendedores as $item) {
            $ventasConTotales = DB::connection('mysql')->table('ventas')
            ->whereBetween("ventas.fecha_venta", [$fecha1, $fecha2])
            ->where("tipo_venta", $item->tipo)
            ->where("metodo_pago", "Efectivo")
            ->sum("total_pagar");

            $objeto = [
                "tipo" => "Ventas efectivo ".$item->tipo_desc,
                "total" => $ventasConTotales
            ];

            array_push($ventas_efectivo, $objeto);

            $ventasConTotales = DB::connection('mysql')->table('ventas')
            ->whereBetween("ventas.fecha_venta", [$fecha1, $fecha2])
            ->where("metodo_pago", "Transferencia")
            ->where("tipo_venta", $item->tipo)
            ->sum("total_pagar");

            $objeto = [
                "tipo" => "Ventas transferencia ".$item->tipo_desc,
                "total" => $ventasConTotales
            ];

            array_push($ventas_efectivo, $objeto);
        }
        
        return $ventas_efectivo;
    }

    public function deuda($fecha1, $fecha2){
        $fiados = [];

        $tipos_vendedores = DB::connection('mysql')->table('tipo_usuario')
        ->where("tipo", "!=", 1)
        ->get();

        $deuda_total = 0;
       
        foreach ($tipos_vendedores as $item) {
            $ventasConTotales = DB::connection('mysql')->table('fiados')
            ->join("ventas", "ventas.id", "fiados.id_factura")
            ->whereBetween("ventas.fecha_venta", [$fecha1, $fecha2])
            ->where("ventas.tipo_venta", $item->tipo)
            ->where("ventas.metodo_pago", "Efectivo")
            ->sum("ventas.total_fiado");

            $objeto = [
                "tipo" => "Total fiado ".$item->tipo_desc,
                "total" => $ventasConTotales
            ];
            
            $deuda_total += $ventasConTotales;
            array_push($fiados, $objeto);
        }
        
        $abonos = DB::connection('mysql')->table('abonos_fiados')
        ->whereBetween("abonos_fiados.fecha", [$fecha1, $fecha2])
        ->sum("abonos_fiados.valor_abonado");

        $objeto = [
            "tipo" => 'Abonos totales',
            "total" => $abonos
        ];
       
        array_push($fiados, $objeto);

        return $fiados;
    }

    public function compras($fecha1, $fecha2){
        $compras = [];

        $tipos_vendedores = DB::connection('mysql')->table('tipo_usuario')
        ->where("tipo", "!=", 1)
        ->get();
        
        foreach ($tipos_vendedores as $item) {
            $compras_total = DB::connection('mysql')->table('compras')
            ->where("compras.tipo_compra", $item->tipo)
            ->whereBetween("compras.fecha_b", [$fecha1, $fecha2])
            ->sum("compras.total");

            $objeto = [
                "tipo" => "Total compras ".$item->tipo_desc,
                "total" => $compras_total
            ];
            
            array_push($compras, $objeto);
        }
       
        return $compras;
    }

    public function recargasYPaquetes($fecha1, $fecha2){
        $recargas = DB::connection('mysql')->table('recargas')
        ->whereBetween("recargas.fecha", [$fecha1, $fecha2])
        ->where("recargas.tipo", "Recarga")
        ->sum("recargas.monto");

        $paquetes = DB::connection('mysql')->table('recargas')
        ->whereBetween("recargas.fecha", [$fecha1, $fecha2])
        ->where("recargas.tipo", "Paquete")
        ->sum("recargas.monto");

        $objeto = [
            "tipo" => "Total de recargas",
            "total" => $recargas
        ];

        $objeto2 = [
            "tipo" => "Total de paquetes",
            "total" => $paquetes
        ];

        $objeto3 = [
            "tipo" => "Total",
            "total" => $recargas + $paquetes
        ];

        return [$objeto, $objeto2, $objeto3];
    }

    public function consignacionesYRetiros($fecha1, $fecha2){
        $cosnignaciones = DB::connection('mysql')->table('consignacion_retiro')
        ->whereBetween("consignacion_retiro.fecha", [$fecha1, $fecha2])
        ->where("consignacion_retiro.tipo", "Consignacion")
        ->sum("consignacion_retiro.monto");

        $retiros = DB::connection('mysql')->table('consignacion_retiro')
        ->whereBetween("consignacion_retiro.fecha", [$fecha1, $fecha2])
        ->where("consignacion_retiro.tipo", "Retiro")
        ->sum("consignacion_retiro.monto");

        $objeto = [
            "tipo" => "Total en consignaciones",
            "total" => $cosnignaciones
        ];

        $objeto2 = [
            "tipo" => "Total en retiros",
            "total" => $retiros
        ];

        return [$objeto, $objeto2];
    }

    public function domicilios($fecha1, $fecha2){
        $domicilios = [];

        $tipos_vendedores = DB::connection('mysql')->table('tipo_usuario')
        ->where("tipo", "!=", 1)
        ->get();
        
        foreach ($tipos_vendedores as $item) {
            $domi = DB::connection('mysql')->table('ventas')
            ->whereBetween("ventas.fecha_venta", [$fecha1, $fecha2])
            ->where("tipo_venta", $item->tipo)
            ->sum("valor_domicilio");

            $objeto = [
                "tipo" => "Domicilios de ".$item->tipo_desc,
                "total" => $domi
            ];

            array_push($domicilios, $objeto);
        }
        
        return $domicilios;
    }

    public function imprimirContabilidad(Request $request){
        $fecha1 = $request->input("fecha1");
        $fecha2 = $request->input("fecha2");

        $ventasEfectivoTransferencia = self::ventasEfectivoTransferencia($fecha1, $fecha2);
        $deuda = self::deuda($fecha1, $fecha2);
        $compras = self::compras($fecha1, $fecha2);
        $recargasYPaquetes = self::recargasYPaquetes($fecha1, $fecha2);
        $consignacionesYRetiros = self::consignacionesYRetiros($fecha1, $fecha2);
        $domicilios = self::domicilios($fecha1, $fecha2);

        $total_en_ventas = 0;
        foreach ($ventasEfectivoTransferencia as $key) {
            $total_en_ventas += $key["total"];
        }

        $total_domicilios = 0;
        foreach ($domicilios as $key) {
            $total_domicilios += $key["total"];
        }

        $total_compras = 0;
        foreach ($compras as $key) {
            $total_compras += $key["total"];
        }

        $negocio = DB::connection('mysql')->table('negocio')->first();
        $usuario = DB::connection('mysql')->table('users')->where('id', session('user_id'))->first();
        $ipImpresora = $usuario->ip_impresora;
        $puertoImpresora = env("PUERTO_IMPRESORA");

        $connector = new NetworkPrintConnector($ipImpresora, $puertoImpresora);
        $impresora = new Printer($connector);
        $impresora->setJustification(Printer::JUSTIFY_CENTER);
        $impresora->setEmphasis(true);
        $impresora->text("Ticket de contabilidad\n");
        $impresora->text(date("d/m/Y H:i:s") . "\n");
        $impresora->text($negocio->nombre."\n");
        $impresora->text("NIT ".$negocio->nit."\n");
        $impresora->text($negocio->direccion."\n");
        $impresora->text("Barrio ".$negocio->barrio."\n");
        $impresora->text("Cel: ".$negocio->telefono."\n");
        $impresora->setEmphasis(false);
        $impresora->text("\nDetalles de contabilidad\n");

        $impresora->setJustification(Printer::JUSTIFY_LEFT);
        $impresora->text("\n_____________________________________________\n\n");
        $impresora->text("Fecha Inicio: ".$fecha1."\n");
        $impresora->text("Fecha Finalización: ".$fecha2."\n");
        $impresora->text("_____________________________________________\n");
        $impresora->setTextSize(1, 1);
        foreach ($ventasEfectivoTransferencia as $key) {
            $tex_tipo = sprintf("%s", $key["tipo"]);
            $precio_text = '$' . number_format($key["total"], 2);
            $line_length = 48;
            $combined_text = $tex_tipo . str_repeat(' ', $line_length - strlen($tex_tipo) - strlen($precio_text)) . $precio_text;
            $impresora->text($combined_text . "\n");
        }

        $tex_tipo = sprintf("Total en ventas");
        $precio_text = '$' . number_format($total_en_ventas, 2);
        $line_length = 48;
        $combined_text = $tex_tipo . str_repeat(' ', $line_length - strlen($tex_tipo) - strlen($precio_text)) . $precio_text;
        $impresora->text("\n". $combined_text . "\n");

        $impresora->text("_____________________________________________\n");

        foreach ($domicilios as $key) {
            $tex_tipo = sprintf("%s", $key["tipo"]);
            $precio_text = '$' . number_format($key["total"], 2);
            $line_length = 48;
            $combined_text = $tex_tipo . str_repeat(' ', $line_length - strlen($tex_tipo) - strlen($precio_text)) . $precio_text;
            $impresora->text($combined_text . "\n");
        }

        $tex_tipo = sprintf("Total en domicilios");
        $precio_text = '$' . number_format($total_domicilios, 2);
        $line_length = 48;
        $combined_text = $tex_tipo . str_repeat(' ', $line_length - strlen($tex_tipo) - strlen($precio_text)) . $precio_text;
        $impresora->text("\n". $combined_text . "\n");

        $impresora->text("_____________________________________________\n");

        foreach ($compras as $key) {
            $tex_tipo = sprintf("%s", $key["tipo"]);
            $precio_text = '$' . number_format($key["total"], 2);
            $line_length = 48;
            $combined_text = $tex_tipo . str_repeat(' ', $line_length - strlen($tex_tipo) - strlen($precio_text)) . $precio_text;
            $impresora->text($combined_text . "\n");
        }

        $tex_tipo = sprintf("Total en compras");
        $precio_text = '$' . number_format($total_compras, 2);
        $line_length = 48;
        $combined_text = $tex_tipo . str_repeat(' ', $line_length - strlen($tex_tipo) - strlen($precio_text)) . $precio_text;
        $impresora->text("\n". $combined_text . "\n");

        $impresora->text("_____________________________________________\n");

        foreach ($deuda as $key) {
            $tex_tipo = sprintf("%s", $key["tipo"]);
            $precio_text = '$' . number_format($key["total"], 2);
            $line_length = 48;
            $combined_text = $tex_tipo . str_repeat(' ', $line_length - strlen($tex_tipo) - strlen($precio_text)) . $precio_text;
            $impresora->text($combined_text . "\n");
        }

        $impresora->text("_____________________________________________\n");

        foreach ($recargasYPaquetes as $key) {
            $tex_tipo = sprintf("%s", $key["tipo"]);
            $precio_text = '$' . number_format($key["total"], 2);
            $line_length = 48;
            $combined_text = $tex_tipo . str_repeat(' ', $line_length - strlen($tex_tipo) - strlen($precio_text)) . $precio_text;
            $impresora->text($combined_text . "\n");
        }

        $impresora->text("_____________________________________________\n");

        foreach ($consignacionesYRetiros as $key) {
            $tex_tipo = sprintf("%s", $key["tipo"]);
            $precio_text = '$' . number_format($key["total"], 2);
            $line_length = 48;
            $combined_text = $tex_tipo . str_repeat(' ', $line_length - strlen($tex_tipo) - strlen($precio_text)) . $precio_text;
            $impresora->text($combined_text . "\n");
        }
        $impresora->setTextSize(1, 1);
        $impresora->feed(10);
        $impresora->close();
    }
}
