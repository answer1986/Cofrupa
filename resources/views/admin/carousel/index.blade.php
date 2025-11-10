@extends('admin.layout')

@section('title', 'Gestión del Carrusel')

@push('styles')
<!-- Cropper.js CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
<style>
/* ---------- Grid ---------- */
#carousel-images {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 24px;
    width: 100%;
    align-items: start;
    padding: 18px;
    box-sizing: border-box;
}

/* ---------- Card ---------- */
.carousel-item {
    width: 100%;
    display: block;
}

.carousel-item .card {
    border: 0;
    border-radius: 14px;
    background: #fff;
    box-shadow: 0 8px 30px rgba(20,30,40,0.06);
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
    position: relative;
}

/* header interior removed border and placed as overlay */
.carousel-item .card-header {
    display: none; /* ya no lo necesitamos; usamos overlay */
}

/* overlay controls (badge left + buttons right) */
.slide-overlay {
    position: absolute;
    top: 12px;
    left: 12px;
    right: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    pointer-events: none; /* para que sólo los botones reciban eventos */
}

/* badge a la izquierda */
.slide-badge {
    pointer-events: auto;
    background: rgba(255,255,255,0.92);
    color: #495057;
    padding: 6px 10px;
    border-radius: 8px;
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(15,20,30,0.06);
    font-size: 0.86rem;
}

/* contenedor de botones en la esquina derecha */
.slide-actions {
    pointer-events: auto;
    display: flex;
    gap: 8px;
    align-items: center;
}

/* botón editar (azul pequeño) */
.btn-edit {
    width: 42px;
    height: 42px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    font-size: 16px;
    box-shadow: 0 6px 18px rgba(3,169,244,0.15);
}

/* botón eliminar (gris/rojo) */
.btn-delete {
    width: 42px;
    height: 42px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    font-size: 16px;
}

/* Drag handle grande rojo (cuadrado) */
.drag-handle {
    width: 46px;
    height: 46px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: grab;
    background: linear-gradient(180deg,#e74c3c,#c0392b);
    color: #fff;
    box-shadow: 0 8px 20px rgba(199,27,48,0.22);
    border: 2px solid rgba(255,255,255,0.06);
    font-size: 18px;
}

/* Imagen (parte superior) */
.carousel-item .card-img-top {
    height: 200px;
    object-fit: cover;
    width: 100%;
    display: block;
    border-top-left-radius: 14px;
    border-top-right-radius: 14px;
}

/* cuerpo de la tarjeta */
.carousel-item .card-body {
    flex: 1;
    padding: 18px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

/* título y contenido */
.carousel-item .card-title {
    margin: 0;
    font-size: 1rem;
    font-weight: 700;
    color: #2c3e50;
}

.carousel-item .card-text {
    margin: 0;
    color: #65748b;
    font-size: 0.92rem;
    flex: 1;
}

/* mini-thumb debajo (si quieres una miniatura) */
.card-thumb {
    width: 56px;
    height: 34px;
    object-fit: cover;
    border-radius: 6px;
    border: 1px solid rgba(0,0,0,0.06);
    box-shadow: 0 4px 12px rgba(0,0,0,0.04);
}

/* Hover */
.carousel-item:hover .card {
    transform: translateY(-6px);
    transition: transform .25s ease;
    box-shadow: 0 20px 40px rgba(10,25,40,0.08);
}

/* Sortable states */
.carousel-item.sortable-ghost { opacity: 0.35; transform: scale(.98); }
.carousel-item.sortable-chosen { z-index:1000; transform: scale(1.02); }

/* Responsive tweaks */
@media (max-width: 768px) {
    #carousel-images { gap: 16px; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); }
    .carousel-item .card-img-top { height: 160px; }
}

</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-images"></i> Gestión del Carrusel</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" onclick="showAddSlideModal()">
                        <i class="fas fa-plus"></i> Agregar Imagen
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Instrucciones -->
                @if($images->count() > 1)
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> 
                    <strong>Arrastra las imágenes</strong> para cambiar el orden del carrusel. Los cambios se guardan automáticamente.
                </div>
                @endif
                
                <div id="carousel-images" style="min-height: 200px; border: 2px dashed blue;">
                    @foreach($images as $image)
                    <div class="carousel-item debug-border" data-id="{{ $image->id }}">
                        <div class="card h-100 debug-bg">
                            <div class="card-header p-2 d-flex justify-content-between align-items-center">
                                <span class="badge bg-primary">Orden: {{ $image->carousel_order }}</span>
                                <div class="d-flex">
                                    <div class="drag-handle me-2">
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
                            <img src="/{{ $image->path }}" class="card-img-top"
                                style="height: 200px; object-fit: cover; width: 100%; border: 1px solid green;">
                            
                            <div class="card-body p-3">
                                <h6 class="card-title">{{ $image->title_es }}</h6>
                                <p class="card-text">{{ Str::limit($image->content_es, 60) }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                @if($images->count() == 0)
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> No hay imágenes en el carrusel aún.
                    <br>Haz clic en "Agregar Imagen" para comenzar.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar imagen -->
<div class="modal fade" id="addSlideModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Agregar Imagen al Carrusel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addSlideForm" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Imagen Principal -->
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-image me-1"></i>Imagen Principal <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="addImageInput" name="image" accept="image/*" required onchange="previewAddImage(this)">
                        <div class="form-text">Imagen para el carrusel (recomendado: 800x600px)</div>
                        
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
                    
                    <!-- Títulos -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-flag me-1"></i>Título (Español) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title_es" placeholder="Ej: Frutas Secas" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                    <div class="mb-3">
                                <label class="form-label"><i class="fas fa-flag me-1"></i>Título (Inglés)</label>
                                <input type="text" class="form-control" name="title_en" placeholder="Ej: Dried Fruits">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contenido -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-align-left me-1"></i>Contenido (Español) <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="content_es" rows="3" placeholder="Descripción de la imagen en español..." required></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                    <div class="mb-3">
                                <label class="form-label"><i class="fas fa-align-left me-1"></i>Contenido (Inglés)</label>
                                <textarea class="form-control" name="content_en" rows="3" placeholder="Image description in English..."></textarea>
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

<!-- Modal para editar imagen -->
<div class="modal fade" id="editSlideModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Editar Imagen del Carrusel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editSlideForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="editSlideId">
                    
                    <!-- Vista previa actual -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Imagen Actual:</label>
                            <img id="currentImage" src="" class="img-fluid" style="max-height: 200px; border-radius: 5px;">
                        </div>
                    </div>
                    
                    <!-- Nueva imagen -->
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


document.addEventListener('DOMContentLoaded', function() {
    // Verificar todas las imágenes
    const images = document.querySelectorAll('.card-img-top');
    console.log('Número de imágenes encontradas:', images.length);
    
    images.forEach((img, index) => {
        console.log(`Imagen ${index + 1}:`, img.src);
        
        // Verificar si la imagen se carga correctamente
        img.onload = function() {
            console.log(`✓ Imagen ${index + 1} cargada correctamente:`, img.src);
        };
        
        img.onerror = function() {
            console.error(`✗ Error al cargar imagen ${index + 1}:`, img.src);
            img.style.border = '3px solid red';
            img.alt = 'Error al cargar imagen';
        };
    });
    
    // Verificar estructura del grid
    const grid = document.getElementById('carousel-images');
    console.log('Estilo del grid:', window.getComputedStyle(grid).display);
});






// Variables globales para los croppers
let addCropper = null;
let editCropper = null;

// Inicializar Sortable cuando se carga la página
document.addEventListener('DOMContentLoaded', function() {
    initializeSortable();
});

// Inicializar funcionalidad de arrastrar y soltar
function initializeSortable() {
    const carouselContainer = document.getElementById('carousel-images');
    if (carouselContainer) {
        new Sortable(carouselContainer, {
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            handle: '.drag-handle',
            forceFallback: true,
            fallbackClass: 'being-dragged',
            onChoose: function (evt) {
                evt.item.classList.add('being-dragged');
                showToast('Arrastrando imagen...', 'info');
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
                    updateCarouselOrder();
                }
            }
        });
    }
}

// Actualizar orden del carrusel después del drag & drop
function updateCarouselOrder() {
    const items = document.querySelectorAll('.carousel-item');
    const order = Array.from(items).map(item => item.dataset.id);
    
    // Mostrar mensaje de carga
    showToast('Actualizando orden...', 'info');
    
    // Deshabilitar drag & drop mientras se actualiza
    const handles = document.querySelectorAll('.drag-handle');
    handles.forEach(handle => {
        handle.style.pointerEvents = 'none';
        handle.style.opacity = '0.5';
    });
    
    fetch('{{ route("admin.carousel.reorder") }}', {
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

// Función para mostrar el modal de agregar imagen
function showAddSlideModal() {
    resetAddForms();
    new bootstrap.Modal(document.getElementById('addSlideModal')).show();
}

// Resetear formularios de agregar
function resetAddForms() {
    document.getElementById('addSlideForm').reset();
    document.getElementById('addImagePreviewContainer').style.display = 'none';
    document.getElementById('addCropContainer').style.display = 'none';
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
        aspectRatio: 4 / 3, // Ratio recomendado para carrusel
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
            width: 800,
            height: 600,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high'
        });
        
        // Convertir canvas a blob y preparar para envío
        canvas.toBlob(function(blob) {
            // Crear un nuevo File object con nombre único
            const timestamp = Date.now();
            const file = new File([blob], `cropped-carousel-${timestamp}.jpg`, { 
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

// Función para guardar nueva imagen
function saveSlide() {
    const form = document.getElementById('addSlideForm');
    const imageInput = document.getElementById('addImageInput');
    
    // Debug: verificar si la imagen está seleccionada
    console.log('Input file:', imageInput);
    console.log('Files en input:', imageInput.files);
    console.log('Cantidad de archivos:', imageInput.files ? imageInput.files.length : 0);
    
    // Verificar que hay una imagen
    if (!imageInput.files || imageInput.files.length === 0) {
        showToast('Por favor selecciona una imagen para el carrusel', 'error');
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
    
    fetch('{{ route("admin.carousel.store") }}', {
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
            showToast(data.error || 'Error al guardar imagen', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error al guardar imagen', 'error');
    });
}

// Función para editar imagen
function editSlide(slideId) {
    try {
        console.log('Editando imagen ID:', slideId);
        
        // Llenar el modal con el ID básico
        document.getElementById('editSlideId').value = slideId;
        
        // Obtener los datos completos via AJAX
        fetch(`{{ route("admin.carousel.get", ":id") }}`.replace(':id', slideId), {
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
                
                titleEsField.value = slide.title_es || slide.alt_text_es || '';
                titleEnField.value = slide.title_en || slide.alt_text_en || '';
                contentEsField.value = slide.content_es || slide.description || '';
                contentEnField.value = slide.content_en || slide.description || '';
                altEsField.value = slide.alt_text_es || '';
                altEnField.value = slide.alt_text_en || '';
                
                console.log('Valores asignados:');
                console.log('Title ES:', titleEsField.value);
                console.log('Title EN:', titleEnField.value);
                console.log('Content ES:', contentEsField.value);
                console.log('Content EN:', contentEnField.value);
                console.log('Alt ES:', altEsField.value);
                console.log('Alt EN:', altEnField.value);
                
                // Mostrar imagen actual
                if (slide.path) {
                    document.getElementById('currentImage').src = '/' + slide.path;
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

// Función para actualizar imagen
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
    
    fetch(`{{ route("admin.carousel.update", ":id") }}`.replace(':id', slideId), {
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
            showToast(data.error || 'Error al actualizar imagen', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error al actualizar imagen', 'error');
    });
}

// Función para eliminar imagen
function deleteSlide(slideId) {
    if (confirm('¿Estás seguro de que quieres eliminar esta imagen del carrusel?')) {
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('id', slideId);
        
        fetch('{{ route("admin.carousel.destroy") }}', {
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
                showToast(data.error || 'Error al eliminar imagen', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error al eliminar imagen', 'error');
        });
    }
}

// Funciones de edición de imágenes
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

function cropEditImage() {
    const previewImg = document.getElementById('editImagePreview');
    const cropContainer = document.getElementById('editCropContainer');
    const cropImg = document.getElementById('editCropImage');
    
    cropImg.src = previewImg.src;
    document.getElementById('editImagePreviewContainer').style.display = 'none';
    cropContainer.style.display = 'block';
    
    editCropper = new Cropper(cropImg, {
        aspectRatio: 4 / 3,
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

function confirmEditCrop() {
    if (editCropper) {
        const canvas = editCropper.getCroppedCanvas({
            width: 800,
            height: 600,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high'
        });
        
        canvas.toBlob(function(blob) {
            const file = new File([blob], 'cropped-image.jpg', { type: 'image/jpeg' });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            document.getElementById('editImageInput').files = dataTransfer.files;
            
            const previewImg = document.getElementById('editImagePreview');
            previewImg.src = canvas.toDataURL();
            
            document.getElementById('editCropContainer').style.display = 'none';
            document.getElementById('editImagePreviewContainer').style.display = 'block';
            
            editCropper.destroy();
            editCropper = null;
            
            showToast('Imagen recortada correctamente', 'success');
        }, 'image/jpeg', 0.9);
    }
}

function cancelEditCrop() {
    document.getElementById('editCropContainer').style.display = 'none';
    document.getElementById('editImagePreviewContainer').style.display = 'block';
    
    if (editCropper) {
        editCropper.destroy();
        editCropper = null;
    }
}

function resetEditImage() {
    document.getElementById('editImageInput').value = '';
    document.getElementById('editImagePreviewContainer').style.display = 'none';
    document.getElementById('editCropContainer').style.display = 'none';
    
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