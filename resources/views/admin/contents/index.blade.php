@extends('admin.layout')

@section('title', 'Contenidos - Onizzo Admin')

@section('content')
    <div class="header-card">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1><i class="fas fa-file-text me-3"></i>Gestión de Contenidos</h1>
                <p class="mb-0">Administra todos los textos de tu sitio web</p>
            </div>
            <a href="{{ route('admin.contents.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Nuevo Contenido
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Lista de Contenidos</h5>
                </div>
                <div class="col-md-6 text-end">
                    <form action="{{ route('admin.contents.import') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-info btn-sm">
                            <i class="fas fa-download me-1"></i>Importar desde Messages
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($contents->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Clave</th>
                                <th>Sección</th>
                                <th>Tipo</th>
                                <th>Contenido ES</th>
                                <th>Contenido EN</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contents as $content)
                                <tr>
                                    <td>
                                        <code class="bg-light p-1 rounded">{{ $content->key }}</code>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $content->section }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $content->type }}</span>
                                    </td>
                                    <td>
                                        <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            {{ $content->value_es ?: 'Sin contenido' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            {{ $content->value_en ?: 'Sin contenido' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.contents.show', $content) }}" 
                                               class="btn btn-outline-info btn-sm"
                                               title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.contents.edit', $content) }}" 
                                               class="btn btn-outline-primary btn-sm"
                                               title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.contents.destroy', $content) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('¿Estás seguro de que quieres eliminar este contenido?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-outline-danger btn-sm"
                                                        title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $contents->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-file-text text-muted" style="font-size: 4rem;"></i>
                    <h4 class="text-muted mt-3">No hay contenidos disponibles</h4>
                    <p class="text-muted">Comienza agregando tu primer contenido o importando desde los archivos existentes.</p>
                    
                    <div class="mt-4">
                        <a href="{{ route('admin.contents.create') }}" class="btn btn-primary me-2">
                            <i class="fas fa-plus me-2"></i>Crear Contenido
                        </a>
                        <form action="{{ route('admin.contents.import') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-info">
                                <i class="fas fa-download me-2"></i>Importar Contenidos
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Información sobre Contenidos</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <h6><i class="fas fa-key me-2 text-primary"></i>Claves Únicas</h6>
                    <p class="text-muted small">
                        Cada contenido tiene una clave única que se usa en las vistas. Por ejemplo: <code>about_us_title</code>
                    </p>
                </div>
                <div class="col-md-4">
                    <h6><i class="fas fa-layer-group me-2 text-success"></i>Secciones</h6>
                    <p class="text-muted small">
                        Los contenidos se organizan por secciones: general, productos, nosotros, contacto, etc.
                    </p>
                </div>
                <div class="col-md-4">
                    <h6><i class="fas fa-language me-2 text-info"></i>Multiidioma</h6>
                    <p class="text-muted small">
                        Cada contenido puede tener versiones en español e inglés para soportar múltiples idiomas.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection 