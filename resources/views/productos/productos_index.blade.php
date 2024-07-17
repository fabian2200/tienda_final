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
                    <a href="/generar-pdf" target="_blank" style="font-size: 20px" class="btn btn-primary mb-2" id="pdf">Exportar a PDF</a>
                </div>
            </div>
            
           
            <hr>
            <br>
            <div class="container" style="width: 100% !important; max-width: 100%">
                <div class="row">
                    <div class="col-lg-6"></div>
                    <div class="col-lg-6">
                        <form action="{{ route('productos.index') }}" method="GET" class="mb-4">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Nombre del producto o Código de barras" value="{{ request()->input('search') }}">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @include("notificacion")
            <div class="table-responsive">
                <table id="tabla_productos" class="table table-bordered">
                    <thead style="background-color: #91baee">
                        <tr>
                            <th>Código de barras</th>
                            <th>Descripción</th>
                            <th>Precio de compra</th>
                            <th>Precio de venta</th>
                            <th>Utilidad</th>
                            <th>Ganancia</th>
                            <th>Existencia</th>
                            <th>opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($productos as $producto)
                        <tr>
                            <td style="text-align: center">
                                {{$producto->codigo_barras}} 
                                <hr>
                                <button onclick="setCodigoBarras('{{$producto->codigo_barras}}')" data-toggle="modal" data-target="#modalEditarCodigo" class="btn btn-warning">Editar Codigo <i class="fas fa-pencil-alt"></i></button>
                            </td>
                            <td>{{$producto->descripcion}}</td>
                            <td>{{$producto->precio_compra}}</td>
                            <td>{{$producto->precio_venta}}</td>
                            <td>{{$producto->precio_venta - $producto->precio_compra}}</td>
                            <td style="text-align: center">
                                <strong>{{$producto->porcentaje}} %</strong>
                                <br>
                                <br>
                                <button onclick="seleccionarProducto2('{{ $producto->descripcion }}', '{{ $producto->codigo_barras }}', {{ $producto->precio_compra}}, {{$producto->precio_venta}}, {{ $producto->existencia }})"  class="btn btn-dark">Cambiar <br> porcentaje</button>
                            </td>
                            <td class="text-center">
                                {{$producto->existencia}} <strong>{{ $producto->unidad_medida }}</strong>
                                <hr>
                                <button onclick="seleccionarProducto('{{ $producto->descripcion }}', '{{ $producto->codigo_barras }}', {{ $producto->precio_compra}}, {{$producto->precio_venta}}, {{ $producto->existencia }}, '{{ $producto->unidad_medida }}')" class="btn btn-success">Agregar Inventario</button>
                            </td>
                            <td style="text-align: center">
                                <a class="btn btn-warning" href="{{route("productos.edit",[$producto])}}">
                                   Editar <i class="fa fa-edit"></i>
                                </a>
                                <hr>
                                <form action="{{route("productos.destroy", [$producto])}}" method="post">
                                    @method("delete")
                                    @csrf
                                    <button type="submit" class="btn btn-danger">
                                       Eliminar <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <br>
                <!-- Pagination links -->
                <div class="d-flex justify-content-center">
                    {{ $productos->appends(request()->input())->links() }}
                </div>
                <br><br><br><br>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditarCodigo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Editar Codigo</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form action="{{route("modificarCodigoProducto")}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label style="font-size: 20px" for="">Codigo de barras anterior</label>
                                <input required id="codigo_anterior" name="codigo_anterior" style="font-size: 20px" class="form-control" type="text">
                            </div>
                            <div class="form-group">
                                <label style="font-size: 20px" for="">Codigo barra nuevo</label>
                                <input required id="codigo_nuevo" name="codigo_nuevo" style="font-size: 20px" class="form-control" type="text">
                            </div>
                        </div>
                        <hr>
                        <div class="col-lg-12">
                            <div class="text-center">
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
                                <label style="font-size: 20px" for="">Precio Compra por <span id="um1"></span></label>
                                <input required id="precio_compra_producto" name="precio_compra" style="font-size: 20px" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label style="font-size: 20px" for="">Agregar Cantidad</label>
                                <input id="fiado" required name="nueva_cantidad" style="font-size: 20px" class="form-control" type="currency">
                            </div>
                            <div class="form-group">
                                <label style="font-size: 20px" for="">Precio Venta por <span id="um2"></span></label>
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

    <div class="modal" id="modalInventarioPorcentaje" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h2 class="modal-title">Inventario Producto - <strong style="color: green" id="nombre_producto_p"></strong></h2>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form action="{{route("modificarInventarioProductoPorcentaje")}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label style="font-size: 20px" for="">Cantidad Disponible</label>
                                <input required id="existencia_producto_p" name="cantidad_disponible" style="font-size: 20px" class="form-control" type="text">
                            </div>
                            <div class="form-group">
                                <label style="font-size: 20px" for="">Precio Compra</label>
                                <input  oninput="calcularPrecioVenta()" required id="precio_compra_producto_p" name="precio_compra" style="font-size: 20px" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label style="font-size: 20px" for="">Agregar Cantidad</label>
                                <input id="fiado" value="0" required name="nueva_cantidad" style="font-size: 20px" class="form-control" type="currency">
                            </div>
                            <div class="form-group">
                                <label style="font-size: 20px" for="">Porcentaje de ganancia</label>
                                <input  oninput="calcularPrecioVenta()" required id="precio_venta_producto_p" name="precio_venta" style="font-size: 20px; width: 80%" class="form-control" type="hidden">
                                <input oninput="calcularPrecioVenta()" required id="porcentaje_producto_p" name="porcentaje_ganancia" style="font-size: 20px" class="form-control" type="number">
                            </div>
                        </div>
                        <input id="codigo_producto_p" name="codigo_producto" type="hidden">
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
        function seleccionarProducto(nombre, item, precio_compra, precio_venta, existencia, um){
            $('#modalInventario').modal("show")

            document.getElementById("nombre_producto").innerHTML = nombre;
            document.getElementById("precio_compra_producto").value = precio_compra;
            document.getElementById("precio_venta_producto").value = precio_venta;
            document.getElementById("existencia_producto").value = existencia;
            document.getElementById("codigo_producto").value = item;

            document.getElementById("um1").innerText = um;
            document.getElementById("um2").innerText = um;
        }

        function seleccionarProducto2(nombre, item, precio_compra, precio_venta, existencia){
            $('#modalInventarioPorcentaje').modal("show")

            document.getElementById("nombre_producto_p").innerHTML = nombre;
            document.getElementById("precio_compra_producto_p").value = precio_compra;
            document.getElementById("existencia_producto_p").value = existencia;
            document.getElementById("codigo_producto_p").value = item;


            var ganancia = precio_venta - precio_compra;
            var porcentajeGanancia = (ganancia / precio_compra) * 100;

            porcentajeGanancia = parseFloat(porcentajeGanancia.toFixed(2));

            document.getElementById("porcentaje_producto_p").value = porcentajeGanancia;
            document.getElementById("precio_venta_producto_p").value = precio_venta;
        }

        function calcularPrecioVenta() {
            var precioCompra =  document.getElementById("precio_compra_producto_p").value;
            var porcentajeGanancia = document.getElementById("porcentaje_producto_p").value;
            
            porcentajeGanancia = porcentajeGanancia.replace(',', '.');

            var precioVenta = precioCompra * (1 + (porcentajeGanancia / 100));
            precioVenta =  parseFloat(precioVenta.toFixed(2));

            document.getElementById("precio_venta_producto_p").value = precioVenta;
        }

        function setCodigoBarras(codigo) {
            document.getElementById("codigo_anterior").value = codigo;
        }        
    </script>
@endsection