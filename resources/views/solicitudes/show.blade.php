@extends('main')

@section('title', 'Ver - Solicitudes | Sigi')

@section('content')
<div class="page-title">
  <div class="row">
    <div class="col-12 col-md-6 order-md-1 order-last">
      <h3>Solicitudes</h3>
      <p class="text-subtitle text-muted">Aquí puedes consultar todas las solicitudes registradas</p>
    </div>
    <div class="col-12 col-md-6 order-md-2 order-first">
      <nav aria-label="breadcrumb" class='breadcrumb-header'>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">Solicitudes</a></li>
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
      <span>Listado de Solicitudes</span>
      <a href="{{ route('solicitudes.create') }}" class="btn btn-primary btn-sm">+ Nueva solicitud</a>
    </div>
    <div class="card-body">
      <table class="table table-striped" id="solicitudes-table">
        <thead>
          <tr>
            <th>Área</th>
            <th>Solicita</th>
            <th>Dirigido</th>
            <th>Fecha</th>
            <th>Comprobación</th>
            <th>Oficios</th>
            <th>Editar</th>
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
    const urlSolicitudesPaginate = "{{ route('solicitudes.paginate') }}";
  </script>
  <script src="{{ asset('js/solicitudes/show.js') }}"></script>
@endsection
