@extends("maestra")
@section("titulo", "Códigos de barra")
@section("contenido")
    <div class="row">
        <div class="col-12">
            <br>
            <h1>Códigos de barra <i class="fas fa-barcode"></i></h1>
            <button onclick="setDateTime()" data-toggle="modal" data-target="#exampleModalCenterCodigo" class="btn btn-success mb-2"><h3>Nuevo código de barras <i style="font-size: 20px" class="fas fa-plus"></i></h3></button>
            <hr>
            <div class="table-responsive">
                <table id="tabla_codigos" class="table table-bordered">
                    <thead>
                    <tr>
                        <th style="text-align: center">Código de barras</th>
                        <th>Descripción</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Opciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($codigos as $codigo)
                        <tr>
                            <td style="text-align: center">
                                <div onclick="verCodigos('{{$codigo->imagen}}', '{{$codigo->numero}}', '{{$codigo->descripcion}}')" style="cursor: pointer" data-toggle="modal" data-target="#modalVercodigo" id="print_{{ $loop->index }}">
                                    <img width="140" height="40" src="{{$codigo->imagen}}" alt=""><br>
                                    <label style="font-size: 13px; font-weight: bold" for="">{{$codigo->numero}}</label>
                                </div>
                            </td>
                            <td>{{$codigo->descripcion}}</td>
                            <td>{{$codigo->fecha}}</td>
                            <td>{{$codigo->hora}}</td>
                            <td style="text-align: center">
                                <button onclick="printDiv('{{ $codigo->numero }}', '{{ $codigo->descripcion }}')" type="submit" class="btn btn-primary">
                                    <i class="fa fa-print"></i>
                                </button>
                                <button  onclick="setearValores({{$codigo->id}}, '{{$codigo->descripcion}}', '{{$codigo->numero}}')" data-toggle="modal" data-target="#exampleModalCenterCodigo" class="btn btn-warning">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button onclick="eliminar({{$codigo->id}})" type="submit" class="btn btn-danger">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModalCenterCodigo" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Registro de código de barras</h5>
              <button onclick="setearValoresVacio()" type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form action="" id="form_codigo_barras">
                    <input type="hidden" id="id_codigo" name="id_codigo">
                    <div class="form-group">
                        <label style="font-size: 20px; font-weight: bold" for="exampleInputPassword1">Código de barras por defecto </label>
                        <label style="color: red" for="">(si desea lo puede cambiar)</label>
                        <input name="codigo" style="font-size: 20px;" type="text" class="form-control" id="codigo" placeholder="235423423423">
                    </div>
                    <div class="form-group">
                        <label style="font-size: 20px; font-weight: bold" for="exampleInputPassword1">Ingrese una descripción</label>
                        <input name="descripcion" style="font-size: 20px;" type="text" class="form-control" id="descripcion" placeholder="Para pollo, verdura, etc...">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
              <button type="button" onclick="guardarCodigo()" class="btn btn-success">Guardar</button>
              <button type="button" onclick="setearValoresVacio()" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
    </div>

    <div class="modal fade" id="modalVercodigo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-body text-center">
                <img id="imagen_modal" src="" style="width: 300px; height: 150px" alt=""><br>
                <h2 id="codigo_modal"></h2>
                <h4 id="descripcion_modal"></h4>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
    </div>

    <div class="modal fade" id="modalImprimir" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel"></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <label for=""><h3>Ingrese la cantidad de copias que desea imprimir</h3></label>
                <input id="numero_copias" style="font-size: 25px" type="number" class="form-control" placeholder="2">
            </div>
            <div class="modal-footer">
                <button onclick="confirmarImpresion()" style="font-size: 25px" type="button" class="btn btn-primary">Imprimir</button>
                <button style="font-size: 25px" type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
    </div>

    <br><br><br>

    <script>
         $(document).ready(function () {
            var table = $('#tabla_codigos').DataTable({
                "order": [
                    [0, "desc"],
                ],
                language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Códigos ",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Códigos ",
                    "infoFiltered": "(Filtrado de _MAX_ total Códigos )",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Códigos ",
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
        });

        function setDateTime() {
            const now = new Date();
            
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            
            const formattedDateTime = `${year}${month}${day}${hours}${minutes}${seconds}`;
            document.getElementById('codigo').value = formattedDateTime;
        }

        var editando = false;

        function guardarCodigo(){
            var ci = document.getElementById("codigo").value;
            var di = document.getElementById("descripcion").value;

            if(ci == '' || di == ''){
                Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "Todos los campos son obligatorios",
                    showConfirmButton: false,
                    timer: 1500
                });
            }else{
                if(editando == false){
                    $.ajax({
                        url: '/guardar-codigo',
                        type: 'POST',
                        data: $('#form_codigo_barras').serialize(), 
                        success: function(response) {
                            if(response.status != "error"){
                                Swal.fire({
                                    position: "center",
                                    icon: response.status,
                                    title: response.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                setTimeout(() => {
                                    location.reload();
                                }, 2000);
                            }else{
                                Swal.fire({
                                    position: "center",
                                    icon: response.status,
                                    title: response.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        }
                    });
                }else{
                    $.ajax({
                        url: '/editar-codigo',
                        type: 'POST',
                        data: $('#form_codigo_barras').serialize(), 
                        success: function(response) {
                            if(response.status != "error"){
                                Swal.fire({
                                    position: "center",
                                    icon: response.status,
                                    title: response.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                setTimeout(() => {
                                    location.reload();
                                }, 2000);
                            }else{
                                Swal.fire({
                                    position: "center",
                                    icon: response.status,
                                    title: response.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        }
                    });
                }
            }
        }

        function setearValores(id, descripcion, codigo){
            setTimeout(() => {
                editando = true;
                document.getElementById("id_codigo").value = id;
                document.getElementById("descripcion").value = descripcion;
                document.getElementById("codigo").value = codigo;
                document.getElementById("codigo").disabled = true;
            }, 500);
        }

        function setearValoresVacio(){
            editando = false;
            document.getElementById("id_codigo").value =  "";
            document.getElementById("descripcion").value = "";
            document.getElementById("codigo").value = "";
            document.getElementById("codigo").disabled = false;
        }

        function eliminar(id){
            Swal.fire({
                title: "¿Esta seguro de eliminar este código de barras?",
                showCancelButton: true,
                confirmButtonText: "Si",
                cancelButtonText: "No"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/eliminar-codigo?id_codigo='+id,
                        type: 'GET',
                        success: function(response) {
                            if(response.status != "error"){
                                Swal.fire({
                                    position: "center",
                                    icon: response.status,
                                    title: response.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                setTimeout(() => {
                                    location.reload();
                                }, 2000);
                            }else{
                                Swal.fire({
                                    position: "center",
                                    icon: response.status,
                                    title: response.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        }
                    });
                } 
            });
        }

        var codigo_imprimir = 0;
        var nombre_imprimir = '';
        function printDiv(id, nombre) {
            codigo_imprimir = id;
            nombre_imprimir = nombre;
            $('#modalImprimir').modal('show');
        }

        function confirmarImpresion(){
            var data = {
                numero: document.getElementById("numero_copias").value,
                codigo: codigo_imprimir,
                nombre: nombre_imprimir
            }

            $.ajax({
                url: '/imprimir-codigo',
                type: 'GET',
                data: data, 
                success: function(response) {
                    $('#modalImprimir').modal('hide');
                    codigo_imprimir = 0;
                    nombre_imprimir = '';
                    document.getElementById("numero_copias").value = '';
                    Swal.fire({
                        position: "center",
                        icon: 'info',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });                        
                },
                error: function(xhr, status, error) {
                    $('#modalImprimir').modal('hide');
                    codigo_imprimir = 0;
                    nombre_imprimir = '';
                    document.getElementById("numero_copias").value = '';
                    Swal.fire({
                        position: "center",
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al intentar imprimir el código: ' + xhr.responseJSON.message,
                        showConfirmButton: true
                    });
                }
            });
        }
    </script>
@endsection