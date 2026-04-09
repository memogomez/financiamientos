@extends('main')

@section('title', 'Editar - Solicitudes | Sigi')

@section('styles')
<style>
  .solicitud-readonly {
    position: relative;
    pointer-events: none;
    user-select: none;
    opacity: 0.72;
  }
  .solicitud-readonly::after {
    content: '';
    position: absolute;
    inset: 0;
    background: transparent;
    cursor: not-allowed;
    z-index: 1;
  }
</style>
@endsection

@section('content')
<div class="page-title">
  <div class="row">
    <div class="col-12 col-md-6 order-md-1 order-last">
      <h3>Editar solicitud #{{ $solicitud->id }}</h3>
      <p class="text-subtitle text-muted">Modifica los datos o agrega los oficios pendientes</p>
    </div>
    <div class="col-12 col-md-6 order-md-2 order-first">
      <nav aria-label="breadcrumb" class='breadcrumb-header'>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('solicitudes.show') }}">Solicitudes</a></li>
          <li class="breadcrumb-item" aria-current="page">Editar</li>
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

<form action="{{ route('solicitudes.update', $solicitud) }}" method="post" enctype="multipart/form-data">
  @csrf
  @method('PUT')

  @php $soloOficios = request('modo') === 'oficios'; @endphp

  {{-- ===================== DATOS DE LA SOLICITUD ===================== --}}
  <section id="multiple-column-form">
    <div class="row match-height">
      <div class="col-12">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>Datos de la solicitud</span>
            @if($soloOficios)
              <span class="badge bg-secondary">Solo lectura</span>
            @endif
          </div>
          <div class="card-body @if($soloOficios) solicitud-readonly @endif">
            @if(!$soloOficios)
              <p class="text-subtitle text-muted">Los campos con <strong>*</strong> son obligatorios</p>
            @endif
            <div class="row">

              {{-- Fila 1: Área (ancha) + Fecha (compacta) --}}
              <div class="col-md-8 col-12">
                <div class="form-group">
                  <label for="id_area">Área</label>@if(!$soloOficios)<strong>*</strong>@endif
                  <select class="form-control" name="id_area" id="id_area"
                    @if($soloOficios) disabled @endif>
                    <option value="">-- Selecciona un área --</option>
                    @foreach ($areas as $area)
                      <option value="{{ $area->id }}" @selected(old('id_area', $solicitud->id_area) == $area->id)>
                        {{ $area->nombre_area }}
                      </option>
                    @endforeach
                  </select>
                  @if($soloOficios)
                    <input type="hidden" name="id_area" value="{{ old('id_area', $solicitud->id_area) }}">
                  @endif
                  @error('id_area')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
              </div>
              <div class="col-md-4 col-12">
                <div class="form-group">
                  <label for="fecha">Fecha</label>@if(!$soloOficios)<strong>*</strong>@endif
                  <input class="form-control" id="fecha" name="fecha" type="date"
                    value="{{ old('fecha', $solicitud->fecha?->format('Y-m-d')) }}"
                    @if($soloOficios) readonly @endif>
                  @error('fecha')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
              </div>

              {{-- Fila 2: Solicita y Dirigido a partes iguales --}}
              <div class="col-md-6 col-12">
                <div class="form-group">
                  <label for="solicita">Solicita</label>@if(!$soloOficios)<strong>*</strong>@endif
                  <input class="form-control" id="solicita" name="solicita" type="text" maxlength="255"
                    placeholder="Nombre de quien solicita"
                    value="{{ old('solicita', $solicitud->solicita) }}"
                    @if($soloOficios) readonly @endif>
                  @error('solicita')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
              </div>
              <div class="col-md-6 col-12">
                <div class="form-group">
                  <label for="dirigido">Dirigido a</label>@if(!$soloOficios)<strong>*</strong>@endif
                  <input class="form-control" id="dirigido" name="dirigido" type="text" maxlength="255"
                    placeholder="A quien va dirigido"
                    value="{{ old('dirigido', $solicitud->dirigido) }}"
                    @if($soloOficios) readonly @endif>
                  @error('dirigido')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
              </div>

              {{-- Fila 3: Montos + toggle Comprobación en la misma línea --}}
              <div class="col-md-3 col-sm-6 col-12">
                <div class="form-group">
                  <label for="monto_solicitado">Monto solicitado</label>@if(!$soloOficios)<strong>*</strong>@endif
                  <input class="form-control" id="monto_solicitado" name="monto_solicitado" type="number"
                    step="0.01" min="0" placeholder="0.00"
                    value="{{ old('monto_solicitado', $solicitud->monto_solicitado) }}"
                    @if($soloOficios) readonly @endif>
                  @error('monto_solicitado')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
              </div>
              <div class="col-md-3 col-sm-6 col-12">
                <div class="form-group">
                  <label for="monto_total">Monto aprobado</label>@if(!$soloOficios)<strong>*</strong>@endif
                  <input class="form-control" id="monto_total" name="monto_total" type="number"
                    step="0.01" min="0" placeholder="0.00"
                    value="{{ old('monto_total', $solicitud->monto_total) }}"
                    @if($soloOficios) readonly @endif>
                  @error('monto_total')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
              </div>
              <div class="col-md-3 col-sm-6 col-12">
                <div class="form-group">
                  <label for="total">Total</label>@if(!$soloOficios)<strong>*</strong>@endif
                  <input class="form-control" id="total" name="total" type="number"
                    step="0.01" min="0" placeholder="0.00"
                    value="{{ old('total', $solicitud->total) }}"
                    @if($soloOficios) readonly @endif>
                  @error('total')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
              </div>
              <div class="col-md-3 col-sm-6 col-12">
                <div class="form-group">
                  <label class="d-block">Comprobación</label>
                  @if($soloOficios)
                    <input type="hidden" name="comprobacion" value="{{ old('comprobacion', $solicitud->comprobacion) ? '1' : '0' }}">
                    <div class="form-check form-switch mt-2">
                      <input class="form-check-input" id="comprobacion" type="checkbox"
                        @checked(old('comprobacion', $solicitud->comprobacion)) disabled>
                      <label class="form-check-label" for="comprobacion">Activar / Desactivar</label>
                    </div>
                  @else
                    <input type="hidden" name="comprobacion" value="0">
                    <div class="form-check form-switch mt-2">
                      <input class="form-check-input" id="comprobacion" name="comprobacion" type="checkbox" value="1"
                        @checked(old('comprobacion', $solicitud->comprobacion))>
                      <label class="form-check-label" for="comprobacion">Activar / Desactivar</label>
                    </div>
                  @endif
                  @error('comprobacion')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- ===================== OFICIOS ===================== --}}
  @php
    $tiposOficio = [
      'oficio_inicio'        => 'Oficio inicio',
      'oficio_oficial_mayor' => 'Oficio oficial mayor',
      'oficio_fiscal'        => 'Oficio fiscal',
    ];
  @endphp

  <section id="oficios-section" class="mt-2">
    <div class="row">
      @foreach ($tiposOficio as $tipo => $etiqueta)
        @php $oficio = $oficiosPorTipo->get($tipo); @endphp
        <div class="col-lg-4 col-md-6 col-12 mb-3">
          <div class="card h-100 border {{ $oficio ? 'border-success' : 'border-warning' }}">
            <div class="card-header d-flex justify-content-between align-items-center
              {{ $oficio ? 'bg-success text-white' : 'bg-warning text-dark' }}">
              <span>{{ $etiqueta }}</span>
              @if($oficio)
                <span class="badge bg-white text-success">Registrado</span>
              @else
                <span class="badge bg-white text-warning">Pendiente</span>
              @endif
            </div>
            <div class="card-body">
              {{-- Número de oficio --}}
              <div class="form-group">
                <label for="num_{{ $tipo }}">
                  Número de oficio
                  @if($oficio) <small class="text-muted">(actual: {{ $oficio->num_oficio ?: '—' }})</small> @endif
                </label>
                <input class="form-control" id="num_{{ $tipo }}"
                  name="num_oficio_{{ $tipo === 'oficio_inicio' ? 'inicio' : ($tipo === 'oficio_oficial_mayor' ? 'oficial_mayor' : 'fiscal') }}"
                  type="text" maxlength="100"
                  value="{{ old('num_oficio_' . ($tipo === 'oficio_inicio' ? 'inicio' : ($tipo === 'oficio_oficial_mayor' ? 'oficial_mayor' : 'fiscal')), $oficio?->num_oficio) }}"
                  placeholder="{{ $oficio ? 'Actualizar número' : 'Agregar número' }}">
                @error('num_oficio_' . ($tipo === 'oficio_inicio' ? 'inicio' : ($tipo === 'oficio_oficial_mayor' ? 'oficial_mayor' : 'fiscal')))
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>

              {{-- Archivo actual --}}
              @if($oficio && $oficio->url)
                <div class="mb-2">
                  <small class="text-muted">Archivo actual:</small><br>
                  <a href="{{ asset($oficio->url) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-1">
                    Ver archivo
                  </a>
                </div>
              @endif

              {{-- Subir / reemplazar archivo --}}
              <div class="form-group mb-0">
                <label for="archivo_{{ $tipo }}">
                  {{ $oficio && $oficio->url ? 'Reemplazar archivo' : 'Subir archivo' }}
                  <small class="text-muted">(pdf, jpg, png — máx. 5MB)</small>
                </label>
                <input class="form-control" id="archivo_{{ $tipo }}"
                  name="archivo_oficio_{{ $tipo === 'oficio_inicio' ? 'inicio' : ($tipo === 'oficio_oficial_mayor' ? 'oficial_mayor' : 'fiscal') }}"
                  type="file" accept=".pdf,.jpg,.jpeg,.png">
                @error('archivo_oficio_' . ($tipo === 'oficio_inicio' ? 'inicio' : ($tipo === 'oficio_oficial_mayor' ? 'oficial_mayor' : 'fiscal')))
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </section>

  {{-- ===================== BOTONES ===================== --}}
  <div class="d-flex justify-content-end gap-2 mb-4">
    <a href="{{ route('solicitudes.show') }}" class="btn btn-light-secondary me-1">Cancelar</a>
    <button type="submit" class="btn btn-primary">Guardar cambios</button>
  </div>

</form>
@endsection
