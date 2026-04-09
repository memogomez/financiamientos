<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'FGJEM | Folios | Sigi')</title>

  <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
  <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
  <link rel="stylesheet" href="{{ asset('select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('data-tables/datatables.min.css') }}">

  <!-- Inter font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

  @yield('styles')
</head>
<body class="sb-nav-fixed">

  <div id="app">
    @include('components.sidebar')

    <div id="main">
      @include('components.nav')
      <div class="main-content container-fluid">
        @yield('content')
      </div>
    </div>
  </div>
  
  <script src="{{ asset('jquery/jquery-3.7.1.min.js') }}"></script>
  <script src="{{ asset('js/feather-icons/feather.min.js') }}"></script>
  <script src="{{ asset('vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ asset('js/app.js') }}"></script>
  <script src="{{ asset('js/main.js') }}"></script>
  @yield('scripts')
</body>
</html>