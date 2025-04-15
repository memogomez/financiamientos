@extends('main')

@section('title', 'Ver - Folios | Sigi')

@section('content')
<div class="page-title">
  <div class="row">
    <div class="col-12 col-md-6 order-md-1 order-last">
      <h3>Folios</h3>
      <p class="text-subtitle text-muted">Aquí podras consultar los folios que se han generado</p>
    </div>
    <div class="col-12 col-md-6 order-md-2 order-first">
      <nav aria-label="breadcrumb" class='breadcrumb-header'>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">Folios</a></li>
          <li class="breadcrumb-item" aria-current="page">Ver</li>
        </ol>
      </nav>
    </div>
  </div>
</div>

<section class="section">
  <div class="card">
    <div class="card-header">
      Tabla de Folios
    </div>
    <div class="card-body">
      <table class='table table-striped' id="folios-table">
        <thead>
          <tr>
            <th>Id</th>
            <th>Ticket</th>
            <th>Folio</th>
            <th>Fecha</th>
            <th>Detalle</th>
            <th>Editar</th>
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
    const urlFoliosPaginate = "{{ route('folios.paginate') }}";
  </script>
  <script src="{{ asset('js/folios/show.js') }}"></script>
@endsection
