@extends("maestra")
@section("titulo", "consignacion o retiro")
@section("contenido")
<br>
    <div class="row" style="padding: 20px">
        <div class="col-12">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div style="padding: 20px;" class="col-lg-2">
                            <h4 style="color: #045f01; font-weight: bold">Fecha de Inicio</h4>
                            <input style="font-size: 24px;" id="fecha1" type="date" class="form-control">
                        </div>
                        <div style="padding: 20px;" class="col-lg-3">
                            <h4 style="color: #5f0101; font-weight: bold">Fecha Final</h4>
                            <div style="display: flex">
                                <input style="font-size: 24px;" id="fecha2" type="date" class="form-control">
                                <button onclick="buscarResultados()" class="btn btn-primary" style="font-size: 24px; margin-left: 20px; cursor: pointer"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                        <div class="col-lg-1"></div>
                        <div style="padding: 20px;" class="col-lg-3">
                            <div class="card_ventas" style="background-color: rgb(146, 12, 12);">
                                <div style="width: 100%">
                                    <h3><strong>Total Retiros</strong></h3>
                                </div> 
                                <h4>$ {{ number_format($total1, 2) }}</h4>
                                <i style="opacity: .7; font-size: 70px; position: absolute; right: 30px; bottom: 30px" class="fas fa-money-bill-alt"></i>
                            </div>
                        </div>
                        <div style="padding: 20px;" class="col-lg-3">
                            <div class="card_ventas" style="background-color: rgb(6, 77, 29);">
                                <div style="width: 100%">
                                    <h3><strong>Total Consignaciones</strong></h3>
                                </div> 
                                <h4>$ {{ number_format($total2, 2) }}</h4>
                                <i style="opacity: .7; font-size: 70px; position: absolute; right: 30px; bottom: 30px" class="far fa-money-bill-alt"></i>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>
                <br>
                <hr>
            </div>
            <div class="row">
                <div class="col-lg-6 text-left">
                    <button style="font-size: 20px" data-toggle="modal" data-target="#modalMovimiento" class="btn btn-success mb-2" id="pdf">Realizar Consignación o Retiro <i class="far fa-money-bill-alt"></i></button>
                </div>
            </div>
            <hr>
            <div class="table-responsive">
                <table id="tabla_consignacion_retiro" class="table table-bordered">
                    <thead style="background-color: #91baee">
                        <tr>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Banco</th>
                            <th>Monto</th>
                            <th>Tipo</th>
                            <th>opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($consignacion_retiro as $recarga)
                        <tr>
                            <td>{{$recarga->fecha_bien}}</td>
                            <td>{{$recarga->hora}}</td>
                            <td>{{$recarga->banco}}</td>
                            <td>{{$recarga->monto}}</td>
                            <td>{{$recarga->tipo}}</td>
                            <td style="text-align: center">
                                <button onclick="setearValores({{$recarga->id}}, '{{$recarga->banco}}', {{$recarga->monto}}, '{{$recarga->tipo}}')" data-toggle="modal" data-target="#modalMovimiento" class="btn btn-warning">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button onclick="eliminar({{$recarga->id}})" type="submit" class="btn btn-danger">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <br><br><br><br>
            </div>
        </div>

        <div class="modal fade" id="modalMovimiento" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
              <div class="modal-content">
                <div style="padding: 20px" class="modal-body">
                    <form id="formmovimiento" action="">
                        <input type="hidden" id="id_movimiento" name="id_movimiento">
                        <div class="form-group">
                            <label style="font-size: 20px; font-weight: bold" for="exampleInputPassword1">Monto del movimiento</label>
                            <input name="monto" style="font-size: 20px;" type="number" class="form-control" id="monto" placeholder="$ 3.000">
                        </div>
                        <div class="form-group">
                            <label style="font-size: 20px; font-weight: bold" for="tipo">¿Retiro o Consignación?</label>
                            <select style="font-size: 20px;" class="form-control" name="tipo" id="tipo">
                                <option value="">Seleccione una opciòn</option>
                                <option value="Retiro">Retiro</option>
                                <option value="Consignacion">Consignación</option>
                            </select>
                          </div>
                        <div class="form-group">
                            <label style="font-size: 20px; font-weight: bold" for="">Selecciona el banco</label>
                            <div class="row">
                                <div class="col-lg-3" style="margin-bottom: 20px; height: 120px">
                                    <input required type="radio" id="control_00" name="banco" value="Bancolombia" >
                                    <label style="padding-top: 0px !important" class="lradio" for="control_00">
                                        <img src="/img/bancolombia.png" style="height: 60px" alt="">
                                    </label>
                                </div>
                                <div class="col-lg-3" style="margin-bottom: 20px; height: 120px">
                                    <input required type="radio" id="control_01" name="banco" value="Nequi" >
                                    <label style="padding-top: 0px !important" class="lradio" for="control_01">
                                        <img src="/img/nequi.png" style="height: 90px" alt="">
                                    </label>
                                </div>
                                <div class="col-lg-3" style="margin-bottom: 20px; height: 120px">
                                    <input required type="radio" id="control_02" name="banco" value="Ahorrro a la mano">
                                    <label style="padding-top: 0px !important" class="lradio" for="control_02">
                                        <img src="/img/ahorro_mano.png" style="height: 90px" alt="">
                                    </label>
                                </div>
                                <div class="col-lg-3" style="margin-bottom: 20px; height: 120px">
                                    <input required type="radio" id="control_03" name="banco" value="Daviplata">
                                    <label style="padding-top: 0px !important" class="lradio" for="control_03">
                                        <img src="/img/daviplata.png" style="height: 90px" alt="">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                  <button type="button" onclick="guardarMovimiento()" class="btn btn-success">Guardar Movimiento</button>
                  <button type="button" onclick="setearValoresVacio()" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
    
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            var table = $('#tabla_consignacion_retiro').DataTable({
                "order": [
                    [0, "desc"],
                    [1, "desc"]
                ],
                dom: 'Bfrtip',
                buttons: ['excel'],
                language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    "infoEmpty": "Mostrando 0 to 0 of 0 registros",
                    "infoFiltered": "(Filtrado de _MAX_ total registros)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ registros",
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
            
            var urlParams = new URLSearchParams(window.location.search);
            var fecha1Param = urlParams.get('fecha1');
            var fecha2Param = urlParams.get('fecha2');

            $('#fecha1').val(fecha1Param);
            $('#fecha2').val(fecha2Param);
        }); 

        var editando = false;
        function guardarMovimiento(){
            var mi = document.getElementById("monto").value;
            var ti = document.getElementById("tipo").value;

            const radioButtons = document.getElementsByName('banco');
            let oi = '';

            for (const radioButton of radioButtons) {
                if (radioButton.checked) {
                    oi = radioButton.value;
                    break;
                }
            }

            if(mi == '' || ti == '' || oi == ''){
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
                        url: '/realizar-movimiento',
                        type: 'POST',
                        data: $('#formmovimiento').serialize(), 
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
                        url: '/editar-movimiento',
                        type: 'POST',
                        data: $('#formmovimiento').serialize(), 
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

        function buscarResultados(){
            var fecha1 = document.getElementById("fecha1").value;
            var fecha2 = document.getElementById("fecha2").value;

            location.href ="consignacion-retiro?fecha1="+fecha1+"&fecha2="+fecha2;
        }

        function setearValores(id, banco, monto, tipo){
            setTimeout(() => {
                editando = true;
                document.getElementById("monto").value = monto;
                document.getElementById("tipo").value = tipo;
                document.getElementById("id_movimiento").value = id;
                const bancoRadio = document.querySelector(`input[name="banco"][value="${banco}"]`);
                if (bancoRadio) {
                    bancoRadio.checked = true;
                }
            }, 500);
        }

        function setearValoresVacio(){
            editando = false;
            document.getElementById("monto").value = "";
            document.getElementById("tipo").value = "";
            document.getElementById("id_movimiento").value = "";
            const radioButtons = document.getElementsByName('banco');

            for (const radioButton of radioButtons) {
                radioButton.checked = false;
            }
        }

        function eliminar(id){
            Swal.fire({
                title: "¿Esta seguro de eliminar este movimiento?",
                showCancelButton: true,
                confirmButtonText: "Si",
                cancelButtonText: "No"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/eliminar-movimiento?id_movimiento='+id,
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
    </script>
@endsection
