@extends('admin.layout')

@section('title', 'Gestión del Slider')

@push('styles')
<!-- Cropper.js CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
<style>
.slider-item {
    cursor: default;
    transition: all 0.3s ease;
    position: relative;
}

.slider-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.slider-item .drag-handle {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #e74c3c;
    color: white !important;
    padding: 8px 12px;
    border-radius: 8px;
    cursor: grab;
    font-size: 16px;
    z-index: 100;
    opacity: 1;
    transition: all 0.3s ease;
    border: 2px solid #fff;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}


.slider-item .drag-handle:hover {
    transform: scale(1.1);
    background: #c0392b;
    box-shadow: 0 6px 16px rgba(0,0,0,0.3);
}

.slider-item.sortable-ghost {
    opacity: 0.3;
    transform: scale(0.95);
    background: rgba(231, 76, 60, 0.1);
    border: 2px dashed #e74c3c;
    border-radius: 8px;
}

.slider-item.sortable-chosen {
    transform: scale(1.05);
    z-index: 1000;
    box-shadow: 0 12px 30px rgba(0,0,0,0.2);
}

.slider-item.sortable-drag {
    cursor: grabbing;
    opacity: 0.9;
}

.slider-item.being-dragged .drag-handle {
    background: #3498db;
    opacity: 1;
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(0,0,0,0.4);
}
.image-preview-container {
    position: relative;
    max-height: 400px;
    overflow: hidden;
    background: #f8f9fa;
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    text-align: center;
    padding: 20px;
}
.image-preview {
    max-width: 100%;
    max-height: 300px;
    border-radius: 5px;
}
.crop-container {
    max-height: 400px;
    margin: 20px 0;
}
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-play-circle"></i> Gestión del Slider Principal</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" onclick="showAddSlideModal()">
                        <i class="fas fa-plus"></i> Agregar Slide
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Instrucciones -->
                @if($images->count() > 1)
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> 
                    <strong>Arrastra las imágenes</strong> para cambiar el orden del slider. Los cambios se guardan automáticamente.
                </div>
                @endif
                
                <div id="slider-images" class="row" style="min-height: 200px;">
                    @foreach($images as $image)
                    <div class="col-md-6 col-lg-4 mb-4 slider-item" data-id="{{ $image->id }}">
                        <div class="card">
                            <div class="card-header p-2 d-flex justify-content-between align-items-center">
                                <span class="badge badge-primary">Orden: {{ $image->slider_order }}</span>
                                <div class="d-flex">
                                    <div class="drag-handle me-2" style="cursor: grab; color: #6c757d;">
                                        <i class="fas fa-grip-vertical"></i>
                                    </div>
                                <div>
                                    <button class="btn btn-sm btn-info" onclick="editSlide({{ $image->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteSlide({{ $image->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Imagen principal -->
                            <img src="/{{ $image->path }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                            
                            <div class="card-body p-3">
                                <h6 class="card-title">{{ $image->title_es }}</h6>
                                <p class="card-text">{{ Str::limit($image->content_es, 60) }}</p>
                                
                                <!-- Thumbnail preview -->
                                @if($image->thumbnail_path)
                                <div class="d-flex align-items-center mt-2">
                                    <span class="badge badge-secondary me-2">Thumbnail:</span>
                                    <img src="/{{ $image->thumbnail_path }}" alt="Thumbnail" style="width: 40px; height: 25px; object-fit: cover; border-radius: 3px;">
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                @if($images->count() == 0)
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> No hay slides en el slider aún.
                    <br>Haz clic en "Agregar Slide" para comenzar.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar slide -->
<div class="modal fade" id="addSlideModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Agregar Slide al Slider</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addSlideForm" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Imagen Principal -->
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-image me-1"></i>Imagen Principal <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="addImageInput" name="image" accept="image/*" required onchange="previewAddImage(this)">
                                <div class="form-text">Imagen de fondo del slide (recomendado: 1200x400px)</div>
                        
                        <!-- Preview y crop container -->
                        <div id="addImagePreviewContainer" class="image-preview-container mt-3" style="display: none;">
                            <img id="addImagePreview" class="image-preview">
                            <div class="mt-3">
                                <button type="button" class="btn btn-sm btn-primary" onclick="cropAddImage()">
                                    <i class="fas fa-crop"></i> Recortar Imagen
                                </button>
                                <button type="button" class="btn btn-sm btn-secondary" onclick="resetAddImage()">
                                    <i class="fas fa-undo"></i> Restablecer
                                </button>
                            </div>
                        </div>
                        
                        <!-- Crop container -->
                        <div id="addCropContainer" class="crop-container" style="display: none;">
                            <img id="addCropImage">
                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-success" onclick="confirmAddCrop()">
                                    <i class="fas fa-check"></i> Confirmar Recorte
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="cancelAddCrop()">
                                    <i class="fas fa-times"></i> Cancelar
                                </button>
                            </div>
                        </div>
                        
                        <!-- Hidden input para verificar si hay imagen procesada -->
                        <input type="hidden" id="addImageFile">
                    </div>
                    
                    <!-- Thumbnail -->
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-image me-1"></i>Thumbnail (Opcional)</label>
                        <input type="file" class="form-control" id="addThumbnailInput" name="thumbnail" accept="image/*" onchange="previewAddThumbnail(this)">
                        <div class="form-text">Se generará automáticamente desde la imagen principal si no se especifica</div>
                        
                        <!-- Thumbnail preview -->
                        <div id="addThumbnailPreviewContainer" class="image-preview-container mt-3" style="display: none;">
                            <img id="addThumbnailPreview" class="image-preview">
                        </div>
                    </div>
                    
                    <!-- Títulos -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-flag me-1"></i>Título (Español) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title_es" placeholder="Ej: Productos de Calidad" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-flag me-1"></i>Título (Inglés)</label>
                                <input type="text" class="form-control" name="title_en" placeholder="Ej: Quality Products">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contenido -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-align-left me-1"></i>Contenido (Español) <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="content_es" rows="3" placeholder="Descripción del slide en español..." required></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-align-left me-1"></i>Contenido (Inglés)</label>
                                <textarea class="form-control" name="content_en" rows="3" placeholder="Slide description in English..."></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Texto alternativo -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-info-circle me-1"></i>Texto Alt (Español) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="alt_text_es" placeholder="Descripción para accesibilidad" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-info-circle me-1"></i>Texto Alt (Inglés)</label>
                                <input type="text" class="form-control" name="alt_text_en" placeholder="Accessibility description">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="saveSlide()">
                    <i class="fas fa-save me-1"></i>Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar slide -->
<div class="modal fade" id="editSlideModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Editar Slide</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editSlideForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="editSlideId">
                    
                    <!-- Vista previa actual -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Imagen Actual:</label>
                            <img id="currentImage" src="" class="img-fluid" style="max-height: 150px; border-radius: 5px;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Thumbnail Actual:</label>
                            <img id="currentThumbnail" src="" class="img-fluid" style="max-height: 150px; border-radius: 5px;">
                        </div>
                    </div>
                    
                    <!-- Resto del formulario igual al de agregar -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-image me-1"></i>Nueva Imagen Principal</label>
                                <input type="file" class="form-control" id="editImageInput" name="image" accept="image/*" onchange="previewEditImage(this)">
                                <div class="form-text">Dejar vacío para mantener la actual</div>
                                
                                <!-- Preview y crop container -->
                                <div id="editImagePreviewContainer" class="image-preview-container mt-3" style="display: none;">
                                    <img id="editImagePreview" class="image-preview">
                                    <div class="mt-3">
                                        <button type="button" class="btn btn-sm btn-primary" onclick="cropEditImage()">
                                            <i class="fas fa-crop"></i> Recortar Imagen
                                        </button>
                                        <button type="button" class="btn btn-sm btn-secondary" onclick="resetEditImage()">
                                            <i class="fas fa-undo"></i> Restablecer
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Crop container -->
                                <div id="editCropContainer" class="crop-container" style="display: none;">
                                    <img id="editCropImage">
                                    <div class="text-center mt-3">
                                        <button type="button" class="btn btn-success" onclick="confirmEditCrop()">
                                            <i class="fas fa-check"></i> Confirmar Recorte
                                        </button>
                                        <button type="button" class="btn btn-secondary" onclick="cancelEditCrop()">
                                            <i class="fas fa-times"></i> Cancelar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-image me-1"></i>Nuevo Thumbnail</label>
                                <input type="file" class="form-control" id="editThumbnailInput" name="thumbnail" accept="image/*" onchange="previewEditThumbnail(this)">
                                <div class="form-text">Dejar vacío para mantener el actual</div>
                                
                                <!-- Preview y crop container -->
                                <div id="editThumbnailPreviewContainer" class="image-preview-container mt-3" style="display: none;">
                                    <img id="editThumbnailPreview" class="image-preview">
                                    <div class="mt-3">
                                        <button type="button" class="btn btn-sm btn-primary" onclick="cropEditThumbnail()">
                                            <i class="fas fa-crop"></i> Recortar Thumbnail
                                        </button>
                                        <button type="button" class="btn btn-sm btn-secondary" onclick="resetEditThumbnail()">
                                            <i class="fas fa-undo"></i> Restablecer
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Crop container -->
                                <div id="editThumbnailCropContainer" class="crop-container" style="display: none;">
                                    <img id="editThumbnailCropImage">
                                    <div class="text-center mt-3">
                                        <button type="button" class="btn btn-success" onclick="confirmEditThumbnailCrop()">
                                            <i class="fas fa-check"></i> Confirmar Recorte
                                        </button>
                                        <button type="button" class="btn btn-secondary" onclick="cancelEditThumbnailCrop()">
                                            <i class="fas fa-times"></i> Cancelar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-flag me-1"></i>Título (Español) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editTitleEs" name="title_es" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-flag me-1"></i>Título (Inglés)</label>
                                <input type="text" class="form-control" id="editTitleEn" name="title_en">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-align-left me-1"></i>Contenido (Español) <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="editContentEs" name="content_es" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-align-left me-1"></i>Contenido (Inglés)</label>
                                <textarea class="form-control" id="editContentEn" name="content_en" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-info-circle me-1"></i>Texto Alt (Español) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editAltEs" name="alt_text_es" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-info-circle me-1"></i>Texto Alt (Inglés)</label>
                                <input type="text" class="form-control" id="editAltEn" name="alt_text_en">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="updateSlide()">
                    <i class="fas fa-save me-1"></i>Actualizar
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- SortableJS CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
<!-- Cropper.js CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>

<script>
// Variables globales para los croppers
let addCropper = null;
let editCropper = null;

// Inicializar Sortable cuando se carga la página
document.addEventListener('DOMContentLoaded', function() {
    initializeSortable();
});

// Inicializar funcionalidad de arrastrar y soltar
function initializeSortable() {
    const sliderContainer = document.getElementById('slider-images');
    if (sliderContainer) {
        new Sortable(sliderContainer, {
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            handle: '.drag-handle',
            forceFallback: true,
            fallbackClass: 'being-dragged',
            onChoose: function (evt) {
                evt.item.classList.add('being-dragged');
                showToast('Arrastrando slide...', 'info');
            },
            onUnchoose: function (evt) {
                evt.item.classList.remove('being-dragged');
            },
            onStart: function (evt) {
                document.body.style.cursor = 'grabbing';
                evt.item.style.cursor = 'grabbing';
            },
            onEnd: function (evt) {
                document.body.style.cursor = '';
                evt.item.style.cursor = '';
                if (evt.oldIndex !== evt.newIndex) {
                    updateSliderOrder();
                }
            }
        });
    }
}

// Actualizar orden del slider después del drag & drop
function updateSliderOrder() {
    const items = document.querySelectorAll('.slider-item');
    const order = Array.from(items).map(item => item.dataset.id);
    
    // Mostrar mensaje de carga
    showToast('Actualizando orden...', 'info');
    
    // Deshabilitar drag & drop mientras se actualiza
    const handles = document.querySelectorAll('.drag-handle');
    handles.forEach(handle => {
        handle.style.pointerEvents = 'none';
        handle.style.opacity = '0.5';
    });
    
    fetch('{{ route("admin.slider.reorder") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            order: order
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Actualizar los badges de orden con animación
            items.forEach((item, index) => {
                const badge = item.querySelector('.badge');
                if (badge) {
                    // Agregar clase para animación
                    badge.style.transition = 'all 0.3s ease';
                    badge.style.transform = 'scale(1.2)';
                    badge.textContent = `Orden: ${index + 1}`;
                    
                    // Volver al tamaño normal
                    setTimeout(() => {
                        badge.style.transform = 'scale(1)';
                    }, 300);
                }
            });
            
            showToast('¡Orden actualizado correctamente!', 'success');
            
            // Recargar la página después de un breve delay
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            throw new Error(data.error || 'Error al actualizar el orden');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error al actualizar el orden: ' + error.message, 'error');
        
        // Recargar la página después de mostrar el error
        setTimeout(() => {
            location.reload();
        }, 2000);
    })
    .finally(() => {
        // Rehabilitar drag & drop
        handles.forEach(handle => {
            handle.style.pointerEvents = 'auto';
            handle.style.opacity = '';
        });
    });
}

// Función para mostrar el modal de agregar slide
function showAddSlideModal() {
    resetAddForms();
    new bootstrap.Modal(document.getElementById('addSlideModal')).show();
}

// Resetear formularios de agregar
function resetAddForms() {
    document.getElementById('addSlideForm').reset();
    document.getElementById('addImagePreviewContainer').style.display = 'none';
    document.getElementById('addCropContainer').style.display = 'none';
    document.getElementById('addThumbnailPreviewContainer').style.display = 'none';
    if (addCropper) {
        addCropper.destroy();
        addCropper = null;
    }
}

// Preview de imagen principal al agregar
function previewAddImage(input) {
    console.log('previewAddImage llamada con input:', input);
    console.log('input.files:', input.files);
    console.log('input.files[0]:', input.files ? input.files[0] : 'NO HAY ARCHIVOS');
    
    if (input.files && input.files[0]) {
        console.log('Archivo seleccionado:', input.files[0].name, 'Tamaño:', input.files[0].size);
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewContainer = document.getElementById('addImagePreviewContainer');
            const previewImg = document.getElementById('addImagePreview');
            
            previewImg.src = e.target.result;
            previewContainer.style.display = 'block';
            
            // Ocultar el crop container si estaba visible
            document.getElementById('addCropContainer').style.display = 'none';
            if (addCropper) {
                addCropper.destroy();
                addCropper = null;
            }
            
            console.log('Preview de imagen configurado correctamente');
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        console.log('No se seleccionó ningún archivo');
    }
}

// Preview de thumbnail al agregar
function previewAddThumbnail(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewContainer = document.getElementById('addThumbnailPreviewContainer');
            const previewImg = document.getElementById('addThumbnailPreview');
            
            previewImg.src = e.target.result;
            previewContainer.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Inicializar crop para agregar imagen
function cropAddImage() {
    const previewImg = document.getElementById('addImagePreview');
    const cropContainer = document.getElementById('addCropContainer');
    const cropImg = document.getElementById('addCropImage');
    
    // Copiar la imagen al contenedor de crop
    cropImg.src = previewImg.src;
    
    // Mostrar el contenedor de crop
    document.getElementById('addImagePreviewContainer').style.display = 'none';
    cropContainer.style.display = 'block';
    
    // Inicializar Cropper
    addCropper = new Cropper(cropImg, {
        aspectRatio: 3 / 1, // Ratio recomendado para slider
        viewMode: 1,
        autoCropArea: 0.8,
        responsive: true,
        background: false,
        guides: true,
        center: true,
        highlight: true,
        cropBoxMovable: true,
        cropBoxResizable: true,
        toggleDragModeOnDblclick: false
    });
}

// Confirmar crop para agregar
function confirmAddCrop() {
    if (addCropper) {
        const canvas = addCropper.getCroppedCanvas({
            width: 1200,
            height: 400,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high'
        });
        
        // Convertir canvas a blob y preparar para envío
        canvas.toBlob(function(blob) {
            // Crear un nuevo File object con nombre único
            const timestamp = Date.now();
            const file = new File([blob], `cropped-slider-${timestamp}.jpg`, { 
                type: 'image/jpeg',
                lastModified: Date.now()
            });
            
            // Crear un DataTransfer para simular la selección de archivo
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            
            // Usar el input hidden para enviar la imagen procesada
            const hiddenInput = document.getElementById('addImageFile');
            const originalInput = document.getElementById('addImageInput');
            
            // Asignar al input file original para el envío
            originalInput.files = dataTransfer.files;
            
            // Debug: verificar que se asignó correctamente
            console.log('Archivo después del crop:', originalInput.files[0]);
            console.log('Nombre del archivo:', originalInput.files[0] ? originalInput.files[0].name : 'NO HAY ARCHIVO');
            
            // Mostrar preview de la imagen recortada
            const previewImg = document.getElementById('addImagePreview');
            previewImg.src = canvas.toDataURL();
            
            // Ocultar crop y mostrar preview
            document.getElementById('addCropContainer').style.display = 'none';
            document.getElementById('addImagePreviewContainer').style.display = 'block';
            
            addCropper.destroy();
            addCropper = null;
            
            showToast('Imagen recortada correctamente', 'success');
        }, 'image/jpeg', 0.9);
    }
}

// Cancelar crop para agregar
function cancelAddCrop() {
    document.getElementById('addCropContainer').style.display = 'none';
    document.getElementById('addImagePreviewContainer').style.display = 'block';
    
    if (addCropper) {
        addCropper.destroy();
        addCropper = null;
    }
}

// Resetear imagen para agregar
function resetAddImage() {
    document.getElementById('addImageInput').value = '';
    document.getElementById('addImagePreviewContainer').style.display = 'none';
    document.getElementById('addCropContainer').style.display = 'none';
    
    if (addCropper) {
        addCropper.destroy();
        addCropper = null;
    }
}

// Función para guardar nuevo slide
function saveSlide() {
    const form = document.getElementById('addSlideForm');
    const imageInput = document.getElementById('addImageInput');
    
    // Debug: verificar si la imagen está seleccionada
    console.log('Input file:', imageInput);
    console.log('Files en input:', imageInput.files);
    console.log('Cantidad de archivos:', imageInput.files ? imageInput.files.length : 0);
    
    // Verificar que hay una imagen
    if (!imageInput.files || imageInput.files.length === 0) {
        showToast('Por favor selecciona una imagen para el slider', 'error');
        return;
    }
    
    // Crear FormData manualmente para asegurar que la imagen se incluya
    const formData = new FormData();
    
    // Agregar la imagen explícitamente
    formData.append('image', imageInput.files[0]);
    
    // Agregar todos los otros campos del formulario
    const formElements = form.elements;
    for (let i = 0; i < formElements.length; i++) {
        const element = formElements[i];
        if (element.name && element.type !== 'file' && element.name !== 'image') {
            if (element.type === 'checkbox' || element.type === 'radio') {
                if (element.checked) {
                    formData.append(element.name, element.value);
                }
            } else {
                formData.append(element.name, element.value);
            }
        }
    }
    
    // Debug: verificar el contenido del FormData
    console.log('FormData image después de agregar manualmente:', formData.get('image'));
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }
    
    fetch('{{ route("admin.slider.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showToast(data.error || 'Error al guardar slide', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error al guardar slide', 'error');
    });
}

// Función para editar slide
function editSlide(slideId) {
    try {
        console.log('Editando slide ID:', slideId);
        
        // Llenar el modal con el ID básico
        document.getElementById('editSlideId').value = slideId;
        
        // Obtener los datos completos via AJAX
        fetch(`{{ route("admin.slider.get", ":id") }}`.replace(':id', slideId), {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const slide = data.slide;
                
                // Llenar el formulario con los datos
                console.log('=== CARGANDO DATOS EN FORMULARIO ===');
                console.log('Datos del slide:', slide);
                
                const titleEsField = document.getElementById('editTitleEs');
                const titleEnField = document.getElementById('editTitleEn');
                const contentEsField = document.getElementById('editContentEs');
                const contentEnField = document.getElementById('editContentEn');
                const altEsField = document.getElementById('editAltEs');
                const altEnField = document.getElementById('editAltEn');
                
                titleEsField.value = slide.title_es || '';
                titleEnField.value = slide.title_en || '';
                contentEsField.value = slide.content_es || '';
                contentEnField.value = slide.content_en || '';
                altEsField.value = slide.alt_text_es || '';
                altEnField.value = slide.alt_text_en || '';
                
                console.log('Valores asignados:');
                console.log('Title ES:', titleEsField.value);
                console.log('Title EN:', titleEnField.value);
                console.log('Content ES:', contentEsField.value);
                console.log('Content EN:', contentEnField.value);
                console.log('Alt ES:', altEsField.value);
                console.log('Alt EN:', altEnField.value);
                
                // Mostrar imágenes actuales
                if (slide.path) {
                    document.getElementById('currentImage').src = '/' + slide.path;
                }
                if (slide.thumbnail_path) {
                    document.getElementById('currentThumbnail').src = '/' + slide.thumbnail_path;
                } else {
                    document.getElementById('currentThumbnail').src = '/' + slide.path;
                }
                
                console.log('Datos del slide cargados correctamente');
            } else {
                console.error('Error en la respuesta:', data);
                alert('Error al cargar datos del slide');
            }
        })
        .catch(error => {
            console.error('Error AJAX:', error);
            alert('Error al conectar con el servidor');
        });
        
        // Mostrar el modal
        const modal = new bootstrap.Modal(document.getElementById('editSlideModal'));
        modal.show();
        
    } catch (error) {
        console.error('Error en editSlide:', error);
        alert('Error al abrir el editor del slide');
    }
}

// Función para actualizar slide
function updateSlide() {
    const slideId = document.getElementById('editSlideId').value;
    const form = document.getElementById('editSlideForm');
    const formData = new FormData(form);
    
    // Debug: verificar los datos que se están enviando
    console.log('=== DEBUGGING UPDATE SLIDE ===');
    console.log('Slide ID:', slideId);
    console.log('Form:', form);
    
    // Verificar valores de los campos antes de enviar
    const titleEs = document.getElementById('editTitleEs').value;
    const titleEn = document.getElementById('editTitleEn').value;
    const contentEs = document.getElementById('editContentEs').value;
    const contentEn = document.getElementById('editContentEn').value;
    const altEs = document.getElementById('editAltEs').value;
    const altEn = document.getElementById('editAltEn').value;
    
    console.log('Valores de campos:');
    console.log('Title ES:', titleEs);
    console.log('Title EN:', titleEn);
    console.log('Content ES:', contentEs);
    console.log('Content EN:', contentEn);
    console.log('Alt ES:', altEs);
    console.log('Alt EN:', altEn);
    
    // Verificar FormData
    console.log('FormData contents:');
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }
    
    fetch(`{{ route("admin.slider.update", ":id") }}`.replace(':id', slideId), {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showToast(data.error || 'Error al actualizar slide', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error al actualizar slide', 'error');
    });
}

// Función para eliminar slide
function deleteSlide(slideId) {
    if (confirm('¿Estás seguro de que quieres eliminar este slide del slider?')) {
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('id', slideId);
        
        fetch('{{ route("admin.slider.destroy") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showToast(data.error || 'Error al eliminar slide', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error al eliminar slide', 'error');
        });
    }
}

// Las funciones moveUp y moveDown ya no son necesarias 
// porque ahora usamos drag & drop con SortableJS

// Resetear formularios de edición
function resetEditForms() {
    const form = document.getElementById('editSlideForm');
    // No hacer reset completo para mantener los datos cargados
    
    // Solo resetear elementos relacionados con nuevas imágenes
    document.getElementById('editImageInput').value = '';
    document.getElementById('editThumbnailInput').value = '';
    
    // Ocultar previews de nuevas imágenes
    document.getElementById('editImagePreviewContainer').style.display = 'none';
    document.getElementById('editCropContainer').style.display = 'none';
    document.getElementById('editThumbnailPreviewContainer').style.display = 'none';
    
    if (editCropper) {
        editCropper.destroy();
        editCropper = null;
    }
}

// Preview de imagen principal al editar
function previewEditImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewContainer = document.getElementById('editImagePreviewContainer');
            const previewImg = document.getElementById('editImagePreview');
            
            previewImg.src = e.target.result;
            previewContainer.style.display = 'block';
            
            // Ocultar el crop container si estaba visible
            document.getElementById('editCropContainer').style.display = 'none';
            if (editCropper) {
                editCropper.destroy();
                editCropper = null;
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Preview de thumbnail al editar
function previewEditThumbnail(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewContainer = document.getElementById('editThumbnailPreviewContainer');
            const previewImg = document.getElementById('editThumbnailPreview');
            
            previewImg.src = e.target.result;
            previewContainer.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Inicializar crop para editar imagen
function cropEditImage() {
    const previewImg = document.getElementById('editImagePreview');
    const cropContainer = document.getElementById('editCropContainer');
    const cropImg = document.getElementById('editCropImage');
    
    // Copiar la imagen al contenedor de crop
    cropImg.src = previewImg.src;
    
    // Mostrar el contenedor de crop
    document.getElementById('editImagePreviewContainer').style.display = 'none';
    cropContainer.style.display = 'block';
    
    // Inicializar Cropper
    editCropper = new Cropper(cropImg, {
        aspectRatio: 3 / 1, // Ratio recomendado para slider
        viewMode: 1,
        autoCropArea: 0.8,
        responsive: true,
        background: false,
        guides: true,
        center: true,
        highlight: true,
        cropBoxMovable: true,
        cropBoxResizable: true,
        toggleDragModeOnDblclick: false
    });
}

// Confirmar crop para editar
function confirmEditCrop() {
    if (editCropper) {
        const canvas = editCropper.getCroppedCanvas({
            width: 1200,
            height: 400,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high'
        });
        
        // Convertir canvas a blob y preparar para envío
        canvas.toBlob(function(blob) {
            // Crear un nuevo File object
            const file = new File([blob], 'cropped-image.jpg', { type: 'image/jpeg' });
            
            // Crear un DataTransfer para simular la selección de archivo
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            
            // Asignar al input file
            document.getElementById('editImageInput').files = dataTransfer.files;
            
            // Mostrar preview de la imagen recortada
            const previewImg = document.getElementById('editImagePreview');
            previewImg.src = canvas.toDataURL();
            
            // Ocultar crop y mostrar preview
            document.getElementById('editCropContainer').style.display = 'none';
            document.getElementById('editImagePreviewContainer').style.display = 'block';
            
            editCropper.destroy();
            editCropper = null;
            
            showToast('Imagen recortada correctamente', 'success');
        }, 'image/jpeg', 0.9);
    }
}

// Cancelar crop para editar
function cancelEditCrop() {
    document.getElementById('editCropContainer').style.display = 'none';
    document.getElementById('editImagePreviewContainer').style.display = 'block';
    
    if (editCropper) {
        editCropper.destroy();
        editCropper = null;
    }
}

// Resetear imagen para editar
function resetEditImage() {
    document.getElementById('editImageInput').value = '';
    document.getElementById('editImagePreviewContainer').style.display = 'none';
    document.getElementById('editCropContainer').style.display = 'none';
    
    if (editCropper) {
        editCropper.destroy();
        editCropper = null;
    }
}

// Inicializar crop para editar thumbnail
function cropEditThumbnail() {
    const previewImg = document.getElementById('editThumbnailPreview');
    const cropContainer = document.getElementById('editThumbnailCropContainer');
    const cropImg = document.getElementById('editThumbnailCropImage');
    
    // Copiar la imagen al contenedor de crop
    cropImg.src = previewImg.src;
    
    // Mostrar el contenedor de crop
    document.getElementById('editThumbnailPreviewContainer').style.display = 'none';
    cropContainer.style.display = 'block';
    
    // Inicializar Cropper
    editCropper = new Cropper(cropImg, {
        aspectRatio: 16 / 9, // Ratio para thumbnails
        viewMode: 1,
        autoCropArea: 0.8,
        responsive: true,
        background: false,
        guides: true,
        center: true,
        highlight: true,
        cropBoxMovable: true,
        cropBoxResizable: true,
        toggleDragModeOnDblclick: false
    });
}

// Confirmar crop para editar thumbnail
function confirmEditThumbnailCrop() {
    if (editCropper) {
        const canvas = editCropper.getCroppedCanvas({
            width: 400, // Ancho para thumbnails
            height: 225, // Height basado en ratio 16:9
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high'
        });
        
        // Convertir canvas a blob y preparar para envío
        canvas.toBlob(function(blob) {
            // Crear un nuevo File object
            const file = new File([blob], 'cropped-thumbnail.jpg', { type: 'image/jpeg' });
            
            // Crear un DataTransfer para simular la selección de archivo
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            
            // Asignar al input file
            document.getElementById('editThumbnailInput').files = dataTransfer.files;
            
            // Mostrar preview de la imagen recortada
            const previewImg = document.getElementById('editThumbnailPreview');
            previewImg.src = canvas.toDataURL();
            
            // Ocultar crop y mostrar preview
            document.getElementById('editThumbnailCropContainer').style.display = 'none';
            document.getElementById('editThumbnailPreviewContainer').style.display = 'block';
            
            editCropper.destroy();
            editCropper = null;
            
            showToast('Thumbnail recortado correctamente', 'success');
        }, 'image/jpeg', 0.9);
    }
}

// Cancelar crop para editar thumbnail
function cancelEditThumbnailCrop() {
    document.getElementById('editThumbnailCropContainer').style.display = 'none';
    document.getElementById('editThumbnailPreviewContainer').style.display = 'block';
    
    if (editCropper) {
        editCropper.destroy();
        editCropper = null;
    }
}

// Resetear thumbnail para editar
function resetEditThumbnail() {
    document.getElementById('editThumbnailInput').value = '';
    document.getElementById('editThumbnailPreviewContainer').style.display = 'none';
    document.getElementById('editThumbnailCropContainer').style.display = 'none';
    
    if (editCropper) {
        editCropper.destroy();
        editCropper = null;
    }
}

// Función para mostrar toast
function showToast(message, type) {
    const toastHtml = `
        <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-${type === 'success' ? 'check' : 'exclamation-triangle'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '10000';
        document.body.appendChild(toastContainer);
    }
    
    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    const toastElement = toastContainer.lastElementChild;
    new bootstrap.Toast(toastElement).show();
    
    toastElement.addEventListener('hidden.bs.toast', () => {
        toastElement.remove();
    });
}
</script>
@endpush
@endsection 