@extends('main')

@section('title', 'Detalle - Folios | Sigi')

@section('content')
<div class="page-title">
  <div class="row">
    <div class="col-12 col-md-6 order-md-1 order-last">
      <h3>Detalle Folio</h3>
      <p class="text-subtitle text-muted">En esta ventana puedes ver la información de los folios registrados</p>
    </div>
    <div class="col-12 col-md-6 order-md-2 order-first">
      <nav aria-label="breadcrumb" class='breadcrumb-header'>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('folios.show') }}">Folios</a></li>
          <li class="breadcrumb-item" aria-current="page">Detalle</li>
        </ol>
      </nav>
    </div>
  </div>
</div>

<section id="multiple-column-form">
  <div class="row match-height">
    <div class="col-12">
      <div class="card">
        <div class="card-content">
          <div class="card-body">
            <h4 class="card-title mt-3 mb-3">Datos Generales</h4>
            <div class="row">
              <div class="col-md-12 col-12">
                <div class="form-group">
                  <label for="folio">Folio</label>
                  <input class="form-control" id="folio" name="folio" type="text" 
                  placeholder="Ticket" value="{{ $folio->folio }}" disabled>
                </div>
              </div>
              <div class="col-md-6 col-12">
                <div class="form-group">
                  <label for="ticket">Ticket</label>
                  <input class="form-control" id="ticket" name="ticket" type="text" 
                  placeholder="Ticket" value="{{ $folio->ticket }}" disabled>
                </div>
              </div>
              <div class="col-md-6 col-12">
                <div class="form-group">
                  <label for="acronimo">Acrónimo</label>
                  <input class="form-control" id="acronimo" name="acronimo" type="text" 
                  placeholder="Acrónimo" value="{{ $folio->acronimo }}" disabled>
                </div>
              </div>
              <div class="col-md-4 col-12">
                <div class="form-group">
                  <label for="hora">Hora</label>
                  <input class="form-control" id="hora" name="hora" type="text" 
                  placeholder="0000" value="{{ $folio->hora }}" disabled>
                </div>
              </div>
              <div class="col-md-4 col-12">
                <div class="form-group">
                  <label for="dia_mes">Día/Mes</label>
                  <input class="form-control" id="dia_mes" name="dia_mes" type="text" 
                  placeholder="0000" value="{{ $folio->dia_mes }}" disabled>
                </div>
              </div>
              <div class="col-md-4 col-12">
                <div class="form-group">
                  <label for="anio">Año</label>
                  <input class="form-control" id="anio" name="anio" type="text" 
                  placeholder="0000" value="{{ $folio->anio }}" disabled>
                </div>
              </div>
              <div class="col-md-12 col-12">
                <div class="form-group">
                  <label for="razon">Razón por la que se otorgo el folio, mención de alcance y vigencia del mismo</label>
                  <textarea class="form-control" id="razon" name="razon" cols="30" rows="5" disabled>{{ $folio->razon }}</textarea>
                </div>
              </div>
              <div class="col-md-4 col-12">
                <div class="form-group">
                  <label for="numero_registro">NIC, NUC o Número de registro provicional en su libro de gobierno:</label>
                  <input class="form-control" id="numero_registro" name="numero_registro" type="text" 
                  placeholder="" value="{{ $folio->numero_registro }}" disabled>
                </div>
              </div>
              <div class="col-md-4 col-12">
                <div class="form-group">
                  <label for="detenido">Detenido</label>
                  <input class="form-control" id="detenido" name="detenido" type="text" 
                  placeholder="Nombre detenido" value="{{ $folio->detenido }}" disabled>
                </div>
              </div>
              <div class="col-md-4 col-12">
                <div class="form-group">
                  <label for="id_delito">Delito</label>
                  <textarea class="form-control" name="delito" id="delito" cols="30" 
                  rows="1" disabled>{{ $folio->delito->delito }}</textarea>
                </div>
              </div>
              <h4 class="card-title mt-3 mb-3">Datos Solicitante</h4>
              <div class="col-md-4 col-12">
                <div class="form-group">
                  <label for="nombre_solicitante">Nombre</label>
                  <input class="form-control" id="nombre_solicitante" name="nombre_solicitante" type="text" 
                  placeholder="Nombre solicitante" value="{{ $folio->solicitante->nombre_solicitante }}" disabled>
                </div>
              </div>
              <div class="col-md-4 col-12">
                <div class="form-group">
                  <label for="plaza">Plaza</label>
                  <input class="form-control" id="plaza" name="plaza" type="text" 
                  placeholder="Plaza" value="{{ $folio->solicitante->plaza }}" disabled>
                </div>
              </div>
              <div class="col-md-4 col-12">
                <div class="form-group">
                  <label for="gafete">Gafete</label>
                  <input class="form-control" id="gafete" name="gafete" type="text" 
                  placeholder="Gafete" value="{{ $folio->solicitante->gafete }}" disabled>
                </div>
              </div>
              <div class="col-md-6 col-12">
                <div class="form-group">
                  <label for="agencia_mp">Agencia del MP</label>
                  <input class="form-control" id="agencia_mp" name="agencia_mp" type="text" 
                  placeholder="Agencia mp" value="{{ $folio->solicitante->agencia_mp }}" disabled>
                </div>
              </div>
              <div class="col-md-6 col-12">
                <div class="form-group">
                  <label for="turno">Turno</label>
                  <input class="form-control" id="turno" name="turno" type="text" 
                  placeholder="Turno" value="{{ $folio->solicitante->turno }}" disabled>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<a class="btn btn-secondary round" href="javascript:history.back()"> Regresar</a>
@endsection

@section('scripts')
  <script src="{{ asset('select2/js/select2.min.js') }}"></script>
  <script>
    $(document).ready(function() {
      $('#id_delito').select2();
    });
  </script>
@endsection