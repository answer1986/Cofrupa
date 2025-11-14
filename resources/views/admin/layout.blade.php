<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Onizzo Admin')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
            --info-color: #17a2b8;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
        }

        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar {
            background: linear-gradient(135deg, var(--primary-color) 0%, #34495e 100%);
            min-height: 100vh;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar .nav-link {
            color: #ecf0f1;
            padding: 15px 20px;
            transition: all 0.3s ease;
            border-radius: 5px;
            margin: 5px 15px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(255,255,255,0.1);
            color: #fff;
            transform: translateX(5px);
        }

        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .main-content {
            padding: 20px;
        }

        .header-card {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #5dade2 100%);
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 1px solid #dee2e6;
            border-radius: 10px 10px 0 0 !important;
            font-weight: 600;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #5dade2 100%);
            border: none;
            border-radius: 25px;
            padding: 10px 25px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        .logo-admin {
            text-align: center;
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }

        .logo-admin h3 {
            color: #ecf0f1;
            margin: 0;
            font-weight: 300;
        }

        .stats-card {
            background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }

        .stats-icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }

        .logout-btn {
            position: absolute;
            bottom: 20px;
            left: 15px;
            right: 15px;
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar position-fixed" style="width: inherit;">
                    <div class="logo-admin">
                        <h3><i class="fas fa-cog"></i> Admin Panel</h3>
                        <small>Cofrupa</small>
                    </div>
                    
                    <nav class="nav flex-column">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.contents.*') ? 'active' : '' }}" href="{{ route('admin.contents.index') }}">
                            <i class="fas fa-file-text"></i> Contenidos
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.images.*') ? 'active' : '' }}" href="{{ route('admin.images.index') }}">
                            <i class="fas fa-images"></i> Imágenes
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}" href="{{ route('admin.contacts.index') }}">
                            <i class="fas fa-address-book"></i> Contactos
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.carousel.*') ? 'active' : '' }}" href="{{ route('admin.carousel.index') }}">
                            <i class="fas fa-film"></i> Carrusel
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.slider.*') ? 'active' : '' }}" href="{{ route('admin.slider.index') }}">
                            <i class="fas fa-play-circle"></i> Slider
                        </a>
                        <a class="nav-link" href="{{ url('/') }}" target="_blank">
                            <i class="fas fa-external-link-alt"></i> Ver Sitio
                        </a>
                    </nav>

                    <form action="{{ route('admin.logout') }}" method="POST" class="logout-btn">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm w-100">
                            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 offset-md-3 offset-lg-2">
                <div class="main-content">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html> 