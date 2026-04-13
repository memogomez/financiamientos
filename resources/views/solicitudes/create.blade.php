@extends('main')

@section('title', 'Crear - Solicitudes | Sigi')

@section('content')
<div class="page-title">
  <div class="row">
    <div class="col-12 col-md-6 order-md-1 order-last">
      <h3>Registrar solicitud</h3>
      <p class="text-subtitle text-muted">Completa los datos para guardar una nueva solicitud</p>
    </div>
    <div class="col-12 col-md-6 order-md-2 order-first">
      <nav aria-label="breadcrumb" class='breadcrumb-header'>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">Solicitudes</a></li>
          <li class="breadcrumb-item" aria-current="page">Crear</li>
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

@if($areas->isEmpty())
  <div class="alert alert-warning" role="alert">
    No hay áreas registradas en el sistema. Debes dar de alta al menos un registro en la tabla <code>areas</code> para poder crear solicitudes.
  </div>
@endif

<section id="multiple-column-form">
  <div class="row match-height">
    <div class="col-12">
      <div class="card">
        <div class="card-content">
          <div class="card-body">
            <form action="{{ route('solicitudes.store') }}" method="post" enctype="multipart/form-data">
              @csrf
              <input type="hidden" name="estatus" value="1">
              <input type="hidden" name="comprobacion" value="0">
              <h4 class="card-title mt-3 mb-3">Datos de la solicitud</h4>
              <p class="text-subtitle text-muted">Los campos con <strong>*</strong> son obligatorios</p>
              <div class="row">

                {{-- Fila 1: Área (ancha) + Fecha (compacta) --}}
                <div class="col-md-8 col-12">
                  <div class="form-group">
                    <label for="id_area">Área</label><strong>*</strong>
                    <select class="form-control" name="id_area" id="id_area" {{ $areas->isEmpty() ? 'disabled' : '' }}>
                      <option value="">-- Selecciona un área --</option>
                      @foreach ($areas as $area)
                        <option value="{{ $area->id }}" @selected(old('id_area') == $area->id)>
                          {{ $area->nombre_area ?? 'Área '.$area->id }}
                        </option>
                      @endforeach
                    </select>
                    @error('id_area')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                <div class="col-md-4 col-12">
                  <div class="form-group">
                    <label for="fecha">Fecha</label><strong>*</strong>
                    <input class="form-control" id="fecha" name="fecha" type="date"
                      value="{{ old('fecha', now()->format('Y-m-d')) }}">
                    @error('fecha')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                </div>

                {{-- Fila 2: Solicita y Dirigido a partes iguales --}}
                <div class="col-md-6 col-12">
                  <div class="form-group">
                    <label for="solicita">Solicita</label><strong>*</strong>
                    <input class="form-control" id="solicita" name="solicita" type="text" maxlength="255"
                      placeholder="Nombre de quien solicita" value="{{ old('solicita') }}">
                    @error('solicita')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                <div class="col-md-6 col-12">
                  <div class="form-group">
                    <label for="dirigido">Dirigido a</label><strong>*</strong>
                    <input class="form-control" id="dirigido" name="dirigido" type="text" maxlength="255"
                      placeholder="A quien va dirigido" value="{{ old('dirigido') }}">
                    @error('dirigido')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                </div>

                {{-- Fila 3: Monto solicitado + Comprobación --}}
                <div class="col-md-6 col-12">
                  <div class="form-group">
                    <label for="monto_solicitado">Monto solicitado</label><strong>*</strong>
                    <input class="form-control" id="monto_solicitado" name="monto_solicitado" type="number"
                      step="0.01" min="0" placeholder="0.00" value="{{ old('monto_solicitado', '0') }}">
                    @error('monto_solicitado')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                <div class="col-md-6 col-12">
                  <div class="form-group">
                    <label class="d-block">Comprobación</label>
                    <div class="form-check form-switch mt-2">
                      <input class="form-check-input" id="comprobacion" name="comprobacion" type="checkbox" value="1"
                        @checked(old('comprobacion', 0))>
                      <label class="form-check-label" for="comprobacion">Activar / Desactivar</label>
                    </div>
                    @error('comprobacion')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                </div>

                {{-- Fila 4: Observaciones --}}
                <div class="col-12">
                  <div class="form-group">
                    <label for="observaciones">Observaciones</label>
                    <textarea class="form-control" id="observaciones" name="observaciones"
                      rows="3" placeholder="Escribe las observaciones (opcional)">{{ old('observaciones') }}</textarea>
                    @error('observaciones')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                <div class="col-12 mt-4">
                  <h4 class="card-title mt-3 mb-3">Oficios</h4>
                  <p class="text-subtitle text-muted">Puedes registrar uno, dos o los tres. Los que falten los puedes agregar despues.</p>
                </div>

                <div class="col-md-6 col-12">
                  <div class="form-group">
                    <label for="num_oficio_inicio">Numero oficio inicio</label>
                    <input class="form-control" id="num_oficio_inicio" name="num_oficio_inicio" type="text" maxlength="100"
                      value="{{ old('num_oficio_inicio') }}">
                    @error('num_oficio_inicio')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                <div class="col-md-6 col-12">
                  <div class="form-group">
                    <label for="archivo_oficio_inicio">Archivo oficio inicio</label>
                    <input class="form-control" id="archivo_oficio_inicio" name="archivo_oficio_inicio" type="file" accept=".pdf,.jpg,.jpeg,.png">
                    @error('archivo_oficio_inicio')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                </div>

                <div class="col-md-6 col-12">
                  <div class="form-group">
                    <label for="num_oficio_fiscal">Numero oficio fiscal</label>
                    <input class="form-control" id="num_oficio_fiscal" name="num_oficio_fiscal" type="text" maxlength="100"
                      value="{{ old('num_oficio_fiscal') }}">
                    @error('num_oficio_fiscal')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                <div class="col-md-6 col-12">
                  <div class="form-group">
                    <label for="archivo_oficio_fiscal">Archivo oficio fiscal</label>
                    <input class="form-control" id="archivo_oficio_fiscal" name="archivo_oficio_fiscal" type="file" accept=".pdf,.jpg,.jpeg,.png">
                    @error('archivo_oficio_fiscal')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                </div>

                <div class="col-md-6 col-12">
                  <div class="form-group">
                    <label for="num_oficio_oficial_mayor">Numero oficio oficial mayor</label>
                    <input class="form-control" id="num_oficio_oficial_mayor" name="num_oficio_oficial_mayor" type="text" maxlength="100"
                      value="{{ old('num_oficio_oficial_mayor') }}">
                    @error('num_oficio_oficial_mayor')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                <div class="col-md-6 col-12">
                  <div class="form-group">
                    <label for="archivo_oficio_oficial_mayor">Archivo oficio oficial mayor</label>
                    <input class="form-control" id="archivo_oficio_oficial_mayor" name="archivo_oficio_oficial_mayor" type="file" accept=".pdf,.jpg,.jpeg,.png">
                    @error('archivo_oficio_oficial_mayor')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                <div class="col-12 d-flex justify-content-end">
                  <button type="submit" class="btn btn-primary me-1 mb-1" {{ $areas->isEmpty() ? 'disabled' : '' }}>Registrar</button>
                  <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reiniciar</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
