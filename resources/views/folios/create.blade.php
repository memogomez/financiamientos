@extends('main')

@section('title', 'Crear - Folios | Sigi')

@section('content')
<div class="page-title">
  <div class="row">
    <div class="col-12 col-md-6 order-md-1 order-last">
      <h3>Registrar Folio</h3>
      <p class="text-subtitle text-muted">Esta ventana es para registrar un nuevo folio</p>
    </div>
    <div class="col-12 col-md-6 order-md-2 order-first">
      <nav aria-label="breadcrumb" class='breadcrumb-header'>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">Folios</a></li>
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

<section id="multiple-column-form">
  <div class="row match-height">
    <div class="col-12">
      <div class="card">
        <div class="card-content">
          <div class="card-body">
            <form action="{{ route('folios.store') }}" method="post">
              @csrf
              <h4 class="card-title mt-3 mb-3">Datos Generales</h4>
              <p class="text-subtitle text-muted">Recuerda que los campos con <strong>*</strong> son obligatorios</p>
              <div class="row">
                <div class="col-md-6 col-12">
                  <div class="form-group">
                    <label for="ticket">Ticket</label>
                    <input class="form-control" id="ticket" name="ticket" type="text" 
                    value="{{ old('ticket') }}">
                    @error('ticket')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                <div class="col-md-6 col-12">
                  <div class="form-group">
                    <label for="acronimo">Acrónimo</label><strong>*</strong>
                    <input class="form-control" id="acronimo" name="acronimo" type="text" 
                    placeholder="Acrónimo" value="{{ old('acronimo') }}">
                    @error('acronimo')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                <div class="col-md-4 col-12">
                  <div class="form-group">
                    <label for="hora">Hora</label><strong>*</strong>
                    <input class="form-control" id="hora" name="hora" type="text" 
                    placeholder="0000" value="{{ old('hora') }}">
                    @error('hora')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                <div class="col-md-4 col-12">
                  <div class="form-group">
                    <label for="dia_mes">Día/Mes</label><strong>*</strong>
                    <input class="form-control" id="dia_mes" name="dia_mes" type="text" 
                    placeholder="0000" value="{{ old('dia_mes') }}">
                    @error('dia_mes')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                <div class="col-md-4 col-12">
                  <div class="form-group">
                    <label for="anio">Año</label><strong>*</strong>
                    <input class="form-control" id="anio" name="anio" type="text" 
                    placeholder="0000" value="{{ old('anio') }}">
                    @error('anio')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                <div class="col-md-12 col-12">
                  <div class="form-group">
                    <label for="razon">Motivo por el que se otorga el folio</label><strong>*</strong>
                    <textarea class="form-control" id="razon" name="razon" cols="30" rows="5">{{ old('razon') }}</textarea>
                    @error('razon')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                <div class="col-md-4 col-12">
                  <div class="form-group">
                    <label for="numero_registro">NIC, NUC o Número de registro provisional en su libro de gobierno</label><strong>*</strong>
                    <input class="form-control" id="numero_registro" name="numero_registro" type="text" 
                    placeholder="" value="{{ old('numero_registro') }}">
                    @error('numero_registro')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                <div class="col-md-4 col-12">
                  <div class="form-group">
                    <label for="detenido">Detenido</label><strong>*</strong>
                    <input class="form-control" id="detenido" name="detenido" type="text" 
                    placeholder="Nombre detenido" value="{{ old('detenido') }}">
                    @error('detenido')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                <div class="col-md-4 col-12">
                  <div class="form-group">
                    <label for="id_delito">Delito</label><strong>*</strong>
                    <select class="form-control" name="id_delito" id="id_delito">
                      <option value="">-- Selecciona un delito --</option>
                      @foreach ($delitos as $delito)
                        <option value="{{ $delito->id_delito }}">{{ $delito->delito }}</option>
                      @endforeach
                    </select>
                    @error('id_delito')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                <h4 class="card-title mt-3 mb-3">Datos Solicitante</h4>
                <div class="col-md-4 col-12">
                  <div class="form-group">
                    <label for="nombre_solicitante">Nombre</label><strong>*</strong>
                    <input class="form-control" id="nombre_solicitante" name="nombre_solicitante" type="text" 
                    placeholder="Nombre solicitante" value="{{ old('nombre_solicitante') }}">
                    @error('nombre_solicitante')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                <div class="col-md-4 col-12">
                  <div class="form-group">
                    <label for="plaza">Plaza</label><strong>*</strong>
                    <input class="form-control" id="plaza" name="plaza" type="text" 
                    placeholder="Plaza" value="{{ old('plaza') }}">
                    @error('plaza')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                <div class="col-md-4 col-12">
                  <div class="form-group">
                    <label for="gafete">Gafete</label><strong>*</strong>
                    <input class="form-control" id="gafete" name="gafete" type="text" 
                    placeholder="Gafete" value="{{ old('gafete') }}">
                    @error('gafete')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                <div class="col-md-6 col-12">
                  <div class="form-group">
                    <label for="agencia_mp">Agencia del MP</label><strong>*</strong>
                    <input class="form-control" id="agencia_mp" name="agencia_mp" type="text" 
                    placeholder="Agencia mp" value="{{ old('agencia_mp') }}">
                    @error('agencia_mp')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                <div class="col-md-6 col-12">
                  <div class="form-group">
                    <label for="turno">Turno</label><strong>*</strong>
                    <input class="form-control" id="turno" name="turno" type="text" 
                    placeholder="Turno" value="{{ old('turno') }}">
                    @error('turno')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                <div class="col-12 d-flex justify-content-end">
                  <button type="submit" class="btn btn-primary me-1 mb-1">Registrar</button>
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

@section('scripts')
  <script src="{{ asset('select2/js/select2.min.js') }}"></script>
  <script src="{{ asset('js/folios/create.js') }}" ></script>
@endsection