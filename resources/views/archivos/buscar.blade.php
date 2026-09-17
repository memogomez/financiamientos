@extends('main')

@section('title', 'Buscar - Archivos | Sigi')

@section('content')
<div class="page-title">
  <div class="row">
    <div class="col-12 col-md-6 order-md-1 order-last">
      <h3>Buscar archivos</h3>
      <p class="text-subtitle text-muted">Filtra los archivos por número de oficio, área o nombre</p>
    </div>
    <div class="col-12 col-md-6 order-md-2 order-first">
      <nav aria-label="breadcrumb" class='breadcrumb-header'>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">Archivos</a></li>
          <li class="breadcrumb-item" aria-current="page">Buscar</li>
        </ol>
      </nav>
    </div>
  </div>
</div>

<section class="section">
  <div class="card">
    <div class="card-body">
      <form method="get" action="{{ route('archivos.buscar') }}" class="mb-4">
        <div class="row">
          <div class="col-md-4 col-12">
            <div class="form-group">
              <label for="numero_oficio">Número de Oficio</label>
              <input class="form-control" id="numero_oficio" name="numero_oficio" type="text" placeholder="Ej. OF-2026-001"
                value="{{ $numeroOficio }}">
            </div>
          </div>
          <div class="col-md-4 col-12">
            <div class="form-group">
              <label for="id_area">Área</label>
              <select class="form-control" id="id_area" name="id_area">
                <option value="">-- Todas las áreas --</option>
                @foreach ($areas as $area)
                  <option value="{{ $area->id }}" @selected($idArea == $area->id)>
                    {{ $area->nombre_area }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-4 col-12">
            <div class="form-group">
              <label for="nombre_archivo">Nombre del Archivo</label>
              <input class="form-control" id="nombre_archivo" name="nombre_archivo" type="text" placeholder="Ej. informe"
                value="{{ $nombreArchivo }}">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 col-12">
            <div class="form-group">
              <label for="fecha_inicio">Fecha desde</label>
              <input class="form-control" id="fecha_inicio" name="fecha_inicio" type="date" value="{{ $fechaInicio }}">
            </div>
          </div>
          <div class="col-md-6 col-12">
            <div class="form-group">
              <label for="fecha_fin">Fecha hasta</label>
              <input class="form-control" id="fecha_fin" name="fecha_fin" type="date" value="{{ $fechaFin }}">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary">Buscar</button>
            <a href="{{ route('archivos.buscar') }}" class="btn btn-secondary">Limpiar</a>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>

@if ($numeroOficio || $nombreArchivo || $idArea || $fechaInicio || $fechaFin)
  <section class="section">
    <div class="card">
      <div class="card-header">
        <span>
          Resultados
          @if (count($resultados) > 0)
            ({{ count($resultados) }} archivo@if(count($resultados) != 1)s@endif encontrado@if(count($resultados) != 1)s@endif)
          @endif
        </span>
      </div>
      <div class="card-body">
        @if (count($resultados) == 0)
          <p class="text-muted">No se encontraron archivos con los criterios especificados.</p>
        @else
          <div class="table-responsive">
            <table class="table table-sm table-hover">
              <thead>
                <tr>
                  <th>Nombre</th>
                  <th>Número de Oficio</th>
                  <th>Área</th>
                  <th>Fecha</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($resultados as $archivo)
                  <tr>
                    <td><strong>{{ $archivo->nombre_archivo }}</strong></td>
                    <td>{{ $archivo->numero_oficio }}</td>
                    <td>{{ $archivo->nombre_area ?? 'Sin área' }}</td>
                    <td>{{ \Carbon\Carbon::parse($archivo->fecha_archivo)->format('d/m/Y') }}</td>
                    <td>
                      <a href="{{ route('archivos.descargar', $archivo->archivo_fisico) }}" class="btn btn-sm btn-info">Descargar</a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>
  </section>
@endif
@endsection
