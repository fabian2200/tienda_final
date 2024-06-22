<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Cliente;
use DB;
use App\Venta;

use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("usuarios.usuarios_index", ["usuarios" => User::all()]);
    }

    public function deudores(){
        $resultado = Cliente::join("fiados", "clientes.id", "=", "fiados.id_cliente")
        ->join("ventas", "ventas.id", "fiados.id_factura")
        ->selectRaw("clientes.*, SUM(fiados.total_fiado) as total_fiado")
        ->groupBy('clientes.id')
        ->get();

        $clientes_deben = [];
        $total_fiado = 0;

        foreach ($resultado as $item) {
            $abonado = Cliente::join("abonos_fiados", "clientes.id", "=", "abonos_fiados.id_cliente")
            ->selectRaw("clientes.id, SUM(abonos_fiados.valor_abonado) as total_abonado")
            ->where("clientes.id", $item->id)
            ->groupBy('clientes.id')
            ->get();

            if(count($abonado) == 0){
                $total_abonado = 0;
            }else{
                $total_abonado = $abonado[0]->total_abonado;
            }

            $item->total_abonado = $total_abonado;
            $item->total_deuda = $item->total_fiado - $item->total_abonado;

            
            if($item->total_deuda > 0){
                $total_fiado += $item->total_deuda;
                array_push($clientes_deben, $item);
            }
        }

        return view("usuarios.usuarios_deudores", ["clientes_deudores" => $clientes_deben, "total_fiado" => $total_fiado]);
    }

    public function abonar(Request $request){
        $id_cliente = $request->input('id_cliente');
        $total_abonar = $request->input('total_abonar');

        $datos = [
            'id_cliente' => $id_cliente,
            'valor_abonado' => $total_abonar,
            'fecha_abono' => date("d-m-Y H:i:s"),
            'fecha' => date("Y-m-d")
        ];

        DB::connection('mysql')->table('abonos_fiados')->insert(
            $datos 
        );

        
        $fiado = Cliente::join("fiados", "clientes.id", "=", "fiados.id_cliente")
        ->join("ventas", "ventas.id", "fiados.id_factura")
        ->selectRaw("clientes.*, SUM(fiados.total_fiado) as total_fiado")
        ->groupBy('clientes.id')
        ->where("clientes.id", $id_cliente)
        ->first();

        $abonado = Cliente::join("abonos_fiados", "clientes.id", "=", "abonos_fiados.id_cliente")
        ->selectRaw("clientes.id, SUM(abonos_fiados.valor_abonado) as total_abonado")
        ->where("clientes.id", $id_cliente)
        ->groupBy('clientes.id')
        ->get();

        if(count($abonado) == 0){
            $total_abonado = 0;
        }else{
            $total_abonado = $abonado[0]->total_abonado;
        }


        $total_deuda = $fiado->total_fiado - $total_abonado;
        
        if($total_deuda < 1){
            DB::connection('mysql')->table('ventas')
            ->where("id_cliente", $id_cliente)
            ->update([
                'total_fiado' => 0,
                'total_dinero' => DB::raw('total_pagar'),
            ]);

            DB::connection('mysql')->table('fiados')
            ->where("id_cliente", $id_cliente)
            ->delete();

            DB::connection('mysql')->table('abonos_fiados')
            ->where("id_cliente", $id_cliente)
            ->delete();
        }
        
        return redirect('/usuarios-deudores');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $tipos_user = DB::connection('mysql')->table('tipo_usuario')
        ->where("tipo", "!=", 1)
        ->get();

        $impresoras = DB::connection('mysql')->table('impresoras')
        ->get();

        return view("usuarios.usuarios_create", ["tipos" => $tipos_user, "impresoras" => $impresoras]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $usuario = new User($request->input());
        $usuario->password = Hash::make($usuario->password);
        $usuario->saveOrFail();
        return redirect()->route("usuarios.index")->with("mensaje", "Usuario guardado");
    }

    /**
     * Display the specified resource.
     *
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user){
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user){
        $tipos_user = DB::connection('mysql')->table('tipo_usuario')
        ->where("tipo", "!=", 1)
        ->get();

        $impresoras = DB::connection('mysql')->table('impresoras')
        ->get();

        $user->password = "";
        return view("usuarios.usuarios_edit", ["usuario" => $user, "tipos" => $tipos_user, "impresoras" => $impresoras]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user){
        $user->fill($request->input());
        $user->password = Hash::make($user->password);
        $user->saveOrFail();
        return redirect()->route("usuarios.index")->with("mensaje", "Usuario actualizado");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user){
        $user->delete();
        return redirect()->route("usuarios.index")->with("mensaje", "Usuario eliminado");
    }

    public function infoDeuda(Request $request){
        $id = $request->input("id_cliente");

        $resultado = Cliente::join("fiados", "clientes.id", "=", "fiados.id_cliente")
        ->join("ventas", "ventas.id", "fiados.id_factura")
        ->selectRaw("clientes.*, SUM(fiados.total_fiado) as total_fiado")
        ->groupBy('clientes.id')
        ->where("clientes.id", $id)
        ->first();

       
        $abonado = Cliente::join("abonos_fiados", "clientes.id", "=", "abonos_fiados.id_cliente")
        ->selectRaw("clientes.id, SUM(abonos_fiados.valor_abonado) as total_abonado")
        ->where("clientes.id", $id)
        ->groupBy('clientes.id')
        ->get();

        if(count($abonado) == 0){
            $total_abonado = 0;
        }else{
            $total_abonado = $abonado[0]->total_abonado;
        }

        $resultado->total_abonado = $total_abonado;
        $resultado->total_deuda = $resultado->total_fiado - $resultado->total_abonado;


        $facturas_deudas =  DB::connection('mysql')->table('ventas')
        ->where("id_cliente", $id)
        ->where("total_fiado", ">", 0)
        ->get();

        foreach ($facturas_deudas as $item) {
            $item->productos = DB::connection('mysql')->table('productos_vendidos')
            ->where("id_venta", $item->id)
            ->get();
        }

        return view("usuarios.info_deuda", ["cliente" => $resultado, "facturas_deudas" => $facturas_deudas]);
    }

    public function ImprimirDeuda(Request $request){

        $id_cliente = $request->input("id_cliente");

        $resultado = Cliente::join("fiados", "clientes.id", "=", "fiados.id_cliente")
        ->selectRaw("clientes.*, SUM(fiados.total_fiado) as total_fiado")
        ->groupBy('clientes.id')
        ->where("clientes.id", $id_cliente)
        ->first();

       
        $abonado = Cliente::join("abonos_fiados", "clientes.id", "=", "abonos_fiados.id_cliente")
        ->selectRaw("clientes.id, SUM(abonos_fiados.valor_abonado) as total_abonado")
        ->where("clientes.id", $id_cliente)
        ->groupBy('clientes.id')
        ->get();

        $total_deuda = 0;
        if(count($abonado) == 0){
            $total_abonado = 0;
        }else{
            $total_abonado = $abonado[0]->total_abonado;
        }

        $total_deuda = $resultado->total_fiado - $total_abonado;

        $facturas_deudas =  DB::connection('mysql')->table('ventas')
        ->where("id_cliente", $id_cliente)
        ->where("total_fiado", ">", 0)
        ->get();

        foreach ($facturas_deudas as $item) {
            $item->productos = DB::connection('mysql')->table('productos_vendidos')
            ->where("id_venta", $item->id)
            ->get();
        }

        $negocio = DB::connection('mysql')->table('negocio')->first();
        $usuario = DB::connection('mysql')->table('users')->where('id', session('user_id'))->first();
        $ipImpresora = $usuario->ip_impresora;
        $puertoImpresora = env("PUERTO_IMPRESORA");

        $connector = new NetworkPrintConnector($ipImpresora, $puertoImpresora);
        $impresora = new Printer($connector);
        $impresora->setJustification(Printer::JUSTIFY_CENTER);
        $impresora->setEmphasis(true);
        $impresora->setTextSize(2, 2);
        $impresora->text($negocio->nombre."\n\n\n");
        $impresora->setTextSize(1, 1);
        $impresora->text("Ticket de Deuda\n");
        $impresora->text("NIT ".$negocio->nit."\n");
        $impresora->text($negocio->direccion."\n");
        $impresora->text("Barrio ".$negocio->barrio."\n");
        $impresora->text("Cel: ".$negocio->telefono."\n");
        $impresora->setEmphasis(false);
        $impresora->text("\nCliente: ".$resultado->nombre ."\n");
        $impresora->text("\nDetalle de la deuda\n");
        foreach ($facturas_deudas as $venta) {
            $impresora->setJustification(Printer::JUSTIFY_LEFT);
            $impresora->text("____________________________________________\n");
            $impresora->text("Factura #".$venta->id."\n");
            $impresora->text("Fecha Factura #".$venta->fecha_venta."\n");
            $impresora->text("SubTotal Factura:  $" . number_format(self::redondearAl100($venta->total_pagar), 2) . "\n");
            $impresora->text("Domicilio:         $" . number_format(self::redondearAl100($venta->valor_domicilio), 2) . "\n");
            $impresora->text("Total Factura:     $" . number_format(self::redondearAl100($venta->total_con_domi), 2) . "\n");
            $impresora->text("Total Pagado:      $" . number_format(self::redondearAl100($venta->total_dinero), 2) . "\n");
            $impresora->text("Total fiado:       $" . number_format(self::redondearAl100($venta->total_fiado), 2) . "\n");
        }
        $impresora->setJustification(Printer::JUSTIFY_CENTER);
        $impresora->text("\n_______________ Deuda Total _______________\n");
        $impresora->setJustification(Printer::JUSTIFY_LEFT);
        $impresora->setTextSize(1, 1);
        $impresora->text("Total fiado:     $". number_format($resultado->total_fiado, 2). "\n");
        $impresora->text("Total Abonado:   $". number_format($total_abonado, 2). "\n");
        $impresora->text("Deuda Restante:  $". number_format($total_deuda, 2). "\n");
        $impresora->setJustification(Printer::JUSTIFY_CENTER);
        $impresora->setTextSize(1, 1);
        $impresora->feed(10);
        $impresora->close();
        return response()->json(["mensaje" => "Ticket de deuda impreso correctamente!"]);
    }

    function redondearAl100($numero) {
        return round($numero / 100) * 100;
    }
}