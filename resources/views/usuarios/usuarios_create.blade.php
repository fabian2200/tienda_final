@extends("maestra")
@section("titulo", "Agregar usuario")
@section("contenido")
<br>
    <div class="row" style="padding: 30px; font-size: 20px">
        <div class="col-12">
            <h1>Agregar usuario</h1>
            <form method="POST" action="{{route("usuarios.store")}}">
                @csrf
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="label">Nombre</label>
                            <input required autocomplete="off" name="name" class="form-control"
                                   type="text" placeholder="Nombre">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="label">Tipo de vendedor</label>
                            <select class="form-control" name="tipo" id="tipo">
                                @foreach ($tipos as $item)
                                    <option value="{{$item->tipo}}">{{$item->tipo_desc}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="label">Usuario</label>
                            <input required autocomplete="off" name="email" class="form-control"
                                   type="text" placeholder="Correo electrónico">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="label">Contraseña</label>
                            <input required autocomplete="off" name="password" class="form-control"
                                   type="password" placeholder="Contraseña">
                        </div>
                    </div>
                </div>
                <label>Selecione una impresora</label>
                <div class="row">
                    @foreach ($impresoras as $item)
                        <div class="col-lg-3" style="margin-bottom: 20px; height: 120px">
                            <input required type="radio" id="control_0{{$loop->index}}" name="ip_impresora" value="{{$item->ip}}" >
                            <label style="padding-top: 0px !important" class="lradio" for="control_0{{$loop->index}}">
                                <img src="/img/impresora.png" style="width: 50px" alt="">
                                <h4>{{$item->nombre}}</h4>
                            </label>
                        </div>
                    @endforeach
                </div>
                @include("notificacion")
                <button style="font-size: 30px" class="btn btn-success">Guardar</button>
                <a style="font-size: 30px" class="btn btn-primary" href="{{route("usuarios.index")}}">Volver atrás</a>
            </form>
        </div>
    </div>
@endsection
