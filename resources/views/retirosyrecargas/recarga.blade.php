@extends("maestra")
@section("titulo", "recargas")
@section("contenido")
<br>
    <div class="row" style="padding: 20px">
        <div class="col-12">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div style="padding: 20px;" class="col-lg-3">
                            <h2 style="color: #045f01; font-weight: bold">Fecha de Inicio</h2>
                            <input style="font-size: 36px;" id="fecha1" type="date" class="form-control">
                        </div>
                        <div style="padding: 20px;" class="col-lg-4">
                            <h2 style="color: #5f0101; font-weight: bold">Fecha Final</h2>
                            <div style="display: flex">
                                <input style="font-size: 36px;" id="fecha2" type="date" class="form-control">
                                <button onclick="buscarResultados()" class="btn btn-primary" style="font-size: 35px; margin-left: 20px; cursor: pointer"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                        <div style="padding: 20px;" class="col-lg-1">
                        </div>
                        <div style="padding: 20px;" class="col-lg-4">
                            <div class="card_ventas" style="background-color: rgb(1, 34, 95);">
                                <div style="width: 100%">
                                    <h3><strong>Total Recargas y Paquetes</strong></h3>
                                </div> 
                                <h1>$ {{ number_format($total, 2) }}</h1>
                                <i style="opacity: .7; font-size: 70px; position: absolute; right: 30px; bottom: 30px" class="fas fa-cash-register"></i>
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
                    <button style="font-size: 20px" data-toggle="modal" data-target="#modalRecarga" class="btn btn-success mb-2" id="pdf">Realizar recarga <i class="fas fa-phone-volume"></i></button>
                </div>
            </div>
            <hr>
            <div class="table-responsive">
                <table id="tabla_recargas" class="table table-bordered">
                    <thead style="background-color: #91baee">
                        <tr>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Operador</th>
                            <th>Monto</th>
                            <th>Tipo</th>
                            <th>opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($recargas as $recarga)
                        <tr>
                            <td>{{$recarga->fecha_bien}}</td>
                            <td>{{$recarga->hora}}</td>
                            <td>{{$recarga->operador}}</td>
                            <td>{{$recarga->monto}}</td>
                            <td>{{$recarga->tipo}}</td>
                            <td style="text-align: center">
                                <button onclick="setearValores({{$recarga->id}}, '{{$recarga->operador}}', {{$recarga->monto}}, '{{$recarga->tipo}}')" data-toggle="modal" data-target="#modalRecarga" class="btn btn-warning">
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
    </div>

    <div class="modal fade" id="modalRecarga" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
          <div class="modal-content">
            <div style="padding: 20px" class="modal-body">
                <form id="formrecarga" action="">
                    <input type="hidden" id="id_recarga" name="id_recarga">
                    <div class="form-group">
                        <label style="font-size: 20px; font-weight: bold" for="exampleInputPassword1">Monto de la recarga o paquete</label>
                        <input name="monto" style="font-size: 20px;" type="number" class="form-control" id="monto" placeholder="$ 3.000">
                    </div>
                    <div class="form-group">
                        <label style="font-size: 20px; font-weight: bold" for="tipo">¿Recarga o Paquete?</label>
                        <select style="font-size: 20px;" class="form-control" name="tipo" id="tipo">
                            <option value="">Seleccione una opciòn</option>
                            <option value="Recarga">Recarga</option>
                            <option value="Paquete">Paquete</option>
                        </select>
                      </div>
                    <div class="form-group">
                        <label style="font-size: 20px; font-weight: bold" for="">Selecciona el operador</label>
                        <div class="row">
                            <div class="col-lg-2" style="margin-bottom: 20px; height: 90px">
                                <input required type="radio" id="control_01" name="operador" value="Tigo" >
                                <label style="padding-top: 0px !important" class="lradio" for="control_01">
                                    <img src="/img/tigo.png" style="width: 50px" alt="">
                                </label>
                            </div>
                            <div class="col-lg-2" style="margin-bottom: 20px; height: 90px">
                                <input required type="radio" id="control_02" name="operador" value="Claro">
                                <label style="padding-top: 0px !important" class="lradio" for="control_02">
                                    <img src="/img/claro.png" style="width: 50px" alt="">
                                </label>
                            </div>
                            <div class="col-lg-2" style="margin-bottom: 20px; height: 90px">
                                <input required type="radio" id="control_03" name="operador" value="Movistar">
                                <label style="padding-top: 0px !important" class="lradio" for="control_03">
                                    <img src="/img/movistar.png" style="width: 50px" alt="">
                                </label>
                            </div>
                            <div class="col-lg-2" style="margin-bottom: 20px; height: 90px">
                                <input required type="radio" id="control_05" name="operador" value="Virgin">
                                <label style="padding-top: 0px !important" class="lradio" for="control_05">
                                    <img src="/img/virgin.png" style="width: 50px" alt="">
                                </label>
                            </div>
                            <div class="col-lg-2" style="margin-bottom: 20px; height: 90px">
                                <input required type="radio" id="control_06" name="operador" value="Exito movil">
                                <label style="padding-top: 0px !important" class="lradio" for="control_06">
                                    <img src="/img/exito.png" style="width: 50px" alt="">
                                </label>
                            </div>
                            <div class="col-lg-2" style="margin-bottom: 20px; height: 90px">
                                <input required type="radio" id="control_07" name="operador" value="Wom">
                                <label style="padding-top: 0px !important" class="lradio" for="control_07">
                                    <img src="/img/wom.png" style="width: 60px" alt="">
                                </label>
                            </div>
                            <div class="col-lg-2" style="margin-bottom: 20px; height: 90px">
                                <input required type="radio" id="control_08" name="operador" value="Uff movil">
                                <label style="padding-top: 0px !important" class="lradio" for="control_08">
                                    <img src="/img/uff.png" style="width: 50px" alt="">
                                </label>
                            </div>
                            <div class="col-lg-2" style="margin-bottom: 20px; height: 90px">
                                <input required type="radio" id="control_09" name="operador" value="ETB">
                                <label style="padding-top: 0px !important" class="lradio" for="control_09">
                                    <img src="/img/etb.png" style="width: 60px" alt="">
                                </label>
                            </div>
                            <div class="col-lg-2" style="margin-bottom: 20px; height: 90px">
                                <input required type="radio" id="control_10" name="operador" value="Direc TV">
                                <label style="padding-top: 0px !important" class="lradio" for="control_10">
                                    <img src="/img/directv.png" style="width: 60px" alt="">
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
              <button type="button" onclick="guardarRecargaPaquete()" class="btn btn-success">Guardar Movimiento</button>
              <button type="button" onclick="setearValoresVacio()" class="btn btn-danger" data-dismiss="modal">Cancelar</button>

            </div>
          </div>
        </div>
    </div>

    <script>

        $(document).ready(function () {
            var table = $('#tabla_recargas').DataTable({
                "order": [
                    [0, "desc"],
                    [1, "desc"]
                ],
                dom: 'Bfrtip',
                buttons: ['excel'],
                language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ recargas",
                    "infoEmpty": "Mostrando 0 to 0 of 0 recargas",
                    "infoFiltered": "(Filtrado de _MAX_ total recargas)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ recargas",
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

            var fechaActual = new Date();
            var primerDiaMes = new Date(fechaActual.getFullYear(), fechaActual.getMonth(), 1);
            
            var urlParams = new URLSearchParams(window.location.search);

            var fecha1Param = urlParams.get('fecha1');
            
            var fecha2Param = urlParams.get('fecha2');

            $('#fecha1').val(fecha1Param);
            $('#fecha2').val(fecha2Param);
        });   
        
        var editando = false;
        function guardarRecargaPaquete(){
            var mi = document.getElementById("monto").value;
            var ti = document.getElementById("tipo").value;

            const radioButtons = document.getElementsByName('operador');
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
                        url: '/realizar-recarga',
                        type: 'POST',
                        data: $('#formrecarga').serialize(), 
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
                        url: '/editar-recarga',
                        type: 'POST',
                        data: $('#formrecarga').serialize(), 
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

            location.href ="recargas?fecha1="+fecha1+"&fecha2="+fecha2;
        }

        function setearValores(id, operador, monto, tipo){
            setTimeout(() => {
                editando = true;
                document.getElementById("monto").value = monto;
                document.getElementById("tipo").value = tipo;
                document.getElementById("id_recarga").value = id;
                const operadorRadio = document.querySelector(`input[name="operador"][value="${operador}"]`);
                if (operadorRadio) {
                    operadorRadio.checked = true;
                }
            }, 500);
        }

        function setearValoresVacio(){
            editando = false;
            document.getElementById("monto").value = "";
            document.getElementById("tipo").value = "";
            document.getElementById("id_recarga").value = "";
            const radioButtons = document.getElementsByName('operador');

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
                        url: '/eliminar-recarga?id_recarga='+id,
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
