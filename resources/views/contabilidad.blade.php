@extends('maestra')
@section("titulo", "Inicio")
@section('contenido')
<div class="container">
    <br>  
    <div class="card bg-light">
        <article class="card-body mx-auto" style="width: 800px;">
            <button onclick="imprimirContabilidad()" style="position: absolute; right: 20px; font-size: 14px;" class="btn btn-warning">Imprimir <i class="fas fa-print"></i></button>
            <div class="row">
                <div style="padding: 20px;" class="col-5">
                    <h4 style="color: #045f01; font-weight: bold">Fecha de Inicio</h4>
                    <input style="font-size: 20px;" id="fecha1" type="date" class="form-control">
                </div>
                <div style="padding: 20px;" class="col-5">
                    <h4 style="color: #5f0101; font-weight: bold">Fecha Final</h4>
                    <input style="font-size: 20px;" id="fecha2" type="date" class="form-control">
                </div>
                <div  style="padding: 20px;" class="col-2">
                    <button onclick="buscarResultados()" class="btn btn-primary" style="font-size: 20px; margin-left: 0px; margin-top: 30px !important;"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <hr>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        @foreach ($contabilidad["ventas"] as $item)
                            <tr>
                                <th style="width: 70%; background-color: #297227; color: white">{{$item["tipo"]}}</th>
                                <td>$ {{number_format($item["total"], 2)}}</th>
                            </tr>
                        @endforeach
                        <tr>
                            <th style="width: 70%; background-color: #297227; color: white">Total vendido</th>
                            <td style="background-color: #297227; color: white">$ {{number_format($contabilidad["total_en_ventas"], 2)}}</th>
                        </tr>
                    </thead>
                </table>
                <br>
                <table class="table table-bordered">
                    <thead>
                        @foreach ($contabilidad["domicilios"] as $item)
                            <tr>
                                <th style="width: 70%; background-color: #56bcf7; color: black">{{$item["tipo"]}}</th>
                                <td>$ {{number_format($item["total"], 2)}}</th>
                            </tr>
                        @endforeach
                        <tr>
                            <th style="width: 70%; background-color: #56bcf7; color: black">Total domicilios</th>
                            <td style="background-color: #56bcf7; color: black">$ {{number_format($contabilidad["total_domicilios"], 2)}}</th>
                        </tr>
                    </thead>
                </table>
                <br>
                <table class="table table-bordered">
                    <thead>
                        @foreach ($contabilidad["compras"] as $item)
                            <tr>
                                <th style="width: 70%; background-color: rgb(190, 18, 18); color: white">{{$item["tipo"]}}</th>
                                <td>$ {{number_format($item["total"], 2)}}</th>
                            </tr>
                        @endforeach
                        <tr>
                            <th style="width: 70%; background-color: rgb(190, 18, 18); color: white">Total vendido</th>
                            <td style="background-color: rgb(190, 18, 18); color: white">$ {{number_format($contabilidad["total_compras"], 2)}}</th>
                        </tr>
                    </thead>
                </table>
                <br>
                <table class="table table-bordered">
                    <thead>
                        @foreach ($contabilidad["deudores"] as $item)
                            <tr>
                                <th style="width: 70%; background-color: #fac105;">{{$item["tipo"]}}</th>
                                <td>$ {{number_format($item["total"], 2)}}</th>
                            </tr>
                        @endforeach
                    </thead>
                </table>
                <br>
                <table class="table table-bordered">
                    <thead>
                        @foreach ($contabilidad["recargas_y_paquetes"] as $item)
                            <tr>
                                <th style="width: 70%; background-color: #4605fa; color: white">{{$item["tipo"]}}</th>
                                <td>$ {{number_format($item["total"], 2)}}</th>
                            </tr>
                        @endforeach
                    </thead>
                </table>
                <br>
                <table class="table table-bordered">
                    <thead>
                        @foreach ($contabilidad["consignaciones_y_retiros"] as $item)
                            <tr>
                                <th style="width: 70%; background-color: #fa05b0; color: white">{{$item["tipo"]}}</th>
                                <td>$ {{number_format($item["total"], 2)}}</th>
                            </tr>
                        @endforeach
                    </thead>
                </table>
            </div>
        </article>
    </div>
    <br><br><br><br>
    <script>
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

            location.href ="contabilidad?fecha1="+fecha1+"&fecha2="+fecha2;
        }

        function imprimirContabilidad(id_venta){
            var fecha1 = document.getElementById("fecha1").value;
            var fecha2 = document.getElementById("fecha2").value;

            $.ajax({
                url: "/imprimir-contabilidad?fecha1="+fecha1+"&fecha2="+fecha2,
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
</div> 
@endsection
