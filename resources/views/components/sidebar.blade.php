<div id="sidebar" class='active'>
  <div class="sidebar-wrapper active">
    <div class="sidebar-header">
      <img src="{{ asset('images/fgjem.jpg') }}" alt="" srcset="">
    </div>
    <div class="sidebar-menu">
      <ul class="menu">
        <li class="sidebar-title">Menu</li>
        <li class="sidebar-item active">
          <a href="{{ route('principal.inicio') }}" class='sidebar-link'>
            <i data-feather="home" width="20"></i> 
            <span>Inicio</span>
          </a>
        </li>
        <li class='sidebar-title'>SOLICITUDES</li>
        <li class="sidebar-item has-sub">
          <a href="#" class='sidebar-link'>
            <i data-feather="clipboard" width="20"></i>
            <span>Solicitudes</span>
          </a>
          <ul class="submenu">
            <li>
              <a href="{{ route('solicitudes.create') }}">Crear</a>
            </li>
            <li>
              <a href="{{ route('solicitudes.show') }}">Ver</a>
            </li>
          </ul>
        </li>
        <li class='sidebar-title'>AREAS</li>
        <li class="sidebar-item has-sub">
          <a href="#" class='sidebar-link'>
            <i data-feather="grid" width="20"></i>
            <span>Areas</span>
          </a>
          <ul class="submenu">
            <li>
              <a href="{{ route('areas.show') }}">Ver</a>
            </li>
          </ul>
        </li>
        <li class='sidebar-title'>USUARIOS</li>
        <li class="sidebar-item has-sub">
          <a href="#" class='sidebar-link'>
            <i data-feather="users" width="20"></i>
            <span>Usuarios</span>
          </a>
          <ul class="submenu">
            <li>
              <a href="{{ route('users.index') }}">Ver</a>
            </li>
            <li>
              <a href="{{ route('users.create') }}">Crear</a>
            </li>
          </ul>
        </li>
        <li class='sidebar-title'>REPORTES</li>
        <li class="sidebar-item has-sub">
          <a href="#" class='sidebar-link'>
            <i data-feather="bar-chart-2" width="20"></i>
            <span>Reportes</span>
          </a>
          <ul class="submenu">
            <li>
              <a href="{{ route('reportes.fechas') }}">Fechas</a>
            </li>
          </ul>
        </li>
      </ul>
    </div>
    <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
  </div>
</div>
