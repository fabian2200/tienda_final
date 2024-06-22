@extends("maestra")
@section("titulo", "Editar producto")
@section("contenido")
<br>
    <div class="row">
        <div class="col-12">
            <h1>Editar producto</h1>
            <form method="POST"  enctype="multipart/form-data"  action="{{route("productos.update", [$producto])}}">
                @method("PUT")
                @csrf
                <div class="row">
                    <div class="col-lg-8">
                        <div class="form-group">
                            <label class="label">Código de barras</label>
                            <div class="row">
                                <div class="col-10">
                                    <input required value="{{$producto->codigo_barras}}" autocomplete="off" id="codigo_barras" name="codigo_barras" class="form-control" type="text" placeholder="Código de barras">
                                </div>
                                <div class="col-2" style="display: flex; justify-content: center; align-items: center">
                                    <button type="button" data-toggle="modal" data-target="#modalCodigosBarras" style="width: 70%; padding: 3px !important;" class="btn btn-warning">
                                        <i class="fas fa-2x fa-barcode"></i>
                                    </button>
                                </div>
                            </div>
                            
                        </div>
                        <div class="form-group">
                            <label class="label">Descripción</label>
                            <input required value="{{$producto->descripcion}}" autocomplete="off" name="descripcion"
                                class="form-control"
                                type="text" placeholder="Descripción">
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="label">Precio de compra</label>
                                    <input required value="{{$producto->precio_compra}}" autocomplete="off" name="precio_compra"
                                        class="form-control"
                                        type="decimal(9,2)" placeholder="Precio de compra">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="label">Precio de venta</label>
                                    <input required value="{{$producto->precio_venta}}" autocomplete="off" name="precio_venta"
                                        class="form-control"
                                        type="decimal(9,2)" placeholder="Precio de venta">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="label">Existencia</label>
                                    <input required value="{{$producto->existencia}}" autocomplete="off" name="existencia"
                                        class="form-control"
                                        type="decimal(9,2)" placeholder="Existencia">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="label">Medida</label>
                                    <select name="unidad_medida" id="unidad_medida" class="form-control">
                                        <option {{ $producto->unidad_medida == 'Unidades' ? 'selected' : '' }} value="Unidades">Unidades</option>
                                        <option {{ $producto->unidad_medida == 'Gramos' ? 'selected' : '' }} value="Gramos">Gramos</option>
                                        <option {{ $producto->unidad_medida == 'Libras' ? 'selected' : '' }} value="Libras">Libras</option>
                                        <option {{ $producto->unidad_medida == 'Kilos' ? 'selected' : '' }} value="Kilos">Kilos</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12" style="margin-bottom: 20px">
                                <label class="label">Categoria del producto</label>
                                <select name="categoria" class="form-control select2" placeholder="Select City" required>
                                    <option value="">Seleccione una opcion</option>
                                    @foreach ($categorias as $item)
                                        <option {{ $producto->categoria == $item->nombre ? 'selected' : '' }} value="{{$item->nombre}}">{{$item->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <label class="imagen_producto" for="imagen">
                            <img id="imagen_previa" style="height: 100px;" src="data:image/jpeg;base64,{{$producto->imagen}}" alt="">
                            Foto del producto
                        </label>
                        <input onchange="cargarImagen()" style="display: none" type="file" id="imagen" name="imagen" class="form-control">
                    </div>
                </div>
                <div class="col-lg-12">
                    @include("notificacion")
                    <button class="btn btn-success">Guardar</button>
                    <a class="btn btn-primary" href="{{route("productos.index")}}">Volver al listado</a>
                </div>
            </form>
            <br><br>
        </div>

        <div class="modal fade" id="modalCodigosBarras" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h3 class="modal-title" id="exampleModalLongTitle">Selecciona un código de barras</h3>
                </div>
                <div class="modal-body">
                    <table id="tabla_codigos_ep" class="table table-bordered">
                        <thead>
                        <tr>
                            <th style="text-align: center">Código de barras</th>
                            <th>Código</th>
                            <th>Descripción</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($codigos as $codigo)
                            <tr onclick="setearCodigoBarra('{{$codigo->numero}}')">
                                <td style="text-align: center">
                                    <img width="100" height="30" src="{{$codigo->imagen}}" alt=""><br>
                                </td>
                                <td>
                                    <label style="font-size: 13px; font-weight: bold" for="">{{$codigo->numero}}</label>
                                </td>
                                <td>{{$codigo->descripcion}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                </div>
              </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('.select2').select2();
            setTimeout(() => {
                var table = $('#tabla_codigos_ep').DataTable({
                    "pageLength": 5,
                    language: {
                        "decimal": "",
                        "emptyTable": "No hay información",
                        "info": "Mostrando _START_ a _END_ de _TOTAL_ Registros",
                        "infoEmpty": "Mostrando 0 to 0 of 0 Registros",
                        "infoFiltered": "(Filtrado de _MAX_ total Registros)",
                        "infoPostFix": "",
                        "thousands": ",",
                        "lengthMenu": "Mostrar _MENU_ Registros",
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
            }, 1000);
        });
            
        function cargarImagen() {
            var input = document.getElementById('imagen');
            var imagenPrevio = document.getElementById('imagen_previa');
    
            if (input.files && input.files[0]) {
                var reader = new FileReader();
    
                reader.onload = function (e) {
                    imagenPrevio.src = e.target.result;
                };
    
                reader.readAsDataURL(input.files[0]);
            }
        }

        function setearCodigoBarra(codigo){
            document.getElementById("codigo_barras").value = codigo;
            $("#modalCodigosBarras").modal('hide');
        }
    </script>
@endsection