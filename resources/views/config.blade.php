@extends('maestra')
@section("titulo", "Inicio")
@section('contenido')
<div class="container">
    <br>  
    <p style="font-size: 27px" class="text-center">Configuración de Tienda</p>
    <hr>
    <div class="card bg-light">
        <article class="card-body mx-auto" style="width: 800px;">
            <form  method="POST" action="{{route("editarNegocio")}}">
                @csrf
                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                    </div>
                    <input name="nombre" value="{{$negocio->nombre}}" class="form-control" placeholder="Nombre del negocio" type="text">
                </div>
                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fas fa-barcode"></i> </span>
                    </div>
                    <input name="nit" value="{{$negocio->nit}}" class="form-control" placeholder="NIT" type="text">
                </div>
                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-building"></i> </span>
                    </div>
                    <input name="direccion" value="{{$negocio->direccion}}"  class="form-control" placeholder="Numero de telefono" type="text">
                </div>
                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-phone"></i> </span>
                    </div>
                    <input name="telefono" value="{{$negocio->telefono}}" class="form-control" placeholder="Direccion del negocio" type="number">
                </div>
                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fas fa-map-marker-alt"></i> </span>
                    </div>
                    <input name="barrio" value="{{$negocio->barrio}}" class="form-control" placeholder="Barrio" type="text">
                </div>
                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fas fa-user-tie"></i> </span>
                    </div>
                    <input name="propietario" value="{{$negocio->propietario}}" class="form-control" placeholder="Nombre del Propietario" type="text">
                </div>      
                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fas fa-qrcode"></i> </span>
                    </div>
                    <input name="resolucion" value="{{$negocio->resolucion}}" class="form-control" placeholder="Resolución del negocio" type="text">
                </div>                                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block"> Guardar Cambios </button>
                </div>     
            </form>
        </article>
    </div>
</div> 
@endsection
