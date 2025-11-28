@extends('admin.layout')

@section('title', 'Fichas Técnicas - Cofrupa Admin')

@section('content')
<div class="header-card">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-file-pdf me-3"></i>Gestión de Fichas Técnicas</h1>
            <p class="mb-0">Administra los PDFs de fichas técnicas de productos</p>
        </div>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#uploadModal">
            <i class="fas fa-upload me-2"></i>Subir Nueva Ficha
        </button>
    </div>
</div>

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

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Fichas Técnicas Disponibles ({{ count($datasheets) }})</h5>
    </div>
    <div class="card-body">
        @if(count($datasheets) > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th><i class="fas fa-file-pdf me-2"></i>Archivo</th>
                            <th><i class="fas fa-hdd me-2"></i>Tamaño</th>
                            <th><i class="fas fa-calendar me-2"></i>Última Modificación</th>
                            <th class="text-end"><i class="fas fa-cogs me-2"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($datasheets as $datasheet)
                            <tr>
                                <td>
                                    <i class="fas fa-file-pdf text-danger me-2"></i>
                                    <strong>{{ $datasheet['filename'] }}</strong>
                                </td>
                                <td>{{ $datasheet['size'] }}</td>
                                <td>{{ $datasheet['modified'] }}</td>
                                <td class="text-end">
                                    <a href="{{ $datasheet['url'] }}" target="_blank" class="btn btn-sm btn-info me-1" title="Ver PDF">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('download.datasheet', $datasheet['filename']) }}" class="btn btn-sm btn-success me-1" title="Descargar">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <button class="btn btn-sm btn-danger" onclick="confirmDelete('{{ $datasheet['filename'] }}')" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-file-pdf text-muted" style="font-size: 4rem;"></i>
                <p class="text-muted mt-3">No hay fichas técnicas disponibles</p>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    <i class="fas fa-upload me-2"></i>Subir Primera Ficha
                </button>
            </div>
        @endif
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Información</h5>
    </div>
    <div class="card-body">
        <p><strong>Productos actuales con fichas técnicas:</strong></p>
        <ul class="mb-0">
            <li>Ciruelas Pasas Deshuesadas → PITTED PRUNES.pdf</li>
            <li>Ciruelas Pasas con Hueso → PRUNES WITH PÍTS.pdf</li>
            <li>Ciruelas Pasas Condición Natural → PRUNES21.pdf</li>
            <li>Puré de Ciruelas → DEHYDRATED PRUNES PULP.pdf</li>
            <li>Jugo Concentrado de Ciruelas → CONCENTRATED PRUNES JUICE 36 meses de duración.pdf</li>
        </ul>
        <p class="mt-3 mb-0 text-muted small">
            <i class="fas fa-lightbulb me-1"></i>
            Los archivos deben tener el nombre exacto mostrado arriba para que se vinculen automáticamente con los botones de descarga en el sitio web.
        </p>
    </div>
</div>

<!-- Modal para subir fichas -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.datasheets.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-upload me-2"></i>Subir Nueva Ficha Técnica</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="datasheet" class="form-label">Archivo PDF</label>
                        <input type="file" class="form-control" id="datasheet" name="datasheet" accept=".pdf" required>
                        <div class="form-text">
                            Tamaño máximo: 10 MB. Solo archivos PDF.
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Recomendación:</strong> Usa los nombres exactos mostrados en la sección de información para que se vinculen automáticamente con los productos.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-upload me-2"></i>Subir Ficha
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Form oculto para eliminar -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function confirmDelete(filename) {
    if (confirm('¿Estás seguro de que deseas eliminar esta ficha técnica?\n\n' + filename + '\n\nEsta acción no se puede deshacer.')) {
        const form = document.getElementById('deleteForm');
        form.action = '{{ route("admin.datasheets.destroy", ":filename") }}'.replace(':filename', filename);
        form.submit();
    }
}
</script>
@endsection


