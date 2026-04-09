@extends('main')

@section('title', 'Exito - Folios | Sigi')

@section('content')
@if (session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<section id="content-types">
  <div class="row">
    <div class="col-xl-6 col-md-6 col-sm-12">
      <div class="card">
        <div class="card-content">
          <div class="card-body">
            <h4 class="card-title">Datos generales</h4>
            <p class="card-text">
              Folio: {{ $folio->folio }}
            </p>
            <p class="card-text">
              Ticket: {{ $folio->ticket }}
            </p>
            <p class="card-text">
              Acrónimo: {{ $folio->acronimo }}
            </p>
            <p class="card-text">
              Hora: {{ $folio->hora }}
            </p>
            <p class="card-text">
              Día/Mes: {{ $folio->dia_mes }}
            </p>
            <p class="card-text">
              Año: {{ $folio->anio }}
            </p>
            <p class="card-text">
              Motivo por el que se otorga el folio: {{ $folio->razon }}
            </p>
            <p class="card-text">
              NIC, NUC o Número de registro provisional en su libro de gobierno: {{ $folio->numero_registro }}
            </p>
            <p class="card-text">
              Detenido: {{ $folio->detenido }}
            </p>
          </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
          <a class="btn btn-primary" href="{{ route('folios.create') }}">Crear Nuevo</a>
          <a class="btn btn-info" href="{{ route('folios.createNext', $folio) }}">Crear Siguiente</a>
          <a class="btn btn-secondary" href="{{ route('folios.detail', $folio) }}">Ver Detalles</a>
          <a class="btn btn-warning" href="{{ route('folios.edit', $folio) }}">Editar Información</a>
        </div>
      </div>
    </div>
  </div>
</section>    
@endsection
