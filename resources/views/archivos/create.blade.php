@extends('main')

@section('title', 'Crear - Archivos | Sigi')

@section('content')
<div class="page-title">
  <div class="row">
    <div class="col-12 col-md-6 order-md-1 order-last">
      <h3>Subir archivo PDF</h3>
      <p class="text-subtitle text-muted">Selecciona un archivo PDF para cargar y procesar</p>
    </div>
    <div class="col-12 col-md-6 order-md-2 order-first">
      <nav aria-label="breadcrumb" class='breadcrumb-header'>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">Archivos</a></li>
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

<section>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-content">
          <div class="card-body">
            <form action="{{ route('archivos.store') }}" method="post" enctype="multipart/form-data">
              @csrf
              <h4 class="card-title mt-3 mb-3">Datos del archivo</h4>
              <p class="text-subtitle text-muted">Los campos con <strong>*</strong> son obligatorios</p>
              <div class="row">

                <div class="col-12">
                  <div class="form-group">
                    <label for="file_input">Archivo PDF</label><strong>*</strong>
                    <input class="form-control" id="file_input" name="file_input" type="file" accept=".pdf" required>
                    <small class="text-muted">Máximo 20 MB</small>
                    @error('file_input')
                      <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <div class="col-12">
                  <div class="form-group">
                    <label for="fecha_archivo">Fecha del archivo</label><strong>*</strong>
                    <input class="form-control" id="fecha_archivo" name="fecha_archivo" type="date"
                      value="{{ old('fecha_archivo', now()->format('Y-m-d')) }}" required>
                    @error('fecha_archivo')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                </div>

              </div>

              <div class="row">
                <div class="col-12 d-flex justify-content-between">
                  <a href="{{ route('archivos.show') }}" class="btn btn-secondary">Cancelar</a>
                  <button type="submit" class="btn btn-primary">Subir y procesar</button>
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
