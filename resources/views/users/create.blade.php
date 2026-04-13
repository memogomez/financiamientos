@extends('main')

@section('title', 'Crear Usuario | Sigi')

@section('content')
<div class="page-title">
  <div class="row">
    <div class="col-12 col-md-6 order-md-1 order-last">
      <h3>Nuevo usuario</h3>
      <p class="text-subtitle text-muted">Completa los datos para crear un usuario</p>
    </div>
    <div class="col-12 col-md-6 order-md-2 order-first">
      <nav aria-label="breadcrumb" class='breadcrumb-header'>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuarios</a></li>
          <li class="breadcrumb-item" aria-current="page">Crear</li>
        </ol>
      </nav>
    </div>
  </div>
</div>

@if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<section id="multiple-column-form">
  <div class="row match-height">
    <div class="col-12">
      <div class="card">
        <div class="card-header">Datos del usuario</div>
        <div class="card-body">
          <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <p class="text-subtitle text-muted">Los campos con <strong>*</strong> son obligatorios</p>
            <div class="row">

              <div class="col-md-4 col-12">
                <div class="form-group">
                  <label for="id_empleado">No. Empleado</label>
                  <input class="form-control @error('id_empleado') is-invalid @enderror"
                    id="id_empleado" name="id_empleado" type="text" maxlength="50"
                    placeholder="Número de empleado" value="{{ old('id_empleado') }}">
                  @error('id_empleado')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
              </div>

              <div class="col-md-4 col-12">
                <div class="form-group">
                  <label for="usuario">Usuario</label><strong>*</strong>
                  <input class="form-control @error('usuario') is-invalid @enderror"
                    id="usuario" name="usuario" type="text" maxlength="100"
                    placeholder="Nombre de usuario" value="{{ old('usuario') }}">
                  @error('usuario')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
              </div>

              <div class="col-md-4 col-12">
                <div class="form-group">
                  <label for="rol">Rol</label><strong>*</strong>
                  <select class="form-control @error('rol') is-invalid @enderror" id="rol" name="rol">
                    <option value="">-- Selecciona un rol --</option>
                    <option value="admin" @selected(old('rol') === 'admin')>Administrador</option>
                    <option value="usuario" @selected(old('rol') === 'usuario')>Usuario</option>
                  </select>
                  @error('rol')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
              </div>

              <div class="col-md-6 col-12">
                <div class="form-group">
                  <label for="name_user">Nombre completo</label><strong>*</strong>
                  <input class="form-control @error('name_user') is-invalid @enderror"
                    id="name_user" name="name_user" type="text" maxlength="255"
                    placeholder="Nombre y apellidos" value="{{ old('name_user') }}">
                  @error('name_user')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
              </div>

              <div class="col-md-6 col-12">
                <div class="form-group">
                  <label for="email">Correo electrónico</label><strong>*</strong>
                  <input class="form-control @error('email') is-invalid @enderror"
                    id="email" name="email" type="email" maxlength="255"
                    placeholder="correo@ejemplo.com" value="{{ old('email') }}">
                  @error('email')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
              </div>

              <div class="col-md-6 col-12">
                <div class="form-group">
                  <label for="password">Contraseña</label><strong>*</strong>
                  <input class="form-control @error('password') is-invalid @enderror"
                    id="password" name="password" type="password" placeholder="Mínimo 6 caracteres">
                  @error('password')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
              </div>

              <div class="col-md-6 col-12">
                <div class="form-group">
                  <label for="password_confirmation">Confirmar contraseña</label><strong>*</strong>
                  <input class="form-control"
                    id="password_confirmation" name="password_confirmation" type="password"
                    placeholder="Repite la contraseña">
                </div>
              </div>

              <div class="col-12 d-flex justify-content-end gap-2 mt-2">
                <a href="{{ route('users.index') }}" class="btn btn-light-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar usuario</button>
              </div>

            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
