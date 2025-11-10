<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="Cofrupa - Empresa líder en exportación de frutas secas y productos agrícolas de alta calidad">

  <title>Cofrupa</title>

  <!-- Google Ads Global Site Tag -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=AW-CONVERSION_ID"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'AW-CONVERSION_ID');
  </script>

  <!-- Preconexiones críticas -->
  <link rel="preconnect" href="https://ajax.googleapis.com">
  <link rel="preconnect" href="https://cdn.jsdelivr.net">
  <link rel="preconnect" href="https://cdnjs.cloudflare.com">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <!-- CSS crítico primero -->
  <link rel="stylesheet" href="{{ asset('css/app.css') }}" type="text/css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  
  <!-- CSS no crítico con media="print" onload -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" media="print" onload="this.media='all'">
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" media="print" onload="this.media='all'">
  <link href="https://fonts.googleapis.com/css2?family=Updock&display=swap" rel="stylesheet" media="print" onload="this.media='all'">

  <!-- Scripts no críticos con defer -->
  <script defer src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body>
    @yield('header')
    @yield('video')
    @yield('nosotros')
    @yield('productos')
    @yield('mercados')
    @yield('contacto')
    @yield('footer')

    <!-- Scripts optimizados -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    
    <!-- Script para ocultar el slider -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
      const slider = document.querySelector(".slider-content");
      if (slider) {
        slider.style.display = "none";
      }
    });
    </script>
    
    <!-- Incluir el editor inline cuando el usuario esté autenticado -->
    @if(session('admin_authenticated'))
        @include('components.inline-editor')
    @endif
</body>

</html>