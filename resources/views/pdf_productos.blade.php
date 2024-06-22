<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        h3 {
            line-height: 0.5 !important;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #000; 
        }

        th, td {
            border: 1px solid #000;
            text-align: left; 
            padding: 8px;
            font-size: 11px;
        }
    </style>
</head>
<body style="text-align: center">
    <br>
    <h2 style="color: black">Inventario de productos</h2>
    <h3>Provisiones Carlos Andres</h3>
    <h3>NIT 12435619</h3>
    <h3>CRA 15 #13B Bis - 62</h3>
    <h3>Brr. Alfonso Lopez</h3>
    
    <br>
    <h2>Lista de productos</h2>
    <hr>
    <table id="tabla_productos" class="table table-bordered">
        <thead style="background-color: #91baee">
            <tr>
                <th>Código de barras</th>
                <th>Descripción</th>
                <th>Precio de venta</th>
                <th>Utilidad</th>
                <th>Existencia</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
        @foreach($productos as $producto)
            <tr>
                <td>{{$producto->codigo_barras}}</td>
                <td>{{$producto->descripcion}}</td>
                <td>${{number_format($producto->precio_venta,2)}}</td>
                <td>${{number_format($producto->precio_venta - $producto->precio_compra, 2)}}</td>
                <td class="text-center">
                    {{$producto->existencia}} <strong>{{ $producto->unidad_medida }}</strong>
                </td>
                <td style="text-align: center">
                    ${{number_format($producto->total, 2)}}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <br>
    <div style="background-color: #91baee; padding: 20px">
        <h1>Total: ${{ number_format($total, 2) }}</h1>
    </div>
</body>
</html>