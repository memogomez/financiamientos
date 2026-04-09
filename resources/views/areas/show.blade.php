@extends('main')

@section('title', 'Ver - Areas | Sigi')

@php
  $hayErrores    = $errors->isNotEmpty() ? '1' : '0';
  $cualModal     = old('_modal', '');
  $editingAreaId = old('_area_id', '');

  $createInputClass = 'form-control' . ($errors->has('nombre_area') && $cualModal === 'create' ? ' is-invalid' : '');
  $editInputClass   = 'form-control' . ($errors->has('nombre_area') && $cualModal === 'edit'   ? ' is-invalid' : '');

  $createNombre = $cualModal === 'create' ? old('nombre_area', '') : '';
  $editNombre   = $cualModal === 'edit'   ? old('nombre_area', '') : '';
@endphp

@section('content')
<div class="page-title">
  <div class="row">
    <div class="col-12 col-md-6 order-md-1 order-last">
      <h3>Areas</h3>
      <p class="text-subtitle text-muted">Listado de todas las areas registradas</p>
    </div>
    <div class="col-12 col-md-6 order-md-2 order-first">
      <nav aria-label="breadcrumb" class='breadcrumb-header'>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">Areas</a></li>
          <li class="breadcrumb-item" aria-current="page">Ver</li>
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

@if (session('error'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

{{-- Variables para JS (sin -> en atributos de HTML) --}}
<input type="hidden" id="hay-errores"     value="{{ $hayErrores }}">
<input type="hidden" id="cual-modal"      value="{{ $cualModal }}">
<input type="hidden" id="editing-area-id" value="{{ $editingAreaId }}">
<input type="hidden" id="areas-base-url"  value="{{ url('areas') }}">

<section class="section">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <span>Listado de Areas</span>
      <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalNuevaArea">
        + Nueva area
      </button>
    </div>
    <div class="card-body">
      <table class="table table-striped" id="areas-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Estatus</th>
            <th>Editar</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($areas as $area)
            @php
              $toggleUrl   = route('areas.toggleEstatus', $area);
              $editUrl     = url('areas/' . $area->id);
              $isChecked   = $area->estatus ? 'checked' : '';
              $labelClass  = $area->estatus ? 'text-success' : 'text-secondary';
              $labelText   = $area->estatus ? 'Activo' : 'Inactivo';
            @endphp
            <tr>
              <td>{{ $area->id }}</td>
              <td>{{ $area->nombre_area }}</td>
              <td>
                <div class="form-check form-switch d-flex align-items-center gap-2 mb-0">
                  <input
                    class="form-check-input toggle-estatus"
                    type="checkbox"
                    role="switch"
                    data-id="{{ $area->id }}"
                    data-url="{{ $toggleUrl }}"
                    {{ $isChecked }}
                    style="cursor:pointer; width:2.5em; height:1.3em;">
                  <span class="badge-estatus {{ $labelClass }}" id="label-{{ $area->id }}">
                    {{ $labelText }}
                  </span>
                </div>
              </td>
              <td>
                <button
                  type="button"
                  class="btn btn-sm btn-warning btn-editar"
                  data-id="{{ $area->id }}"
                  data-nombre="{{ $area->nombre_area }}"
                  data-url="{{ $editUrl }}">
                  Editar
                </button>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</section>

{{-- Triggers ocultos para abrir modales via JS sin necesitar window.bootstrap --}}
<button type="button" id="trigger-modal-crear"  data-bs-toggle="modal" data-bs-target="#modalNuevaArea"  style="display:none"></button>
<button type="button" id="trigger-modal-editar" data-bs-toggle="modal" data-bs-target="#modalEditarArea" style="display:none"></button>

{{-- ===================== MODAL CREAR ===================== --}}
<div class="modal fade" id="modalNuevaArea" tabindex="-1" aria-labelledby="modalNuevaAreaLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('areas.store') }}" method="post" id="formNuevaArea">
        @csrf
        <input type="hidden" name="_modal" value="create">
        <div class="modal-header">
          <h5 class="modal-title" id="modalNuevaAreaLabel">Nueva area</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div class="form-group mb-0">
            <label for="nombre_area_create">Nombre del area <strong>*</strong></label>
            <input
              class="{{ $createInputClass }}"
              id="nombre_area_create"
              name="nombre_area"
              type="text"
              maxlength="150"
              value="{{ $createNombre }}"
              placeholder="Nombre del area">
            @if ($createInputClass !== 'form-control')
              <div class="invalid-feedback">{{ $errors->first('nombre_area') }}</div>
            @endif
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Registrar</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- ===================== MODAL EDITAR ===================== --}}
<div class="modal fade" id="modalEditarArea" tabindex="-1" aria-labelledby="modalEditarAreaLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formEditarArea" method="post" action="">
        @csrf
        @method('PUT')
        <input type="hidden" name="_modal" value="edit">
        <input type="hidden" name="_area_id" id="form-editing-area-id" value="{{ $editingAreaId }}">
        <div class="modal-header">
          <h5 class="modal-title" id="modalEditarAreaLabel">Editar area</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div class="form-group mb-0">
            <label for="nombre_area_edit">Nombre del area <strong>*</strong></label>
            <input
              class="{{ $editInputClass }}"
              id="nombre_area_edit"
              name="nombre_area"
              type="text"
              maxlength="150"
              value="{{ $editNombre }}"
              placeholder="Nombre del area">
            @if ($editInputClass !== 'form-control')
              <div class="invalid-feedback">{{ $errors->first('nombre_area') }}</div>
            @endif
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-warning">Guardar cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
  <script src="{{ asset('data-tables/datatables.min.js') }}"></script>
  <script>
    const areasBaseUrl  = document.getElementById('areas-base-url').value;
    const hayErrores    = document.getElementById('hay-errores').value === '1';
    const cualModal     = document.getElementById('cual-modal').value;
    const editingAreaId = document.getElementById('editing-area-id').value;

    // DataTable
    $('#areas-table').DataTable({
      order: [[0, 'desc']],
      responsive: true,
      pageLength: 25,
      lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
      language: {
        lengthMenu: 'Mostrando _MENU_ registros por página',
        zeroRecords: 'No se ha encontrado información',
        emptyTable: 'No hay areas registradas',
        info: 'Mostrando página _PAGE_ de _PAGES_',
        infoEmpty: 'No hay información disponible',
        infoFiltered: '(filtrado de _MAX_ registros totales)',
        search: 'Buscar:',
        paginate: { previous: 'Anterior', next: 'Siguiente' },
      },
    });

    // Reabrir modal correcto tras error de validación
    if (hayErrores && cualModal === 'create') {
      document.getElementById('trigger-modal-crear').click();
    }

    if (hayErrores && cualModal === 'edit' && editingAreaId) {
      document.getElementById('formEditarArea').action = areasBaseUrl + '/' + editingAreaId;
      document.getElementById('trigger-modal-editar').click();
    }

    // Abrir modal editar al hacer clic en el botón de la fila
    $(document).on('click', '.btn-editar', function () {
      const id     = $(this).data('id');
      const nombre = $(this).data('nombre');
      const url    = $(this).data('url');

      document.getElementById('formEditarArea').action = url;
      document.getElementById('form-editing-area-id').value = id;
      document.getElementById('nombre_area_edit').value = nombre;

      document.getElementById('trigger-modal-editar').click();
    });

    // Toggle de estatus via AJAX
    $(document).on('change', '.toggle-estatus', function () {
      const checkbox = $(this);
      const id       = checkbox.data('id');
      const url      = checkbox.data('url');
      const label    = $('#label-' + id);

      $.ajax({
        url: url,
        type: 'PATCH',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        success: function (res) {
          if (res.success) {
            label.text(res.label);
            if (res.estatus) {
              label.removeClass('text-secondary').addClass('text-success');
            } else {
              label.removeClass('text-success').addClass('text-secondary');
            }
          }
        },
        error: function () {
          checkbox.prop('checked', !checkbox.prop('checked'));
          alert('No se pudo cambiar el estatus. Intenta de nuevo.');
        },
      });
    });
  </script>
@endsection
