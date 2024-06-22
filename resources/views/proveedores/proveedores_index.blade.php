@extends("maestra")
@section("titulo", "Proveedores")
@section("contenido")
    <div class="row">
        <div class="col-12">
            <br>
            <h1>Proveedores <i class="fas fa-truck-moving"></i></h1>
            <br>
            <button data-toggle="modal" data-target="#exampleModal" class="btn btn-success mb-2"><h3>Registrar proveedor <i style="font-size: 20px" class="fas fa-plus"></i></h3></button>
            @if ($errors->has('mensaje'))
                <div class="alert alert-danger">
                    Ya existe un proveedor con ese nombre
                </div>
            @endif
            <hr>
            <div class="table-responsive">
                <table id="table_proveedores" class="table table-bordered">
                    <thead>
                    <tr>
                        <th style="width: 70%">Proveedor</th>
                        <th style="text-align: center">Opciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($proveedores as $proveedor)
                        <tr>
                            <td><p style="text-transform: capitalize">{{$proveedor->nombre}}</p></td>
                            <td style="text-align: center">
                                <button onclick="editarProveedor({{$proveedor->id}}, '{{$proveedor->nombre}}')" class="btn btn-warning">
                                    <i class="fa fa-edit"></i>
                                </button>
                            
                                <button onclick="eliminarProveedor({{$proveedor->id}})" class="btn btn-danger">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <br><br><br><br>
        </div>
    </div>

   <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Registro de proveedores</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <form action="{{route("guardarProveedor")}}" method="post">
                    @csrf
                    <div class="form-group">
                      <label style="font-weight: bold" for="exampleInputEmail1">Ingrese el nombre del proveedor</label>
                      <input autocomplete="none" type="text" class="form-control" name="nombre" aria-describedby="emailHelp" placeholder="Coca Cola...">
                      <small id="emailHelp" class="form-text text-muted">No debe existir otro proveedor con ese nombre</small>
                    </div>
                    <button type="submit" class="btn btn-success">Guardar cambios</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">cerrar</button>
                </form>
            </div>
        </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Editar proveedor</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <form action="{{route("editarProveedor")}}" method="post">
                    @csrf
                    <div class="form-group">
                      <label style="font-weight: bold" for="exampleInputEmail1">Ingrese el nombre del proveedor</label>
                      <input type="hidden" name="id" id="id_proveedor">
                      <input id="nombre_proveedor" type="text" class="form-control" name="nombre" aria-describedby="emailHelp" placeholder="Coca Cola...">
                      <small id="emailHelp" class="form-text text-muted">No debe existir otro proveedor con ese nombre</small>
                    </div>
                    <button type="submit" class="btn btn-success">Guardar cambios</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">cerrar</button>
                </form>
            </div>
        </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            var table = $('#table_proveedores').DataTable({
                language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Proveedores",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Proveedores",
                    "infoFiltered": "(Filtrado de _MAX_ total Proveedores)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Proveedores",
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

        function editarProveedor(id, nombre){
            $("#exampleModal2").modal("show");

            document.getElementById('id_proveedor').value = id;
            document.getElementById('nombre_proveedor').value = nombre;
        }

        function eliminarProveedor(id){
            Swal.fire({
                title: "¿Esta seguro de eliminar este proveedor?",
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: "Si",
                cancelButtonText: 'No',
                cancelButtonColor: 'red',
                confirmButtonColor: 'green'
            }).then((result) => {
                if (result.isConfirmed) {
                    elimninarOK(id);
                }
            });
        }

        function elimninarOK(id){
            $.ajax({
                url: '/eliminarProveedor?id='+id,
                type: 'GET',
                success: function(response) {
                  
                    Swal.fire({
                        position: "center",
                        icon: response.status,
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    
                    if(response.status != "error"){
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    }
                }
            });
        }
    </script>
@endsection
