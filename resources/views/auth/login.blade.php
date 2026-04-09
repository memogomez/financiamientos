@extends('app')

@section('title', 'Login - Folios | Sigi')

@section('content')
<div id="auth">
  <div class="container">
    <div class="row">
      <div class="col-md-5 col-sm-12 mx-auto">
        <div class="card pt-4">
          <div class="card-body">
            <div class="text-center mb-5">
              <img src="{{ asset('images/fgjem.jpg') }}" height="140" class='mb-4'>
              <h3>Iniciar Sesión</h3>
              <p>Inicia sesión para poder continuar.</p>
            </div>
            <form action="{{ route('auth.login') }}" method="POST">
              @csrf
              <div class="form-group position-relative has-icon-left">
                <label for="username">Usuario</label>
                <div class="position-relative">
                  <input type="text" class="form-control" id="usuario" name="usuario" value="{{ old('usuario') }}">
                  <div class="form-control-icon">
                    <i data-feather="user"></i>
                  </div>
                </div>
                @error('usuario')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              <div class="form-group position-relative has-icon-left">
                <div class="clearfix">
                  <label for="password">Contraseña</label>
                </div>
                <div class="position-relative">
                  <input type="password" class="form-control" id="password" name="password">
                  <div class="form-control-icon">
                    <i data-feather="lock"></i>
                  </div>
                </div>
                @error('password')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              <div class="clearfix">
                <button class="btn btn-primary float-end">Iniciar Sesión</button>
              </div>
            </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
