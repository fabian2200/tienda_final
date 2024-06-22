@extends("maestra")
@section("titulo", "Compras")
@section("contenido")
<br>
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-6" style="display: flex; align-items:center">
                    <button data-toggle="modal" data-target="#modalCompra" class="btn btn-warning">
                        <h1>Agregar Compra <i style="font-size: 40px" class="fas fa-plus"></i></h1>
                    </button>
                </div>
                <div style="padding: 20px;" class="col-lg-3">
                    <div class="card_ventas" style="background-color: rgb(6, 139, 247);">
                        <div style="width: 100%">
                            <h4><strong>Total Comprado Hoy</strong></h4>
                        </div> 
                        <h1>$ {{ number_format($totalCompradoHoy, 2) }}</h1>
                        <i style="opacity: .5; font-size: 50px; position: absolute; right: 30px; bottom: 30px" class="fas fa-donate"></i>
                    </div>
                </div>
                <div style="padding: 20px;" class="col-lg-3">
                    <div class="card_ventas" style="background-color: rgb(4, 95, 1);">
                        <div style="width: 100%">
                            <h4><strong>Total Compras</strong></h4>
                        </div> 
                        <h1>$ {{ number_format($totalComprado, 2) }}</h1>
                        <i style="opacity: .5; font-size: 50px; position: absolute; right: 30px; bottom: 30px" class="fas fa-cash-register"></i>
                    </div>
                </div>
            </div>
            <br>
        </div>
        <br>
        <hr>
        <h3 style="width: 100%; text-align: center"><strong>Listado de compras - <strong style="color: red">{{session('tipo_usuario')}}</strong></strong></h3>
        <div class="col-12">
            @include("notificacion")
            <div class="table-responsive">
                <table id="tabla_compras" class="table table-bordered">
                    <thead style="background-color: #91baee">
                        <tr>
                            <th>Fecha de compra</th>
                            <th>Proveedor</th>
                            <th>Total</th>
                            <th>Borrar</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($compras as $venta)
                        <tr>
                            <td>{{$venta->fecha}}</td>
                            <td>{{$venta->nombre}}</td>
                            <td>${{number_format($venta->total, 2)}}</td>
                            <td style="width: 100px; text-align: center">
                                <form action="{{route("compras.eliminar")}}" method="post">
                                    @csrf
                                    <input type="hidden" name="id_compra" value="{{ $venta->id }}">
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

    <div class="modal" id="modalCompra" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h2 class="modal-title">Confirmar Compra - <strong style="color: red">{{session('tipo_usuario')}}</strong></h2>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form action="{{route("compras.guardarCompra")}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label style="font-size: 20px" for="">Fecha de la compra</label>
                                <input autocomplete="off" required id="dateInput" style="font-size: 20px" class="form-control" type="date">
                                <input autocomplete="off" required id="fecha_compra" name="fecha_compra" style="font-size: 20px" class="form-control" type="hidden">
                            </div>
                        </div>  
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label style="font-size: 20px" for="">Total a pagar</label>
                                <input autocomplete="off" placeholder="$30.000" required name="total" style="font-size: 20px" class="form-control" type="number">
                            </div>
                        </div>                      
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label style="font-size: 20px" for="">Proveedor</label>
                                <select style="font-size: 20px" name="proveedor" id="proveedor" required class="form-control">
                                   <option value="">Selecciona un proveedor</option>
                                    @foreach ($proveedores as $item)
                                        <option value="{{$item->id}}">{{$item->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @if (session('user_tipo') == 1)
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label style="font-size: 20px" for="">Tipo de Compra</label>
                                <select style="font-size: 20px" name="tipo_compra" id="tipo_compra" required class="form-control">
                                   <option value="">Selecciona un proveedor</option>
                                    @foreach ($tipos_usuarios as $item)
                                        <option value="{{$item->tipo}}">{{$item->tipo_desc}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                        <hr>
                        <div class="col-lg-12">
                            <div class="text-right">
                                <button name="accion" type="submit" class="btn btn-success">Guardar Compra</button>
                                <a style="color: white" class="btn btn-danger" data-dismiss="modal">Cerrar</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
          </div>
        </div>
    </div>

    <script>
        $('#tabla_compras').DataTable({
            dom: 'Bfrtip',
            buttons: ['excel'],
            language: {
                "decimal": "",
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Compras",
                "infoEmpty": "Mostrando 0 to 0 of 0 COmpras",
                "infoFiltered": "(Filtrado de _MAX_ total Compras)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Compras",
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

        document.getElementById('dateInput').addEventListener('change', function() {
            var date = this.value;
            const [year, month, day] = date.split("-");
            const formattedDate = `${day}/${month}/${year}`;
            document.getElementById('fecha_compra').value = formattedDate;
        });
    </script>
@endsection
