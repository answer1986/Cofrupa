@extends('admin.layout')

@section('title', 'Dashboard - Cofrupa Admin')

@section('content')
    <div class="header-card">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1><i class="fas fa-tachometer-alt me-3"></i>Dashboard</h1>
                <p class="mb-0">Bienvenido al panel de administración de Cofrupa</p>
            </div>
            <a href="{{ url('/') }}" target="_blank" class="btn btn-success">
                <i class="fas fa-edit me-2"></i>Editar Sitio Visualmente
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="stats-card">
                <div class="stats-icon text-primary">
                    <i class="fas fa-file-text"></i>
                </div>
                <h3 class="text-primary">{{ $totalContents }}</h3>
                <p class="text-muted mb-0">Contenidos</p>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="stats-card">
                <div class="stats-icon text-success">
                    <i class="fas fa-images"></i>
                </div>
                <h3 class="text-success">{{ $totalImages }}</h3>
                <p class="text-muted mb-0">Imágenes</p>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="stats-card">
                <div class="stats-icon text-warning">
                    <i class="fas fa-address-book"></i>
                </div>
                <h3 class="text-warning">{{ $totalContacts ?? 0 }}</h3>
                <p class="text-muted mb-0">Contactos</p>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="stats-card">
                <div class="stats-icon text-info">
                    <i class="fas fa-layer-group"></i>
                </div>
                <h3 class="text-info">{{ $totalFooter ?? 0 }}</h3>
                <p class="text-muted mb-0">Footer</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-rocket me-2"></i>Opciones de Edición</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="{{ url('/') }}" target="_blank" class="btn btn-success w-100 py-3">
                                <i class="fas fa-edit me-2"></i>
                                <strong>Edición Visual</strong><br>
                                <small>Edita directamente en el sitio web</small>
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('admin.contents.index') }}" class="btn btn-primary w-100 py-3">
                                <i class="fas fa-list me-2"></i>
                                <strong>Gestión Avanzada</strong><br>
                                <small>Panel de administración completo</small>
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <form action="{{ route('admin.contents.import') }}" method="POST" class="d-inline w-100">
                                @csrf
                                <button type="submit" class="btn btn-info w-100 py-3">
                                    <i class="fas fa-download me-2"></i>
                                    <strong>Importar Contenido</strong><br>
                                    <small>Desde archivos existentes</small>
                                </button>
                            </form>
                        </div>
                        <div class="col-md-6 mb-3">
                            <form action="{{ route('admin.images.import') }}" method="POST" class="d-inline w-100">
                                @csrf
                                <button type="submit" class="btn btn-warning w-100 py-3">
                                    <i class="fas fa-sync me-2"></i>
                                    <strong>Importar Imágenes</strong><br>
                                    <small>Registrar existentes</small>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Información del Sistema</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Laravel:</strong>
                        <span class="text-muted">{{ app()->version() }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>PHP:</strong>
                        <span class="text-muted">{{ PHP_VERSION }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Entorno:</strong>
                        <span class="badge bg-{{ app()->environment() === 'production' ? 'success' : 'warning' }}">
                            {{ ucfirst(app()->environment()) }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <strong>Idioma Actual:</strong>
                        <span class="text-muted">{{ app()->getLocale() === 'es' ? 'Español' : 'English' }}</span>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-external-link-alt me-2"></i>Enlaces Útiles</h5>
                </div>
                <div class="card-body">
                    <a href="{{ url('/') }}" target="_blank" class="btn btn-outline-success btn-sm w-100 mb-2">
                        <i class="fas fa-edit me-2"></i>Editar Visualmente
                    </a>
                    <a href="{{ route('admin.contents.index') }}" class="btn btn-outline-info btn-sm w-100 mb-2">
                        <i class="fas fa-file-text me-2"></i>Gestionar Contenidos
                    </a>
                    <a href="{{ route('admin.images.index') }}" class="btn btn-outline-success btn-sm w-100 mb-2">
                        <i class="fas fa-images me-2"></i>Gestionar Imágenes
                    </a>
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-warning btn-sm w-100 mb-2">
                        <i class="fas fa-address-book me-2"></i>Gestionar Contactos
                    </a>
                    <a href="{{ route('admin.contents.index') }}?section=footer" class="btn btn-outline-info btn-sm w-100">
                        <i class="fas fa-layer-group me-2"></i>Gestionar Footer
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-question-circle me-2"></i>Cómo Usar la Edición Visual</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h6><i class="fas fa-mouse-pointer me-2 text-success"></i>Paso 1</h6>
                            <p class="text-muted small mb-3">
                                Haz clic en <strong>"Editar Sitio Visualmente"</strong> para abrir tu sitio web con los controles de edición activados.
                            </p>
                        </div>
                        <div class="col-md-4">
                            <h6><i class="fas fa-edit me-2 text-primary"></i>Paso 2</h6>
                            <p class="text-muted small mb-3">
                                Verás lapicitos <i class="fas fa-edit text-primary"></i> al lado de cada texto e imagen. Haz clic en ellos para editar.
                            </p>
                        </div>
                        <div class="col-md-4">
                            <h6><i class="fas fa-save me-2 text-info"></i>Paso 3</h6>
                            <p class="text-muted small mb-3">
                                Edita el contenido y guarda. Los cambios se aplicarán inmediatamente en tu sitio web.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 