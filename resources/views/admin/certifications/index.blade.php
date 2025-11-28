@extends('admin.layout')

@section('title', 'Imágenes - Sección Certificaciones')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.css">
<style>
.cert-item {
    cursor: move;
    transition: all 0.3s ease;
}
.cert-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}
.cert-item.sortable-ghost {
    opacity: 0.4;
}
.cert-order-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: rgba(0,123,255,0.9);
    color: white;
    padding: 5px 10px;
    border-radius: 20px;
    font-weight: bold;
    z-index: 10;
}
</style>
@endpush

@section('content')
<div class="header-card">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-image me-3"></i>Gestión de Imágenes del Carrusel</h1>
            <p class="mb-0">Administra las imágenes que aparecen en el carrusel de la sección de certificaciones del sitio web</p>
        </div>
        <div>
            <button class="btn btn-primary me-2" onclick="showAddCertModal()">
                <i class="fas fa-plus me-2"></i>Agregar Imagen
            </button>
            <a href="{{ url('/#productos') }}" target="_blank" class="btn btn-success">
                <i class="fas fa-eye me-2"></i>Ver en el Sitio
            </a>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="alert alert-info">
    <i class="fas fa-info-circle me-2"></i>
    <strong>Tip:</strong> Arrastra y suelta las imágenes para cambiar su orden en el carrusel. Los cambios se guardan automáticamente.
</div>

<!-- Imágenes del Carrusel -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0"><i class="fas fa-images me-2"></i>Imágenes del Carrusel</h5>
            <small class="text-muted">Arrastra para reordenar | Total: {{ $certifications->count() }} imágenes</small>
        </div>
        <button class="btn btn-sm btn-success" onclick="showAddCertModal()">
            <i class="fas fa-plus me-1"></i>Agregar Imagen
        </button>
    </div>
    <div class="card-body">
        @if($certifications->count() > 0)
        <div class="row" id="certifications-sortable">
            @foreach($certifications as $cert)
            <div class="col-md-6 col-lg-3 mb-4 cert-item" data-id="{{ $cert->id }}">
                <div class="card h-100 position-relative">
                    <span class="cert-order-badge">#{{ $cert->cert_order }}</span>
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <strong>{{ $cert->key }}</strong>
                        <i class="fas fa-grip-vertical"></i>
                    </div>
                    <div class="card-body text-center">
                        <img src="/{{ $cert->path }}" 
                             alt="{{ $cert->alt_text_es }}" 
                             class="img-fluid mb-3" 
                             style="max-height: 200px; object-fit: contain;">
                        
                        <h6>{{ $cert->title_es }}</h6>
                        <p class="text-muted small">{{ $cert->alt_text_es }}</p>
                        
                        <div class="btn-group w-100">
                            <button class="btn btn-sm btn-info" onclick="editCertification({{ $cert->id }}, '{{ $cert->key }}', '{{ addslashes($cert->title_es) }}', '{{ addslashes($cert->title_en ?? '') }}', '{{ addslashes($cert->alt_text_es) }}', '{{ addslashes($cert->alt_text_en ?? '') }}')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deleteCertification({{ $cert->id }}, '{{ addslashes($cert->title_es) }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="alert alert-warning text-center">
            <i class="fas fa-exclamation-triangle me-2"></i>
            No hay imágenes aún. Haz clic en "Agregar Imagen" para comenzar.
        </div>
        @endif
    </div>
</div>

<!-- Imágenes del Footer -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-image me-2"></i>Imágenes en el Footer</h5>
        <small class="text-muted">Estas 2 imágenes de certificaciones aparecen fijas en el footer del sitio</small>
    </div>
    <div class="card-body">
        @if($footerCertifications->count() > 0)
        <div class="row">
            @foreach($footerCertifications as $cert)
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-secondary text-white">
                        <strong>{{ $cert->key }}</strong>
                    </div>
                    <div class="card-body text-center">
                        <img src="/{{ $cert->path }}" 
                             alt="{{ $cert->alt_text_es }}" 
                             class="img-fluid mb-3" 
                             style="max-height: 150px; object-fit: contain;">
                        
                        <h6>{{ $cert->title_es }}</h6>
                        <p class="text-muted small">{{ $cert->alt_text_es }}</p>
                        
                        <button class="btn btn-sm btn-info w-100" onclick="editCertification({{ $cert->id }}, '{{ $cert->key }}', '{{ addslashes($cert->title_es) }}', '{{ addslashes($cert->title_en ?? '') }}', '{{ addslashes($cert->alt_text_es) }}', '{{ addslashes($cert->alt_text_en ?? '') }}')">
                            <i class="fas fa-edit me-1"></i>Editar
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle me-2"></i>
            Las certificaciones del footer se gestionan desde la sección de imágenes con las claves <code>footer_cert_image_1</code> y <code>footer_cert_image_2</code>
        </div>
        @endif
    </div>
</div>

<!-- Modal para Agregar Imagen -->
<div class="modal fade" id="addCertModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Agregar Nueva Imagen</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="addCertForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Imagen *</label>
                        <input type="file" class="form-control" name="image" accept="image/*" required>
                        <small class="text-muted">Formatos: JPG, PNG, WebP. Máx: 10MB. Preferible fondo transparente.</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Título (Español) *</label>
                        <input type="text" class="form-control" name="title_es" placeholder="Ej: Certificación BRC" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Título (Inglés)</label>
                        <input type="text" class="form-control" name="title_en" placeholder="Ex: BRC Certification">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Texto Alternativo (Español) *</label>
                        <input type="text" class="form-control" name="alt_text_es" placeholder="Descripción para accesibilidad" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Texto Alternativo (Inglés)</label>
                        <input type="text" class="form-control" name="alt_text_en" placeholder="Description for accessibility">
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Agregar Imagen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Editar Imagen -->
<div class="modal fade" id="editCertModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Editar Imagen</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editCertForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="cert_id" name="cert_id">
                <input type="hidden" id="cert_key" name="cert_key">
                
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Key:</strong> <span id="display_cert_key"></span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nueva Imagen (opcional)</label>
                        <input type="file" class="form-control" name="image" accept="image/*">
                        <small class="text-muted">Deja vacío si no deseas cambiar la imagen</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Título (Español) *</label>
                        <input type="text" class="form-control" id="cert_title_es" name="title_es" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Título (Inglés)</label>
                        <input type="text" class="form-control" id="cert_title_en" name="title_en">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Texto Alternativo (Español) *</label>
                        <input type="text" class="form-control" id="cert_alt_es" name="alt_text_es" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Texto Alternativo (Inglés)</label>
                        <input type="text" class="form-control" id="cert_alt_en" name="alt_text_en">
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-save me-2"></i>Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
const addCertModal = new bootstrap.Modal(document.getElementById('addCertModal'));
const editCertModal = new bootstrap.Modal(document.getElementById('editCertModal'));

// Inicializar Sortable para reordenar certificaciones
const certSortable = document.getElementById('certifications-sortable');
if (certSortable) {
    new Sortable(certSortable, {
        animation: 150,
        handle: '.cert-item',
        ghostClass: 'sortable-ghost',
        onEnd: async function(evt) {
            const order = [];
            document.querySelectorAll('.cert-item').forEach(item => {
                order.push(parseInt(item.dataset.id));
            });
            
            try {
                const response = await fetch('/admin/certifications/reorder', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ order: order })
                });
                
                const data = await response.json();
                if (data.success) {
                    location.reload();
                }
            } catch (error) {
                console.error('Error:', error);
                alert('✗ Error al reordenar');
            }
        }
    });
}

function showAddCertModal() {
    document.getElementById('addCertForm').reset();
    addCertModal.show();
}

// Agregar nueva certificación
document.getElementById('addCertForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch('/admin/certifications/store', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('✓ Imagen agregada exitosamente');
            location.reload();
        } else {
            alert('✗ Error: ' + (data.error || 'No se pudo agregar la imagen'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('✗ Error al agregar la imagen');
    }
});

function editCertification(id, key, titleEs, titleEn, altEs, altEn) {
    document.getElementById('cert_id').value = id;
    document.getElementById('cert_key').value = key;
    document.getElementById('display_cert_key').textContent = key;
    document.getElementById('cert_title_es').value = titleEs;
    document.getElementById('cert_title_en').value = titleEn || '';
    document.getElementById('cert_alt_es').value = altEs;
    document.getElementById('cert_alt_en').value = altEn || '';
    
    editCertModal.show();
}

// Editar certificación
document.getElementById('editCertForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const certId = document.getElementById('cert_id').value;
    const formData = new FormData(this);
    
    try {
        const response = await fetch(`/admin/certifications/update/${certId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('✓ Imagen actualizada exitosamente');
            location.reload();
        } else {
            alert('✗ Error: ' + (data.error || 'No se pudo actualizar la imagen'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('✗ Error al actualizar la imagen');
    }
});

async function deleteCertification(id, title) {
    if (!confirm(`¿Estás seguro de eliminar la imagen "${title}"? Esta acción no se puede deshacer.`)) {
        return;
    }
    
    try {
        const response = await fetch('/admin/certifications/destroy', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ id: id })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('✓ Imagen eliminada exitosamente');
            location.reload();
        } else {
            alert('✗ Error: ' + (data.error || 'No se pudo eliminar la imagen'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('✗ Error al eliminar la imagen');
    }
}
</script>
@endpush
