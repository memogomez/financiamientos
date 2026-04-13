@extends('main')

@section('title', 'Usuarios | Sigi')

@section('content')
<div class="page-title">
  <div class="row">
    <div class="col-12 col-md-6 order-md-1 order-last">
      <h3>Usuarios</h3>
      <p class="text-subtitle text-muted">Administra los usuarios del sistema</p>
    </div>
    <div class="col-12 col-md-6 order-md-2 order-first">
      <nav aria-label="breadcrumb" class='breadcrumb-header'>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">Usuarios</li>
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

<section class="section">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <span>Listado de Usuarios</span>
      <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">+ Nuevo usuario</a>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th>No. Empleado</th>
              <th>Usuario</th>
              <th>Nombre</th>
              <th>Correo</th>
              <th>Rol</th>
              <th class="text-center">Estatus</th>
              <th class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @forelse($users as $i => $user)
            <tr>
              <td class="text-muted small">{{ $i + 1 }}</td>
              <td>{{ $user->id_empleado ?? '—' }}</td>
              <td><strong>{{ $user->usuario }}</strong></td>
              <td>{{ $user->name_user }}</td>
              <td>{{ $user->email }}</td>
              <td><span class="badge bg-secondary">{{ $user->rol }}</span></td>
              <td class="text-center">
                @if($user->estatus)
                  <span class="badge bg-success">Activo</span>
                @else
                  <span class="badge bg-danger">Inactivo</span>
                @endif
              </td>
              <td class="text-center">
                <div class="d-flex justify-content-center gap-1">
                  <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning">Editar</a>
                  <form action="{{ route('users.toggle', $user) }}" method="POST" class="d-inline">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-sm {{ $user->estatus ? 'btn-outline-danger' : 'btn-outline-success' }}">
                      {{ $user->estatus ? 'Desactivar' : 'Activar' }}
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="8" class="text-center text-muted py-4">No hay usuarios registrados.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>
@endsection
