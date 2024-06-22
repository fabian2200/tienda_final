<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="{{env("APP_NAME")}}">
    <meta name="author" content="Parzibyte">
    <title>@yield("titulo") - {{env("APP_NAME")}}</title>
    <link href="{{url("/css/bootstrap.min.css")}}" rel="stylesheet">
    <link href="{{url("/css/all.min.css")}}" rel="stylesheet">
    <link href="{{url("/css/jquery.dataTables.min.css")}}" rel="stylesheet">
    <link href="{{url("/select_search/select2.min.css")}}" rel="stylesheet">
    <link href="{{url("/select_mio/bootstrap-select.min.css")}}" rel="stylesheet">
    <script src="{{url("/css/sweetalert.js")}}"></script>
    <script src="{{url("/js/jquery-3.7.0.js")}}"></script>
    <script src="{{url("/js/jquery.dataTables.min.js")}}"></script>
    <script src="{{url("/select_mio/bootstrap-select.min.js")}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
   
    <!-- DataTables Buttons CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css" />

    <!-- pdfMake Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>

    <!-- DataTables Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
    
    <script src="{{url("/select_search/select2.min.js")}}"></script>
    <style>
        body {
            padding-top: 57px;
        }

        .imagen_producto {
            font-size: 20px;
            height: 200px;
            width: 200px;
            background-color: #c2e6f5;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            border-radius: 20px;
            margin-top: 30px;
            cursor: pointer;
        }

        input[type="radio"] {
            display: none;
            &:not(:disabled) ~ label {
                cursor: pointer;
                display: flex;
                flex-direction: column;
            }
            &:disabled ~ label {
                color: hsla(150, 5%, 75%, 1);
                border-color: hsla(150, 5%, 75%, 1);
                box-shadow: none;
                cursor: not-allowed;
            }
        }

        input[type="checkbox"] {
            display: none;
            &:not(:disabled) ~ label {
                cursor: pointer;
            }
            &:disabled ~ label {
                color: hsla(150, 5%, 75%, 1);
                border-color: hsla(150, 5%, 75%, 1);
                box-shadow: none;
                cursor: not-allowed;
            }
        }

        label.lradio {
            height: 100%;
            display: block;
            background: white;
            border: 2px solid hsla(150, 75%, 50%, 1);
            border-radius: 20px;
            padding: 0.5rem;
            margin-bottom: 1rem;
            text-align: center;
            box-shadow: 0px 3px 10px -2px hsla(150, 5%, 65%, 0.5);
            position: relative;
            padding-top: 19px;
            font-size: 14px;
            font-weight: bold;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .lradio p {
            margin-bottom: 0px !important;
        }

        input[type="radio"]:checked + label {
            background: hsla(150, 75%, 50%, 1);
            color: hsla(215, 0%, 100%, 1);
            box-shadow: 0px 0px 20px hsla(150, 100%, 50%, 0.75);
        }

        input[type="checkbox"]:checked + label {
            background: hsla(150, 75%, 50%, 1);
            color: hsla(215, 0%, 100%, 1);
            box-shadow: 0px 0px 20px hsla(150, 100%, 50%, 0.75);
            &::after {
                color: hsla(215, 5%, 25%, 1);
                font-family: FontAwesome;
                border: 2px solid hsla(150, 75%, 45%, 1);
                content: "\f00c";
                font-size: 24px;
                position: absolute;
                top: -25px;
                left: 50%;
                transform: translateX(-50%);
                height: 50px;
                width: 50px;
                line-height: 50px;
                text-align: center;
                border-radius: 50%;
                background: white;
                box-shadow: 0px 2px 5px -2px hsla(0, 0%, 0%, 0.25);
            }
        }

        .btn-morado {
            color: #fff;
            background-color: #713bdf !important;
            border-color: #6202ff !important;
        }

        .btn-azul {
            color: #fff;
            background-color: #285ab6 !important;
            border-color: #173b7e !important;
        }

        .btn-azul:hover {
            color: #fff;
            background-color: #173b7e !important;
        }

        .btn-rosado {
            background-color: #ff4dc8ed !important;
            border-color: #ee0da7ed !important;
            color: #fff
        }

        .btn-rosado:hover {
            background-color: #ee0da7ed !important;
            color: #fff
        }

        .btn-negro {
            background-color: #272727ed !important;
            border-color: #000000ed !important;
            color: #fff
        }

        .btn-negro:hover {
            background-color: #000000ed !important;
            color: #fff
        }

        .btn-morado:hover{
            background-color: #5b17e2 !important;
            color: white 
        }
           
        .btn-gris {
            color: #222;
            background-color: #c9c4c4;
            border-color: #9c9595;
        }

        .btn-gris:hover {
            color: #222;
            background-color: #acabab;
            border-color: #9c9595;
        }

        table {
            font-size: 20px !important
        }

        #tabla_productos_vender td, #tabla_productos_vender th {
            padding: 5px;
            font-size: 14px !important;
        }

        .card_ventas {
            border-radius: 15px; 
            padding: 20px; 
            height: 130px; 
            color: white;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.75);
        }

        .btn_modal {
            width: 25% !important;
            height: 70px;
            font-size: 24px !important;
        }


        .boton_tabla {
            width: 20px;
            height: 24px;
            border: none;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .bad {
            width: 27px;
            height: 29px;
            border-radius: 50%;
            background-color: red;
            color: white;
            position: absolute;
            display: flex;
            justify-content: center;
            align-items: center;
            top: 3px;
            right: 0px;
        }

        .btn:not(.disabled):hover {
            margin-top: 0px !important;
        }

        .bs-searchbox ul {
            display: block !important;
        }

        .buttons-excel {
            background-color: green !important;
            color: #fff !important;
            font-size: 15px !important;
        }

        #tabla_codigos_cp tr {
            cursor: pointer;
        }

        #tabla_codigos_cp tr:hover {
            background-color: #18b146;
            color: #fff;
        }

        #tabla_codigos_ep tr {
            cursor: pointer;
        }

        #tabla_codigos_ep tr:hover {
            background-color: #18b146;
            color: #fff;
        }

        #spinner_venta {
            z-index: 999999;
            position: fixed;
            width: 100%;
            top: 0px;
            height: 100vh;
            left: 0px;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            background-color: #000000a8;
            flex-direction: column !important;
            color: #fff;
        }

        .num-key-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100px;
        }
        .num-key {
            font-size: 1.5rem;
            text-align: center;
            cursor: pointer;
            user-select: none;
            background-color: #535353; /* Color azul */
            color: white; /* Letras blancas */
            border-radius: 20px; /* Bordes redondeados */
            width: 80px;
            height: 80px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .num-key:hover {
            background-color: #0056b3; /* Color azul más oscuro al pasar el cursor */
        }

        .page-link {
            font-size: 16px !important;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <button class="navbar-toggler" type="button" data-toggle="collapse"
            id="botonMenu" aria-label="Mostrar u ocultar menú">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="menu">
        <ul class="navbar-nav mr-auto">
            @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">
                        Registro
                    </a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link btn btn-info" style="color: white !important;" href="{{route("home")}}">Inicio&nbsp;<i class="fa fa-home"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route("vender.index")}}">Vender&nbsp;<i class="fa fa-cart-plus"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route("productos.index")}}">Productos&nbsp;<i class="fa fa-box"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route("clientes.index")}}">Clientes&nbsp;<i class="fa fa-users"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route("compras.index")}}">Compras&nbsp;<i class="fas fa-money-bill-alt"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route("ventas.ventasPorFecha", ['fecha1' => date('Y-m-').'01', 'fecha2' => date('Y-m-d') ])}}">Ventas por mes &nbsp;<i class="fas fa-money-bill-alt"></i></a>
                </li>
                <li class="nav-item" style="position: relative">
                    <a class="nav-link" href="{{route("ventas.domicilios")}}">Domicilios &nbsp;<i class="fas fa-truck"></i></a>
                    <span id="bad" class="bad">0</span>
                </li>
            @endguest
        </ul>
        <ul class="navbar-nav ml-auto">
            @auth
                <li style="margin-right: 15px" class="nav-item">
                    <a style="color: #fff" href="{{route("productos.alert")}}" class="nav-link btn btn-warning">
                       Productos en alerta <i class="fas fa-exclamation-triangle"></i>
                    </a>
                </li>

                <li style="margin-right: 15px" class="nav-item">
                    <a style="color: #fff" href="{{route("logout")}}" class="nav-link btn btn-danger">
                        Salir ({{ Auth::user()->name }}) <i class="fas fa-power-off"></i>
                    </a>
                </li>

                <li class="nav-item">
                    <a style="color: #fff" href="{{route("config")}}" class="nav-link btn btn-dark">
                      <i class="fas fa-cogs"></i>
                    </a>
                </li>
            @endauth
           
        </ul>
    </div>
</nav>
<script type="text/javascript">
    var vueltas = 0;
    var numero_anterior = 0;
    document.addEventListener("DOMContentLoaded", () => {
        const menu = document.querySelector("#menu"),
            botonMenu = document.querySelector("#botonMenu");
        if (menu) {
            botonMenu.addEventListener("click", () => menu.classList.toggle("show"));
        }
    });

    obtenerDomiciliosP();
    setInterval(obtenerDomiciliosP, 10000);
    
    function obtenerDomiciliosP(){
        vueltas += 1;
        $.ajax({
            url: 'http://192.168.1.76/tienda2/ver_domicilios.php',
            type: 'GET',
            success: function(response) {
                response = JSON.parse(response);
                if(vueltas > 1){
                    if(response.length > numero_anterior){
                        var audio = new Audio('/sounds/sound.mp3');
                        audio.play();
                        numero_anterior = response.length;
                    }
                }else{
                    numero_anterior = response.length;
                }
                document.getElementById("bad").innerHTML = response.length;
            }
        });
        return false;
    }
    

</script>
<main class="container-fluid">
    @yield("contenido")
</main>
<footer class="px-2 py-2 fixed-bottom bg-dark">
    <span class="text-muted">Punto de venta en Laravel
        <i class="fa fa-code text-white"></i>
        con
        <i class="fa fa-heart" style="color: #ff2b56;"></i>
        por
        <a class="text-white" href="">Fabian Quintero</a>
        &nbsp;|&nbsp;
        <a target="_blank" class="text-white" href="">
            <i class="fab fa-github"></i>
        </a>
    </span>
</footer>
</body>
</html>
