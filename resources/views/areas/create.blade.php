@extends('main')

@section('title', 'Crear - Areas | Sigi')

@section('content')HISTOR
<div class="page-title">
  <div class="row">
    <div class="col-12 col-md-6 order-md-1 order-last">
      <h3>Registrar area</h3>
      <p class="text-subtitle text-muted">Completa los datos para guardar una nueva area</p>
    </div>
    <div class="col-12 col-md-6 order-md-2 order-first">
      <nav aria-label="breadcrumb" class='breadcrumb-header'>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('areas.show') }}">Areas</a></li>
          <li class="breadcrumb-item" aria-current="page">Crear</li>
        </ol>
      </nav>
    </div>
  </div>
</div>

@if (session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

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
        <div class="card-content">
          <div class="card-body">
            <form action="{{ route('areas.store') }}" method="post">
              @csrf
              <h4 class="card-title mt-3 mb-3">Datos del area</h4>
              <p class="text-subtitle text-muted">Los campos con <strong>*</strong> son obligatorios</p>
              <div class="row">
                <div class="col-md-8 col-12">
                  <div class="form-group">
                    <label for="nombre_area">Nombre del area</label><strong>*</strong>
                    <input class="form-control" id="nombre_area" name="nombre_area" type="text" maxlength="150"
                      value="{{ old('nombre_area') }}">
                    @error('nombre_area')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                <div class="col-md-4 col-12">
                  <div class="form-group">
                    <label for="estatus">Estatus</label><strong>*</strong>
                    <select class="form-control" name="estatus" id="estatus">
                      <option value="1" @selected(old('estatus', '1') == '1')>Activo</option>
                      <option value="0" @selected(old('estatus') == '0')>Inactivo</option>
                    </select>
                    @error('estatus')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                <div class="col-12 d-flex justify-content-end">
                  <button type="submit" class="btn btn-primary me-1 mb-1">Registrar</button>
                  <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reiniciar</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
