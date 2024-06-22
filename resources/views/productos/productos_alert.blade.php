@extends("maestra")
@section("titulo", "Productos")
@section("contenido")
<br>
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-lg-3"><h1>Productos <i class="fa fa-box"></i></h1></div>
                <div class="col-lg-7 text-right"><a style="font-size: 20px" href="{{route("productos.create")}}" class="btn btn-success mb-2">Registrar Producto</a></div>
                <div class="col-lg-2 text-right">
                    <button style="font-size: 20px" class="btn btn-primary mb-2" id="pdf">Exportar a PDF</button>
                </div>
            </div>
            
           
            <hr>
            @include("notificacion")
            <div class="table-responsive">
                <table id="tabla_productos" class="table table-bordered">
                    <thead style="background-color: #91baee">
                        <tr>
                            <th>Imagen</th>
                            <th>Código de barras</th>
                            <th>Descripción</th>
                            <th>Precio de compra</th>
                            <th>Precio de venta</th>
                            <th>Utilidad</th>
                            <th>Existencia</th>
                            <th>opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($productos as $producto)
                        <tr>
                            <td style="text-align: center">
                                <img style="height: 70px" src="/imagenes_productos/{{$producto->imagen}}" alt="">
                            </td>
                            <td>{{$producto->codigo_barras}}</td>
                            <td>{{$producto->descripcion}}</td>
                            <td>{{$producto->precio_compra}}</td>
                            <td>{{$producto->precio_venta}}</td>
                            <td>{{$producto->precio_venta - $producto->precio_compra}}</td>
                            <td class="text-center">
                                {{$producto->existencia}} <strong>{{ $producto->unidad_medida }}</strong>
                                <hr>
                                <button onclick="seleccionarProducto('{{ $producto->descripcion }}', '{{ $producto->codigo_barras }}', {{ $producto->precio_compra}}, {{$producto->precio_venta}}, {{ $producto->existencia }})" onclick="" class="btn btn-success">Inventario</button>
                            </td>
                            <td style="text-align: center">
                                <a class="btn btn-warning" href="{{route("productos.edit",[$producto])}}">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <hr>
                                <form action="{{route("productos.destroy", [$producto])}}" method="post">
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
                <br><br><br><br>
            </div>
        </div>
    </div>

    <div class="modal" id="modalInventario" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h2 class="modal-title">Inventario Producto - <strong style="color: green" id="nombre_producto"></strong></h2>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form action="{{route("modificarInventarioProducto")}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label style="font-size: 20px" for="">Cantidad Disponible</label>
                                <input required id="existencia_producto" name="cantidad_disponible" style="font-size: 20px" class="form-control" type="text">
                            </div>
                            <div class="form-group">
                                <label style="font-size: 20px" for="">Precio Compra por Unidad o por Libra</label>
                                <input required id="precio_compra_producto" name="precio_compra" style="font-size: 20px" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label style="font-size: 20px" for="">Agregar Cantidad</label>
                                <input id="fiado" required name="nueva_cantidad" style="font-size: 20px" class="form-control" type="currency">
                            </div>
                            <div class="form-group">
                                <label style="font-size: 20px" for="">Precio Venta por Unidad o por Libra</label>
                                <input required id="precio_venta_producto" name="precio_venta" style="font-size: 20px" class="form-control" type="text">
                            </div>
                        </div>
                        <input id="codigo_producto" name="codigo_producto" type="hidden">
                        <hr>
                        <div class="col-lg-12">
                            <div class="text-right">
                                <button style="font-size: 20px"  type="submit" class="btn btn-success">Guardar datos</button>
                                <a style="font-size: 20px; color: white" class="btn btn-danger" data-dismiss="modal">Cerrar</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
          </div>
        </div>
    </div>

    <script>

        $(document).ready(function () {
            var table = $('#tabla_productos').DataTable({
                dom: 'Bfrtip',
                buttons: ['pdf'],
                language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Productos",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Productos",
                    "infoFiltered": "(Filtrado de _MAX_ total Productos)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Productos",
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

            $('#pdf').on('click', function () {
                table.button('0').trigger();
            });
        });

        function seleccionarProducto(nombre, item, precio_compra, precio_venta, existencia){
            $('#modalInventario').modal("show")

            document.getElementById("nombre_producto").innerHTML = nombre;
            document.getElementById("precio_compra_producto").value = precio_compra;
            document.getElementById("precio_venta_producto").value = precio_venta;
            document.getElementById("existencia_producto").value = existencia;
            document.getElementById("codigo_producto").value = item;
        }
    </script>
@endsection
