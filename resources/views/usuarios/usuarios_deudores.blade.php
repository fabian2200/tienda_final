@extends("maestra")
@section("titulo", "Usuarios")
@section("contenido")
<br>
<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-lg-7">
                <h1>Clientes Deudores  <i class="fas fa-hand-holding-usd"></i></h1>
            </div>
            <div class="col-lg-5">
                <h1 style="color: red; font-weight: bold">Deuda total:  ${{number_format($total_fiado, 2)}}</h1>
            </div>
        </div>
               <hr>
        @include("notificacion")
        <div class="table-responsive">
            <table id="tabla_deudores" class="table table-bordered">
                <thead style="background-color: #91baee">
                    <tr>
                        <th>Cliente</th>
                        <th>Total Fiado</th>
                        <th>Total Abonado</th>
                        <th>Deuda Total</th>
                        <th style="text-align: center">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($clientes_deudores as $cliente)
                    <tr>
                        <td>{{$cliente->nombre}}</td>
                        <td>${{number_format($cliente->total_fiado, 2)}}</td>
                        <td>${{number_format($cliente->total_abonado, 2)}}</td>
                        <td>${{number_format($cliente->total_deuda, 2)}}</td>
                        <td style="display: flex; justify-content: space-evenly; align-items: center;">
                            <button onclick="seleccionarCliente({{$cliente->id}}, '{{$cliente->nombre}}', {{$cliente->total_deuda}})" data-toggle="modal" data-target="#modalAbonar" class="btn btn-success">
                                Abonar <i class="fas fa-hand-holding-usd"></i>
                            </button>
                            <a href="/info-deuda?id_cliente={{$cliente->id}}" class="btn btn-warning">
                                Información <i class="fa fa-info"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<br>
<br>
<br>
<br>

<div class="modal fade" id="modalAbonar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title" id="exampleModalLabel">Abonar a deuda</h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form style="font-size: 16px !important" method="POST" action="{{route("usuarios.abonar")}}">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="cliente">Cliente</label>
                    <input type="hidden"  id="id_cliente" name="id_cliente" class="form-control" placeholder="Total a abonar">
                    <input type="text" disabled id="cliente" name="cliente" class="form-control" placeholder="Total a abonar">
                </div>
                <div class="form-group">
                    <label for="total_abonar">Total de la deuda</label>
                    <input type="number" disabled id="total_deuda" name="total_deuda" class="form-control" placeholder="Total a abonar">
                </div>
                <div class="form-group">
                    <label for="total_abonar">Total del abono</label>
                    <input required oninput="calcularDeudaRestante(this)" type="number" id="total_abonar" name="total_abonar" class="form-control" placeholder="Total a abonar">
                </div>
                <div class="form-group">
                    <label for="total_deuda_restante">Total deuda restante</label>
                    <input type="number" disabled id="total_deuda_restante" name="total_deuda_restante" class="form-control" placeholder="Total a abonar">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Abonar</button>
                <a style="color: white" class="btn btn-danger" data-dismiss="modal">Cerrar</a>
            </div>
        </form>
      </div>
    </div>
  </div>

<script>
    $('#tabla_deudores').DataTable({
        "order": [[3, 'desc']],
        language: {
            "decimal": "",
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Clientes",
            "infoEmpty": "Mostrando 0 to 0 of 0 Clientes",
            "infoFiltered": "(Filtrado de _MAX_ total Clientes)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ Clientes",
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
        }
    });

    valor_deuda = 0;

    function seleccionarCliente(id_cliente, cliente, total_deuda){
        valor_deuda = total_deuda;
        setTimeout(() => {
            document.getElementById("id_cliente").value = id_cliente;
            document.getElementById("cliente").value = cliente;
            document.getElementById("total_deuda").value = total_deuda;
        }, 500);
    }

    function calcularDeudaRestante(element){
        var deuda_restante = valor_deuda - element.value;
        document.getElementById("total_deuda_restante").value = deuda_restante;
    }
</script>
@endsection
