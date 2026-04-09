@extends('main')

@section('title', 'Ver - Historicos | Sigi')

@section('content')
<div class="page-title">
  <div class="row">
    <div class="col-12 col-md-6 order-md-1 order-last">
      <h3>Historicos</h3>
      <p class="text-subtitle text-muted">Aquí podras consultar los folios ya se habían generado en el Excel</p>
    </div>
    <div class="col-12 col-md-6 order-md-2 order-first">
      <nav aria-label="breadcrumb" class='breadcrumb-header'>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">Historicos</a></li>
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

<section class="section">
  <div class="card">
    <div class="card-header">
      Tabla de Historicos
    </div>
    <div class="card-body">
      <table class='table table-striped' id="historicos-table">
        <thead>
          <tr>
            <th>Consecutivo</th>
            <th>Solicitante</th>
            <th>Agencia MP</th>
            <th>Fecha</th>
            <th>Detalle</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
</section>
@endsection

@section('scripts')
  <script src="{{ asset('data-tables/datatables.min.js') }}"></script>
  <script>
    const context = "{{ url('') }}"
    const urlHistoricosPaginate = "{{ route('historicos.paginate') }}";
  </script>
  <script src="{{ asset('js/historicos/show.js') }}"></script>
@endsection
