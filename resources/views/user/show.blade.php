@extends('main')

@section('title', 'Ver - Usuarios | Sigi')

@section('content')
<div class="page-title">
  <div class="row">
    <div class="col-12 col-md-6 order-md-1 order-last">
      <h3>Usuario</h3>
      <p class="text-subtitle text-muted">Aquí puedes ver la información general de tu usuario</p>
    </div>
    <div class="col-12 col-md-6 order-md-2 order-first">
      <nav aria-label="breadcrumb" class='breadcrumb-header'>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">Usuarios</a></li>
          <li class="breadcrumb-item" aria-current="page">Ver</li>
        </ol>
      </nav>
    </div>
  </div>
</div>

<section id="basic-vertical-layouts">
  <div class="row match-height">
    <div class="col-md-6 col-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Datos generales</h4>
        </div>
        <div class="card-content">
          <div class="card-body">
            <div class="form-body">
              <div class="row">
                <div class="col-12">
                  <div class="form-group has-icon-left">
                    <label for="first-name-icon">Nombre</label>
                    <div class="position-relative">
                      <input type="text" class="form-control" id="first-name-icon"
                          value="{{ $user->nombre_usuario }}" disabled>
                      <div class="form-control-icon">
                        <i data-feather="users"></i>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group has-icon-left">
                    <label for="first-name-icon">Usuario</label>
                    <div class="position-relative">
                      <input type="text" class="form-control" id="first-name-icon"
                          value="{{ $user->usuario }}" disabled>
                      <div class="form-control-icon">
                        <i data-feather="user"></i>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group has-icon-left">
                    <label for="first-name-icon">Rol</label>
                    <div class="position-relative">
                      <input type="text" class="form-control" id="first-name-icon"
                        value="{{ $user->rol }}" disabled>
                      <div class="form-control-icon">
                        <i data-feather="user"></i>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group has-icon-left">
                    <label for="email-id-icon">Email</label>
                    <div class="position-relative">
                      <input type="text" class="form-control" id="email-id-icon"
                        value="{{ $user->email }}" disabled>
                      <div class="form-control-icon">
                        <i data-feather="mail"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@section('scripts')

@endsection
