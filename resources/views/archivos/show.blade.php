@extends('main')

@section('title', 'Ver - Archivos | Sigi')

@section('content')
<div class="page-title">
  <div class="row">
    <div class="col-12 col-md-6 order-md-1 order-last">
      <h3>Archivos</h3>
      <p class="text-subtitle text-muted">Aquí puedes consultar todos los archivos PDF subidos</p>
    </div>
    <div class="col-12 col-md-6 order-md-2 order-first">
      <nav aria-label="breadcrumb" class='breadcrumb-header'>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">Archivos</a></li>
          <li class="breadcrumb-item" aria-current="page">Ver</li>
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

@if (session('error'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<section class="section">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <span>Listado de Archivos</span>
      <a href="{{ route('archivos.create') }}" class="btn btn-primary btn-sm">+ Nuevo archivo</a>
    </div>
    <div class="card-body">
      <table class="table table-striped" id="archivos-table">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Fecha Subida</th>
            <th>Fecha Archivo</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</section>
@endsection

@section('scripts')
  <script src="{{ asset('data-tables/datatables.min.js') }}"></script>
  <script>
    const context = "{{ url('') }}";
    const urlArchivosPaginate = "{{ route('archivos.paginate') }}";
  </script>
  <script src="{{ asset('js/archivos/show.js') }}"></script>
@endsection
