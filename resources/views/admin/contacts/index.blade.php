@extends('admin.layout')

@section('title', 'Gestión de Contactos y Footer - Onizzo Admin')

@section('content')
    <div class="header-card">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1><i class="fas fa-address-book me-3"></i>Gestión de Contactos y Footer</h1>
                <p class="mb-0">Administra la información de contacto y footer del sitio web</p>
            </div>
            <div>
                <a href="{{ route('admin.contents.index') }}?section=contacto" class="btn btn-primary me-2">
                    <i class="fas fa-edit me-2"></i>Editar Contactos
                </a>
                <a href="{{ route('admin.contents.index') }}?section=footer" class="btn btn-success me-2">
                    <i class="fas fa-edit me-2"></i>Editar Footer
                </a>
                <div class="btn-group">
                    <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-download me-2"></i>Importar
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <form action="{{ route('admin.contacts.import') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-address-book me-2"></i>Importar Contactos
                                </button>
                            </form>
                        </li>
                        <li>
                            <form action="{{ route('footer.import') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-layer-group me-2"></i>Importar Footer
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Información sobre Contactos y Footer
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-lightbulb me-2"></i>¿Cómo funciona la edición de contactos y footer?</h6>
                        <p class="mb-2">Tanto los contactos como el footer en tu sitio web ahora son completamente editables. Cada elemento (nombres, emails, teléfonos, títulos, enlaces) se puede editar individualmente.</p>
                        <ul class="mb-0">
                            <li><strong>Edición directa:</strong> Ve a tu sitio web y haz clic en los lapicitos para editar</li>
                            <li><strong>Gestión desde admin:</strong> Ve a la sección "Contenidos" y filtra por "contacto" o "footer"</li>
                            <li><strong>Importar elementos:</strong> Usa los botones de importar para crear los elementos editables</li>
                            <li><strong>Nota:</strong> El crédito de desarrollo (R3Q) permanece fijo y no es editable</li>
                        </ul>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 col-lg-3">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <i class="fas fa-address-book fa-3x text-primary mb-3"></i>
                                    <h6>Editar Contactos</h6>
                                    <p class="text-muted small mb-3">Gestiona información de contacto</p>
                                    <a href="{{ route('admin.contents.index') }}?section=contacto" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit me-2"></i>Ir a Contactos
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-3">
                            <div class="card border-warning">
                                <div class="card-body text-center">
                                    <i class="fas fa-layer-group fa-3x text-warning mb-3"></i>
                                    <h6>Editar Footer</h6>
                                    <p class="text-muted small mb-3">Gestiona información del footer</p>
                                    <a href="{{ route('admin.contents.index') }}?section=footer" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit me-2"></i>Ir a Footer
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-3">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <i class="fas fa-eye fa-3x text-success mb-3"></i>
                                    <h6>Ver Contactos</h6>
                                    <p class="text-muted small mb-3">Ve la sección contacto</p>
                                    <a href="{{ url('/#contacto') }}" class="btn btn-success btn-sm" target="_blank">
                                        <i class="fas fa-external-link-alt me-2"></i>Ver Sitio
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-3">
                            <div class="card border-info">
                                <div class="card-body text-center">
                                    <i class="fas fa-eye fa-3x text-info mb-3"></i>
                                    <h6>Ver Footer</h6>
                                    <p class="text-muted small mb-3">Ve el footer del sitio</p>
                                    <a href="{{ url('/') }}" class="btn btn-info btn-sm" target="_blank">
                                        <i class="fas fa-external-link-alt me-2"></i>Ver Footer
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

 