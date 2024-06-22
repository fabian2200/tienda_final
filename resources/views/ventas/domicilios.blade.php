@extends("maestra")
@section("titulo", "Domicilios")
@section("contenido")
<br>
<h1 style="width: 100%; text-align: center"><strong>Listado de domicilios</strong></h1>
<hr>
<div class="row">
    <div class="col-12">
        @include("notificacion")
        <div class="table-responsive">
            <table id="tabla_ventas" class="table table-bordered">
                <thead style="background-color: #91baee">
                    <tr>
                        <th>Cliente</th>
                        <th>Direccion</th>
                        <th>Total</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody id="tabla_domicilios">
               
                </tbody>
            </table>
            <br><br><br>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Detalles del pedido</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        <label style="font-size: 20px" for="">Imprimir factura</label>
                        <select style="font-size: 15px !important" name="imprimir_factura" id="imprimir_factura" class="form-control">
                            <option selected value="si">si</option>
                            <option value="no">no</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label style="font-size: 20px" for="">Cliente</label>
                        <input style="font-size: 15px !important" autocomplete="off" id="nombre_cliente" name="nombre_cliente" style="font-size: 20px" class="form-control" type="text">
                        <input autocomplete="off" id="direccion_cliente" name="direccion_cliente" style="font-size: 20px" class="form-control" type="hidden">
                        <input autocomplete="off" id="celular_cliente" name="celular_cliente" style="font-size: 20px" class="form-control" type="hidden">
                        <input autocomplete="off" id="id_cliente" name="id_cliente" style="font-size: 20px" class="form-control" type="hidden">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label style="font-size: 20px" for="">Metodo de pago</label>
                        <select style="width: 100%;" class="form-control" name="metodo_pago" required id="metodo_pago">
                           <option value="Efectivo">Efectivo</option>
                           <option value="Transferencia">Transferencia</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label style="font-size: 20px" for="">Total Cliente Paga</label>
                        <input oninput="calcularCambio(this)" style="font-size: 15px !important" autocomplete="off" id="total_dinero" required name="total_dinero" style="font-size: 20px" class="form-control" type="number">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label style="font-size: 20px" for="">Subtotal pagar</label>
                        <input style="font-size: 15px !important" id="total_pagar_tv" autocomplete="off" required name="total_pagar" style="font-size: 20px" class="form-control" type="text">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label style="font-size: 20px" for="">Domicilio</label>
                        <input value="0" style="font-size: 15px !important" id="valor_domicilio" autocomplete="off" required name="valor_domicilio" style="font-size: 20px" class="form-control" type="text">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label style="font-size: 20px" for="">Total a Pagar</label>
                        <input value="0" style="font-size: 15px !important" id="total_pagar_con_domi" autocomplete="off" required name="total_pagar_con_domi" style="font-size: 20px" class="form-control" type="text">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label style="font-size: 20px" for="">Total Cambio</label>
                        <input style="font-size: 15px !important" autocomplete="off" required name="total_vueltos" id="vueltos" style="font-size: 20px" class="form-control" type="text">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label style="font-size: 20px" for="">Total Fiado</label>
                        <input style="font-size: 15px !important" autocomplete="off" id="fiado" required name="total_fiado" style="font-size: 20px" class="form-control" type="currency">
                    </div>
                </div>
            </div>
            <hr>
            <table style="font-size: 15px !important" class="table table-bordered">
                <thead>
                    <tr style="background-color: #75caeb;">
                        <th>Código de barras</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody id="tbodyTablaProductos">
                   
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button onclick="guardarVenta()" type="button" class="btn btn-success">Guardar Venta</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="registroCliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content" style="background-color: rgb(236, 237, 238)">
        <div class="modal-body">
            <h4 id="nombre_celular_no_existe" style="font-weight: bold"></h4>
            <input style="font-size: 20px" class="form-control" id="valor_domicilio_guardar" placeholder="$2.000" type="number">
        </div>
        <div class="modal-footer">
          <button type="button" onclick="guardarCliente()" class="btn btn-success">Guardar Cambios</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    function obtenerDomicilios(){
        $.ajax({
            url: 'http://192.168.1.76/tienda2/ver_domicilios.php',
            type: 'GET',
            success: function(response) {
                response = JSON.parse(response);
                var div = "";
                response.forEach(element => {
                    div += "<tr>";
                    div += "<td>"+element.nombre+"</td>";
                    div += "<th>"+element.direccion+"</th>";
                    div += "<td>"+element.total_pagar+"</td>";
                    div += "<td>"+element.fecha_domi+"</td>";
                    div += "<td><span style='padding: 5px; border-radius: 6px; background-color: orange'>"+element.estado+"</span></td>";
                    div += "<td><button onclick='obtenerInfoPedido("+element.id+")' class='btn btn-success'>Despachar</button></td>";
                    div += "</tr>";
                });

                document.getElementById("tabla_domicilios").innerHTML = div;
            }
        });
        return false;
    }

    obtenerDomicilios();

    setInterval(obtenerDomicilios, 10000);

    var total_pagar = 0;
    var productos = [];
    var id_pedido_sel = "";
    function obtenerInfoPedido(id_pedido){
        id_pedido_sel = id_pedido;
        $.ajax({
            url: 'http://192.168.1.76/tienda2/info_pedido.php?id_pedido='+id_pedido,
            type: 'GET',
            success: function(response) {
                response = JSON.parse(response);
                var div = "";
                total_pagar = 0;
                response.productos.forEach(element => {
                    element.cantidad = parseFloat(element.cantidad)
                    element.precio = parseFloat(element.precio)

                    div += "<tr>"+
                        "<td>"+element.codigo_barras+"</td>"+
                        "<td>"+element.descripcion+"</td>"+
                        "<td>"+element.precio+"</td>"+
                        "<td>"+element.cantidad+" "+element.unidad+"</td>"+
                        "<td>$ "+(element.cantidad * element.precio)+"</td>"+
                    "</tr>";

                    total_pagar += element.cantidad * element.precio;
                });

                productos = response.productos;

                div += "<tr style='background-color: #75caeb;'>"+
                    "<th colspan='4'>Total</th>"+
                    "<th>$ "+(total_pagar)+"</th>"+
                "</tr>";

                document.getElementById("tbodyTablaProductos").innerHTML = div;
                document.getElementById("total_pagar_tv").value = total_pagar;
                document.getElementById("nombre_cliente").value = response.nombre;
                document.getElementById("direccion_cliente").value = response.direccion;      
                document.getElementById("celular_cliente").value = response.celular;
                document.getElementById("vueltos").value = 0;
                document.getElementById("fiado").value = 0;
                document.getElementById("total_dinero").value = total_pagar;
                document.getElementById("metodo_pago").value = response.metodo_pago;
                verificarCliente(response.celular, response.nombre, response.direccion);
            }
        });
        return false;
    }

    function calcularCambio(element){
        let total_con_domi = document.getElementById("total_pagar_con_domi").value;
        var valor = (-1) * (total_con_domi - element.value).toFixed(3)

        if(valor < 0){
            document.getElementById("vueltos").value = 0;
        }else{
            document.getElementById("vueltos").value = valor;
        }
        
        if(valor < 0){
            document.getElementById("fiado").value = (-1) * valor;
        }else{
            document.getElementById("fiado").value = 0;
        }
    }


    function verificarCliente(celular_cliente, nombre_cliente, direccion_cliente){
        $.ajax({
            url: "/verificar-cliente-existe?celular_cliente="+celular_cliente,
            type: "GET",
            contentType: "application/json",
            success: function(respuesta) {
                if(respuesta.estado == 0){
                    document.getElementById("nombre_celular_no_existe").innerHTML = "El cliente <strong style='color: red'>("+nombre_cliente+")</strong> con el numero de celular <strong style='color: red'>("+celular_cliente+")</strong> y con dirección <strong style='color: red'>("+direccion_cliente+")</strong> no esta registrado, por favor ingrese el valor de domicilio que desea guardar para este cliente";
                    $('#registroCliente').modal({backdrop: 'static', keyboard: false});
                }else{
                    document.getElementById("id_cliente").value = respuesta.id_cliente;
                    document.getElementById("valor_domicilio").value = respuesta.valor_domi;
                    calcularPrecioTotal(respuesta.valor_domi);
                    $('#exampleModal').modal('show');
                }
            },
        });
    }

    function guardarCliente(){
        var valor_domi_g = document.getElementById("valor_domicilio_guardar").value;
        if(valor_domi_g != ""){
            var datos = {
                telefono: document.getElementById("celular_cliente").value,
                nombre: document.getElementById("nombre_cliente").value,
                direccion: document.getElementById("direccion_cliente").value,
                valor_domi: valor_domi_g
            }

            $.ajax({
                url: "/guardar-cliente-domi",
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify(datos),
                success: function(respuesta) {
                    if(respuesta.estado == 0){
                        Swal.fire({
                            position: "center",
                            icon: "error",
                            title: respuesta.mensaje,
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }else{
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "Valor asignado correctamente",
                            showConfirmButton: false,
                            timer: 2000
                        });

                        $('#registroCliente').modal('hide');
                        document.getElementById("valor_domicilio").value = valor_domi_g;
                        document.getElementById("id_cliente").value = respuesta.mensaje;
                        calcularPrecioTotal(valor_domi_g);
                        setTimeout(()=>{
                            $('#exampleModal').modal('show');
                        }, 2500)
                    }
                },
            });

        }else{
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Ingreseel valor del domicilio para este cliente",
                showConfirmButton: false,
                timer: 3000
            });
        }
    }

    function calcularPrecioTotal(valor_domi){
        document.getElementById("total_dinero").value = parseFloat(document.getElementById("total_pagar_tv").value) + parseFloat(valor_domi);
        document.getElementById("total_pagar_con_domi").value =  parseFloat(document.getElementById("total_pagar_tv").value) + parseFloat(valor_domi);
    }

    function guardarVenta(){
        if(document.getElementById("total_dinero").value != ""){
            var datos = {
                total_pagar: total_pagar,
                total_dinero: document.getElementById("total_dinero").value,
                total_fiado: document.getElementById("fiado").value,
                total_vueltos: document.getElementById("vueltos").value,
                imprimir_factura: document.getElementById("imprimir_factura").value,
                id_cliente: document.getElementById("id_cliente").value,
                metodo_pago: document.getElementById("metodo_pago").value,
                total_pagar_con_domi: document.getElementById("total_pagar_con_domi").value,
                productos: productos,
                id_pedido:  id_pedido_sel,
                direccion_cliente: document.getElementById("direccion_cliente").value
            }

            $.ajax({
                url: "/terminarVentaDomicilio",
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify(datos),
                success: function(response) {
                    Swal.fire({
                        position: "center",
                        icon: response.status,
                        title: response.message,
                        showConfirmButton: false,
                        timer: 2000
                    });
                    if(response.status == "success"){
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    }
                },
            });

        }else{
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Ingrese la cantidad de dinero que el cliente pago en en campo (total dinero)",
                showConfirmButton: false,
                timer: 4000
            });
        }
    }
  </script>
@endsection