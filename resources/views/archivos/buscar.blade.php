@extends('main')

@section('title', 'Buscar - Archivos | Sigi')

@section('content')
<div class="page-title">
  <div class="row">
    <div class="col-12 col-md-6 order-md-1 order-last">
      <h3>Buscar en archivos</h3>
      <p class="text-subtitle text-muted">Busca texto dentro de los archivos PDF procesados</p>
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
          <div class="col-md-6 col-12">
            <div class="form-group">
              <label for="query">Término de búsqueda</label>
              <input class="form-control" id="query" name="query" type="text" placeholder="Ingresa palabras o frases"
                value="{{ $query }}">
            </div>
          </div>
          <div class="col-md-3 col-12">
            <div class="form-group">
              <label for="modo">Modo</label>
              <select class="form-control" id="modo" name="modo">
                <option value="palabras" @selected($modo === 'palabras')>Palabras (AND)</option>
                <option value="frase" @selected($modo === 'frase')>Frase exacta</option>
              </select>
            </div>
          </div>
          <div class="col-md-3 col-12 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Buscar</button>
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
      </form>
    </div>
  </div>
</section>

@if ($query)
  <section class="section">
    <div class="card">
      <div class="card-header">
        <span>
          Resultados
          @if (count($resultados) > 0)
            ({{ count($resultados) }} resultado@if(count($resultados) != 1)s@endif encontrado@if(count($resultados) != 1)s@endif)
          @endif
        </span>
      </div>
      <div class="card-body">
        @if (count($resultados) == 0)
          <p class="text-muted">No se encontraron resultados para tu búsqueda.</p>
        @else
          <div class="table-responsive">
            <table class="table table-sm table-hover">
              <thead>
                <tr>
                  <th>Archivo</th>
                  <th>Página</th>
                  <th>Fecha</th>
                  <th>Fragmento de texto</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($resultados as $resultado)
                  <tr>
                    <td><strong>{{ $resultado->nombre_archivo }}</strong></td>
                    <td>{{ $resultado->numero_pagina }}</td>
                    <td>{{ \Carbon\Carbon::parse($resultado->fecha_archivo)->format('d/m/Y') }}</td>
                    <td>
                      <small class="text-muted">
                        {{ Str::limit($resultado->texto, 150, '...') }}
                      </small>
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
