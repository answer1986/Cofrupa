@extends('admin.layout')

@section('title', 'Gestión de Imágenes')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-images"></i> Imágenes Editables</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" onclick="importExistingImages()">
                        <i class="fas fa-sync"></i> Sincronizar Imágenes
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if($images->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Clave</th>
                                    <th>Imagen Actual</th>
                                    <th>Sección</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($images as $image)
                                <tr>
                                    <td><code>{{ $image->key }}</code></td>
                                    <td>
                                        <img src="{{ asset($image->path) }}" alt="{{ $image->alt_text_es }}" 
                                             style="max-width: 100px; max-height: 60px; object-fit: cover;">
                                        <br>
                                        <small class="text-muted">{{ $image->path }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $image->section }}</span>
                                    </td>
                                    <td>
                                        @if(strpos($image->path, 'uploads/') !== false)
                                            <span class="badge badge-success">Personalizada</span>
                                        @else
                                            <span class="badge badge-secondary">Original</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(strpos($image->path, 'uploads/') !== false)
                                            <button class="btn btn-danger btn-sm" onclick="restoreImage('{{ $image->key }}')">
                                                <i class="fas fa-undo"></i> Restaurar
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No hay imágenes personalizadas aún.
                        <br>Para crear imágenes editables, usa el modo "Editar Sitio Visualmente" desde el dashboard.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function importExistingImages() {
    if (confirm('¿Importar todas las imágenes existentes del sitio?')) {
        // Aquí puedes agregar la lógica para importar imágenes si es necesario
        alert('Funcionalidad en desarrollo');
    }
}

function restoreImage(key) {
    if (confirm('¿Restaurar la imagen original?')) {
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        formData.append('key', key);

        fetch('/api/admin/image/delete', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + (data.error || 'No se pudo restaurar'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error de conexión');
        });
    }
}
</script>
@endsection 