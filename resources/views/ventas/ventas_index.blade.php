@extends("maestra")
@section("titulo", "Ventas")
@section("contenido")
    <br>
    <h1 style="width: 100%; text-align: left"><strong>Ventas ({{session('tipo_usuario')}})</strong></h1>
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div style="padding: 20px;" class="col-lg-3">
                    <div class="card_ventas" style="background-color: rgb(6, 139, 247);">
                        <div style="width: 100%">
                            <h3><strong>Total Vendido Hoy</strong></h3>
                        </div> 
                        <h2>$ {{ number_format($totalVendidoHoy, 2) }}</h2>
                        <i style="opacity: .4; font-size: 80px; position: absolute; right: 30px; bottom: 30px" class="fas fa-donate"></i>
                    </div>
                </div>
                <div style="padding: 20px;" class="col-lg-3">
                    <div class="card_ventas" style="background-color: rgb(4, 95, 1);">
                        <div style="width: 100%">
                            <h3><strong>Total Vendido Mes</strong></h3>
                        </div> 
                        <h2>$ {{ number_format($totalVendido, 2) }}</h2>
                        <i style="opacity: .4; font-size: 80px; position: absolute; right: 30px; bottom: 30px" class="fas fa-cash-register"></i>
                    </div>
                </div>
                <div class="col-lg-1"></div>
                <div style="padding: 20px;" class="col-lg-5">
                    <div class="card_ventas" style="background-color: rgb(245, 102, 6);">
                        <div style="width: 100%">
                            <h3><strong>Total Vendido</strong></h3>
                        </div> 
                        <h2>$ {{ number_format($totalVendidoTotal, 2) }}</h2>
                        <i style="opacity: .4; font-size: 80px; position: absolute; right: 30px; bottom: 30px" class="fas fa-cash-register"></i>
                    </div>
                </div>
                
            </div>
            <br>
        </div>
        <br>
        <hr>
        <div class="col-12">
            @include("notificacion")
            <div class="table-responsive">
                <table id="tabla_ventas" class="table table-bordered">
                    <thead style="background-color: #91baee">
                        <tr>
                            <th>#</th>
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
                            <td>{{$venta->id}}</td>
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
        $('#tabla_ventas').DataTable({
            dom: 'Bfrtip',
            buttons: ['excel'],
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
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: response.mensaje,
                        showConfirmButton: false,
                        timer: 2000
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire({
                        position: "center",
                        icon: "error",
                        title: "Error: "+jqXHR.responseJSON.message,
                        showConfirmButton: false,
                        timer: 5000
                    });
                }
            });
        }
    </script>
@endsection
