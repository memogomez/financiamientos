@extends('main')

@section('title', 'Reporte por Fechas | Sigi')

@section('content')
<div class="page-title">
  <div class="row">
    <div class="col-12 col-md-6 order-md-1 order-last">
      <h3>Reporte por Fechas</h3>
      <p class="text-subtitle text-muted">Montos por área en un intervalo de fechas</p>
    </div>
    <div class="col-12 col-md-6 order-md-2 order-first">
      <nav aria-label="breadcrumb" class='breadcrumb-header'>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">Reportes</li>
          <li class="breadcrumb-item" aria-current="page">Fechas</li>
        </ol>
      </nav>
    </div>
  </div>
</div>

<div class="row">
  {{-- Filtro --}}
  <div class="col-12">
    <div class="card shadow-sm">
      <div class="card-header d-flex align-items-center gap-2">
        <i data-feather="filter" width="18"></i>
        <strong>Filtrar por intervalo de fechas</strong>
      </div>
      <div class="card-body">
        <form method="GET" action="{{ route('reportes.fechas') }}">
          <div class="row g-3 align-items-end">
            <div class="col-md-4">
              <label for="fecha_inicio" class="form-label">Fecha inicio</label>
              <input type="date" id="fecha_inicio" name="fecha_inicio"
                     class="form-control @error('fecha_inicio') is-invalid @enderror"
                     value="{{ $fechaInicio ?? '' }}" required>
              @error('fecha_inicio')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-4">
              <label for="fecha_fin" class="form-label">Fecha fin</label>
              <input type="date" id="fecha_fin" name="fecha_fin"
                     class="form-control @error('fecha_fin') is-invalid @enderror"
                     value="{{ $fechaFin ?? '' }}" required>
              @error('fecha_fin')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-4 d-flex gap-2">
              <button type="submit" class="btn btn-primary">
                <i data-feather="search" width="16" class="me-1"></i> Generar
              </button>
              @if($resultados->isNotEmpty())
                <a href="{{ route('reportes.exportarExcel', ['fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]) }}"
                   class="btn btn-success">
                  <i data-feather="download" width="16" class="me-1"></i> Exportar Excel
                </a>
              @endif
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Resultados --}}
  @if($fechaInicio && $fechaFin)
  <div class="col-12 mt-3">
    <div class="card shadow-sm">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span>
          <i data-feather="bar-chart-2" width="18" class="me-1"></i>
          <strong>Resultados</strong>
          &mdash;
          <span class="text-muted small">
            Del <strong>{{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }}</strong>
            al <strong>{{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}</strong>
          </span>
        </span>
        @if($resultados->isNotEmpty())
          <span class="badge bg-primary">{{ $resultados->count() }} área(s)</span>
        @endif
      </div>
      <div class="card-body p-0">
        @if($resultados->isEmpty())
          <div class="p-4 text-center text-muted">
            <i data-feather="inbox" width="40" class="mb-2"></i>
            <p class="mb-0">No se encontraron solicitudes en el intervalo seleccionado.</p>
          </div>
        @else
          <div class="table-responsive">
            <table class="table table-hover table-bordered mb-0">
              <thead class="table-dark">
                <tr>
                  <th>#</th>
                  <th>Área</th>
                  <th class="text-center">Solicitudes</th>
                  <th class="text-end">Monto Solicitado</th>
                </tr>
              </thead>
              <tbody>
                @foreach($resultados as $index => $row)
                <tr>
                  <td class="text-muted small">{{ $index + 1 }}</td>
                  <td><strong>{{ $row->nombre_area }}</strong></td>
                  <td class="text-center">
                    <span class="badge bg-secondary">{{ $row->num_solicitudes }}</span>
                  </td>
                  <td class="text-end">${{ number_format($row->total_solicitado, 2) }}</td>
                </tr>
                @endforeach
              </tbody>
              <tfoot class="table-light fw-bold">
                <tr>
                  <td colspan="2" class="text-end">Totales</td>
                  <td class="text-center">
                    <span class="badge bg-primary">{{ $totales['num_solicitudes'] }}</span>
                  </td>
                  <td class="text-end text-primary">${{ number_format($totales['total_solicitado'], 2) }}</td>
                </tr>
              </tfoot>
            </table>
          </div>
        @endif
      </div>
    </div>
  </div>
  @endif
</div>
@endsection

