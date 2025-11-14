<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="google-site-verification" content="i8uXiThHjzH94pRMfUwmZjCLLu8txy2R93PAN4IesD0" />
  
  <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ asset('image/LOGO-sin fonfopng.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('image/LOGO-sin fonfopng.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('image/LOGO-sin fonfopng.png') }}">
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('image/LOGO-sin fonfopng.png') }}">
  <link rel="shortcut icon" type="image/png" href="{{ asset('image/LOGO-sin fonfopng.png') }}">
  
  @if(app()->getLocale() == 'es')
    <!-- SEO Español -->
    <title>Cofrupa - Exportadores de Ciruelas Pasas D'Agen desde Chile | 20 Años de Experiencia</title>
    <meta name="description" content="Cofrupa: Exportadores chilenos líderes de ciruelas pasas D'Agen con 20 años de experiencia. Especialistas en ciruelas pasas con y sin hueso, puré de ciruela y jugo concentrado. Exportamos a 5 continentes con certificaciones BRC y FDA.">
    <meta name="keywords" content="exportación ciruelas pasas, ciruelas pasas Chile, ciruelas D'Agen, exportadores ciruelas, ciruelas deshidratadas, puré ciruela, jugo concentrado ciruela, exportación frutas secas Chile, BRC FDA certificado">
    <meta name="author" content="Cofrupa">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url('/') }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="Cofrupa - Exportadores de Ciruelas Pasas D'Agen desde Chile">
    <meta property="og:description" content="20 años exportando ciruelas pasas chilenas a 5 continentes. Productos certificados BRC y FDA. Ciruelas pasas con y sin hueso, puré y jugo concentrado.">
    <meta property="og:image" content="{{ asset('image/LOGO-sin fonfopng.png') }}">
    <meta property="og:locale" content="es_CL">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url('/') }}">
    <meta name="twitter:title" content="Cofrupa - Exportadores de Ciruelas Pasas D'Agen">
    <meta name="twitter:description" content="Exportadores chilenos de ciruelas pasas con 20 años de experiencia. Certificaciones BRC y FDA.">
    <meta name="twitter:image" content="{{ asset('image/LOGO-sin fonfopng.png') }}">
    
  @elseif(app()->getLocale() == 'en')
    <!-- SEO English -->
    <title>Cofrupa - D'Agen Prunes Exporters from Chile | 20 Years Experience</title>
    <meta name="description" content="Cofrupa: Leading Chilean exporters of D'Agen prunes with 20 years of experience. Specialists in pitted and unpitted prunes, prune puree, and concentrated prune juice. We export to 5 continents with BRC and FDA certifications.">
    <meta name="keywords" content="prune export, Chilean prunes, D'Agen prunes, prune exporters, dried plums, prune puree, concentrated prune juice, dried fruit export Chile, BRC FDA certified">
    <meta name="author" content="Cofrupa">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url('/') }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="Cofrupa - D'Agen Prunes Exporters from Chile">
    <meta property="og:description" content="20 years exporting Chilean prunes to 5 continents. BRC and FDA certified products. Pitted and unpitted prunes, puree and concentrated juice.">
    <meta property="og:image" content="{{ asset('image/LOGO-sin fonfopng.png') }}">
    <meta property="og:locale" content="en_US">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url('/') }}">
    <meta name="twitter:title" content="Cofrupa - D'Agen Prunes Exporters from Chile">
    <meta name="twitter:description" content="Chilean prune exporters with 20 years of experience. BRC and FDA certifications.">
    <meta name="twitter:image" content="{{ asset('image/LOGO-sin fonfopng.png') }}">
    
  @elseif(app()->getLocale() == 'zh')
    <!-- SEO 中文 -->
    <title>Cofrupa - 智利达根梅干出口商 | 20年经验</title>
    <meta name="description" content="Cofrupa：智利领先的达根梅干出口商，拥有20年经验。专注于去核和带核梅干、梅子泥和浓缩梅汁。我们出口到五大洲，拥有BRC和FDA认证。">
    <meta name="keywords" content="梅干出口, 智利梅干, 达根梅干, 梅干出口商, 干梅子, 梅子泥, 浓缩梅汁, 智利干果出口, BRC FDA认证">
    <meta name="author" content="Cofrupa">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url('/') }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="Cofrupa - 智利达根梅干出口商">
    <meta property="og:description" content="20年向五大洲出口智利梅干。BRC和FDA认证产品。去核和带核梅干、梅子泥和浓缩汁。">
    <meta property="og:image" content="{{ asset('image/LOGO-sin fonfopng.png') }}">
    <meta property="og:locale" content="zh_CN">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url('/') }}">
    <meta name="twitter:title" content="Cofrupa - 智利达根梅干出口商">
    <meta name="twitter:description" content="拥有20年经验的智利梅干出口商。BRC和FDA认证。">
    <meta name="twitter:image" content="{{ asset('image/LOGO-sin fonfopng.png') }}">
  @endif

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