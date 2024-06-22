@extends("maestra")
@section("titulo", "Usuarios")
@section("contenido")
<br>
<div class="row">
    <div class="col-12">
        <div class="row" style="padding-left: 5%; padding-right: 5%">
            <div class="col-lg-5" style="display: flex; align-items: center">
                <h3><i class="fas fa-user"></i> Cliente: <span style="color: green">{{ $cliente->nombre }}</span>  </h3>
            </div>
            <div class="col-lg-4" style="display: flex; align-items: center">
                <h3 style="color: red; font-weight: bold">Deuda total:  ${{number_format($cliente->total_deuda, 2)}}</h3>
            </div>
            <div class="col-lg-3">
                <button onclick="ImprimirDeuda({{$cliente->id}})" style="font-size: 20px; width: 100%" class="btn btn-success">Imprimir deuda</button>
            </div>
        </div>
               <hr>
        @include("notificacion")
        <div class="table-responsive">
           @foreach ($facturas_deudas as $item)
            <div style="padding-left: 15%; padding-right: 15%">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 style="color: rgb(42, 54, 165); font-weight: bold; margin-bottom: 0px">Factura # {{$item->id}}</h2>
                        <h6 style="color: rgb(4, 6, 22); font-weight: bold">Fecha {{$item->fecha_venta}}</h6>
                        <h4 style="color: rgb(165, 42, 42); font-weight: bold">Total Fiado en esta factura $ {{number_format($item->total_fiado,2)}}</h4>
                    </div>
                </div>
                <br>
                <table style="width: 100%">
                    <tr style="background-color: aquamarine">
                        <th style="width: 40%">Producto</th>
                        <th style="width: 20%">Cantidad</th>
                        <th style="width: 20%">Precio</th>
                        <th style="width: 20%"> Subtotal </th>
                    </tr>
                    @foreach ($item->productos as $item2)
                        <tr>
                            <td>{{$item2->descripcion}}</td>
                            <td>{{$item2->cantidad}} {{$item2->unidad}}</td>
                            <td>$ {{number_format($item2->precio)}}</td>
                            <td>$ {{number_format(round(($item2->cantidad * $item2->precio) / 100) * 100, 2)}}</td>
                        </tr>
                    @endforeach
                    <tr style="border-top: 1px solid">
                        <th colspan="2"></th>
                        <th style="background-color: aquamarine">Subtotal</th>
                        <th style="background-color: aquamarine">$ {{ number_format($item->total_pagar, 2) }}</th>
                    </tr>
                    <tr>
                        <th colspan="2"></th>
                        <th style="background-color: aquamarine">Domicilio</th>
                        <th style="background-color: aquamarine">$ {{ number_format($item->valor_domicilio, 2) }}</th>
                    </tr>
                    <tr>
                        <th colspan="2"></th>
                        <th style="background-color: aquamarine">Total Factura</th>
                        <th style="background-color: aquamarine">$ {{ number_format($item->total_con_domi, 2) }}</th>
                    </tr>
                </table>
                <br>
                <hr>
                <br>
            </div>
           @endforeach
           <div class="row" style="width: 100%; padding-left: 15%; padding-right: 15%">
                <div class="col-lg-6">
                    <h2 style="color: rgb(250, 0, 0); font-weight: bold">&nbsp;&nbsp;Total Fiado ${{number_format($cliente->total_fiado, 2)}}</h2>
                    <h2 style="color: rgb(11, 99, 33); font-weight: bold">- Total Abonado ${{number_format($cliente->total_abonado, 2)}}</h2>
                    <hr>
                    <h2 style="color: rgb(2, 2, 2); font-weight: bold">&nbsp;&nbsp;&nbsp;Deuda Total ${{number_format($cliente->total_deuda, 2)}}</h2>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<br>
<br>
<br>
<script>
    function ImprimirDeuda(id_cliente){
        $.ajax({
            url: '/imprimir-deuda?id_cliente='+id_cliente,
            type: 'GET',
            success: function(response) {
                alert(response.mensaje);
            }
        });
    }
</script>
@endsection
