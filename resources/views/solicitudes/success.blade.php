@extends('main')

@section('title', 'Éxito - Solicitudes | Sigi')

@section('content')
@if (session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<section id="content-types">
  <div class="row">
    <div class="col-xl-8 col-md-10 col-sm-12">
      <div class="card">
        <div class="card-content">
          <div class="card-body">
            <h4 class="card-title">Solicitud registrada</h4>
            <p class="card-text"><strong>ID:</strong> {{ $solicitud->id }}</p>
            <p class="card-text"><strong>Área:</strong> {{ $solicitud->area?->nombre_area ?? '—' }}</p>
            <p class="card-text"><strong>Fecha:</strong> {{ $solicitud->fecha?->format('d/m/Y') }}</p>
            <p class="card-text"><strong>Solicita:</strong> {{ $solicitud->solicita }}</p>
            <p class="card-text"><strong>Dirigido:</strong> {{ $solicitud->dirigido }}</p>
            <p class="card-text"><strong>Monto solicitado:</strong> {{ number_format((float) $solicitud->monto_solicitado, 2, '.', ',') }}</p>
            <p class="card-text"><strong>Comprobación:</strong> {{ $solicitud->comprobacion ? 'Sí' : 'No' }}</p>
            @if($solicitud->observaciones)
              <p class="card-text"><strong>Observaciones:</strong> {{ $solicitud->observaciones }}</p>
            @endif
            <p class="card-text"><strong>Estatus:</strong> {{ $solicitud->estatus }}</p>
            <hr>
            <h5 class="card-title">Oficios</h5>
            @forelse($solicitud->oficios as $oficio)
              <p class="card-text"><strong>Tipo:</strong> {{ $oficio->tipo_oficio }}</p>
              <p class="card-text"><strong>Número:</strong> {{ $oficio->num_oficio ?? '—' }}</p>
              <p class="card-text">
                <strong>Archivo:</strong>
                @if($oficio->url)
                  <a href="{{ asset($oficio->url) }}" target="_blank">Ver archivo</a>
                @else
                  —
                @endif
              </p>
              <hr>
            @empty
              <p class="card-text">No se adjuntaron oficios al crear la solicitud.</p>
            @endforelse
          </div>
        </div>
        <div class="card-footer d-flex flex-wrap gap-2">
          <a class="btn btn-primary" href="{{ route('solicitudes.create') }}">Crear otra solicitud</a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
