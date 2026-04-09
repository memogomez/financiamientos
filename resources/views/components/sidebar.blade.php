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
        <li class='sidebar-title'>Folios</li>
        <li class="sidebar-item  has-sub">
          <a href="#" class='sidebar-link'>
            <i data-feather="file-text" width="20"></i> 
            <span>Folios</span>
          </a>
          <ul class="submenu">
            <li>
              <a href="{{ route('folios.create') }}">Crear</a>
            </li>
            <li>
              <a href="{{ route('folios.show') }}">Ver - Folios</a>
            </li>
          </ul>
        </li>
        <li class="sidebar-item has-sub">
          <a href="#" class="sidebar-link">
            <i data-feather="archive" width="20"></i>
            <span>Historico</span>
          </a>
          <ul class="submenu">
            <li>
              <a href="{{ route('historicos.show') }}">Ver</a>
            </li>
          </ul>
        </li>
      </ul>
    </div>
    <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
  </div>
</div>