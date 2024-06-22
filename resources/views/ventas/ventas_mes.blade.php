@extends("maestra")
@section("titulo", "Ventas")
@section("contenido")
<br>
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div style="padding: 20px;" class="col-lg-3">
                    <h2 style="color: #045f01; font-weight: bold">Fecha de Inicio</h2>
                   <input style="font-size: 36px;" id="fecha1" type="date" class="form-control">
                </div>
                <div style="padding: 20px;" class="col-lg-4">
                    <h2 style="color: #5f0101; font-weight: bold">Fecha Final</h2>
                    <div style="display: flex">
                        <input style="font-size: 36px;" id="fecha2" type="date" class="form-control">
                        <button onclick="buscarResultados()" class="btn btn-primary" style="font-size: 35px; margin-left: 20px;"><i class="fas fa-search"></i></button>
                    </div>
                </div>
                <div style="padding: 20px;" class="col-lg-1">
                </div>
                <div style="padding: 20px;" class="col-lg-4">
                    <div class="card_ventas" style="background-color: rgb(4, 95, 1);">
                        <div style="width: 100%">
                            <h3><strong>Total Vendido</strong></h3>
                        </div> 
                        <h1>$ {{ number_format($totalVendido, 2) }}</h1>
                        <i style="opacity: .7; font-size: 70px; position: absolute; right: 30px; bottom: 30px" class="fas fa-cash-register"></i>
                    </div>
                </div>
            </div>
            <br>
        </div>
        <br>
        <hr>
        <h2 style="width: 100%; text-align: center"><strong>LISTADO DE VENTAS PERIODO</strong></h2>
        <div class="col-12">
            @include("notificacion")
            <div class="table-responsive">
                <table id="tabla_ventas_2" class="table table-bordered">
                    <thead style="background-color: #91baee">
                        <tr>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>SubTotal</th>
                            <th>Domicilio</th>
                            <th>Total</th>
                            <th style="text-align: center">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($ventas as $venta)
                        <tr>
                            <td>{{$venta->created_at}}</td>
                            <td>{{$venta->cliente}}</td>
                            <td>${{number_format($venta->total_pagar, 2)}}</td>
                            <td>${{number_format($venta->valor_domicilio, 2)}}</td>
                            <td>${{number_format($venta->total_con_domi, 2)}}</td>
                            <td style="display: flex; justify-content: space-evenly; align-items: center;">
                                <a type="button" class="btn btn-info"  onclick="ImprimirTicket({{$venta->id}})">
                                    <i class="fa fa-print"></i>
                                </a>
                            
                                <a class="btn btn-success" href="{{route("ventas.show", $venta)}}">
                                    <i class="fa fa-info"></i>
                                </a>
                            
                                <form action="{{route("ventas.destroy", [$venta])}}" method="post">
                                    @method("delete")
                                    @csrf
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <br><br><br>
            </div>
        </div>
    </div>
    <script>        
        $('#tabla_ventas_2').DataTable({
            "pageLength": 100,
            language: {
                "decimal": "",
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Ventas",
                "infoEmpty": "Mostrando 0 to 0 of 0 Ventas",
                "infoFiltered": "(Filtrado de _MAX_ total Ventas)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Ventas",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            ordering: false
        });

        function ImprimirTicket(id_venta){
            $.ajax({
                url: '/imprimir-ticket?id_venta='+id_venta,
                type: 'GET',
                success: function(response) {
                    alert(response.mensaje);
                }
            });
        }

        $(document).ready(function() {
            var fechaActual = new Date();
            var primerDiaMes = new Date(fechaActual.getFullYear(), fechaActual.getMonth(), 1);
            
            var urlParams = new URLSearchParams(window.location.search);

            var fecha1Param = urlParams.get('fecha1');
            
            var fecha2Param = urlParams.get('fecha2');

            $('#fecha1').val(fecha1Param);
            $('#fecha2').val(fecha2Param);
            
        });

        function buscarResultados(){
            var fecha1 = document.getElementById("fecha1").value;
            var fecha2 = document.getElementById("fecha2").value;

            location.href ="venta-por-fecha?fecha1="+fecha1+"&fecha2="+fecha2;
        }
    </script>
@endsection
