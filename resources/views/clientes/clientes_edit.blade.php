@extends("maestra")
@section("titulo", "Editar cliente")
@section("contenido")
    <div class="row" style="padding: 20px">
        <div class="col-12">
            <h1>Editar cliente</h1>
            <form method="POST" action="{{route("clientes.update", [$cliente])}}">
                @method("PUT")
                @csrf
                <div class="form-group">
                    <label class="label">Nombre</label>
                    <input required value="{{$cliente->nombre}}" autocomplete="off" name="nombre" class="form-control"
                           type="text" placeholder="Nombre">
                </div>
                <div class="form-group">
                    <label class="label">Teléfono</label>
                    <input required value="{{$cliente->telefono}}" autocomplete="off" name="telefono"
                           class="form-control"
                           type="number" placeholder="Teléfono">
                </div>
                <div class="form-group">
                    <label class="label">Valor del domicilio</label>
                    <input required value="{{$cliente->valor_domi}}" autocomplete="off" name="valor_domi" class="form-control"
                           type="text" placeholder="Ej: $500">
                </div>
                @include("notificacion")
                <button class="btn btn-success">Guardar</button>
                <a class="btn btn-primary" href="{{route("clientes.index")}}">Volver</a>
            </form>
        </div>
    </div>
@endsection
