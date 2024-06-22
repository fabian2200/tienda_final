@extends('maestra')
@section("titulo")
    Login
@endsection
@section('contenido')
    <div class="row justify-content-center" style="background-image: url('/img/frutas.png'); height: 88vh; background-size: 100% 134%">
        <div class="col-md-8" style="display: flex; justify-content: center; align-items: center;">
            <div class="card" style="width: 500px">
                <div class="card-header">Login</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login-usuario') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email"
                                   class="col-md-4 col-form-label text-md-right">Correo electrónico</label>

                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control @error('email') is-invalid @enderror"
                                       name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password"
                                   class="col-md-4 col-form-label text-md-right">Contraseña</label>

                            <div class="col-md-6">
                                <input id="password" type="password"
                                       class="form-control @error('password') is-invalid @enderror" name="password"
                                       required autocomplete="current-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        @if ($errors->has('mensaje'))
                            <hr>
                            <strong style="width: 100%;text-align: center;color: red;display: block;background-color: #ffbdbd;padding: 10px;">No se encontro un usuario con esas credenciales</strong>
                            <br>
                        @endif

                        <div class="form-group row mb-0">
                            <div class="col-md-12 text-center">
                                <button style="width: 74%" type="submit" class="btn btn-primary">
                                    Iniciar Sesión
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
