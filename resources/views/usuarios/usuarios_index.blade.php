@extends("maestra")
@section("titulo", "Usuarios")
@section("contenido")
    <div class="row">
        <div class="col-12">
            <br>
            <h1>Usuarios <i class="fa fa-users"></i></h1>
            <a href="{{route("usuarios.create")}}" class="btn btn-success mb-2"><h3>Registrar usuario <i style="font-size: 20px" class="fas fa-plus"></i></h3></a>
            @include("notificacion")
            <hr>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Editar</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($usuarios as $usuario)
                        <tr>
                            <td>{{$usuario->email}}</td>
                            <td>{{$usuario->name}}</td>
                            <td> {{$usuario->tipo == 1 ? "Administrador" : ( $usuario->tipo == 2 ? "Tienda" : "Miscelánea" ) }}</td>
                            <td>
                                @if ($usuario->tipo != 1)
                                    <a class="btn btn-warning" href="{{route("usuarios.edit",[$usuario])}}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <br><br><br><br>
@endsection
