@if(session('admin_authenticated'))
<!-- CDN para Cropper.js -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

<!-- Inline Editor Styles -->
<style>
.edit-icon {
    transition: all 0.3s ease;
}

.edit-icon:hover {
    opacity: 1 !important;
    transform: scale(1.2);
}

.inline-editor-bar {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    padding: 10px 20px;
    z-index: 9999;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.inline-editor-bar .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.inline-editor-bar h5 {
    margin: 0;
    font-size: 14px;
}

.inline-editor-bar .btn {
    font-size: 12px;
    padding: 5px 15px;
}

body {
    padding-top: 60px;
}

/* Modal styles */
.modal-content {
    border-radius: 10px;
}

/* Estilos para Cropper.js */
.crop-container {
    max-width: 100%;
    max-height: 400px;
    margin: 20px 0;
}

.crop-container img {
    max-width: 100%;
    height: auto;
}

.crop-controls {
    margin-top: 15px;
    text-align: center;
}

.crop-controls .btn {
    margin: 0 5px;
}

.image-preview-container {
    display: none;
    margin: 15px 0;
    text-align: center;
}

.image-preview-container img {
    max-width: 100%;
    max-height: 200px;
    border: 2px dashed #007bff;
    border-radius: 8px;
    padding: 10px;
}

.modal-header {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    border-radius: 10px 10px 0 0;
}

.btn-primary {
    background: linear-gradient(135deg, #007bff, #0056b3);
    border: none;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}
</style>

<!-- Inline Editor Bar -->
<div class="inline-editor-bar">
    <div class="container">
        <div>
            <h5><i class="fas fa-edit me-2"></i>Modo Edición Activado</h5>
            <small>Haz clic en los lapicitos para editar contenido</small>
        </div>
        <div>
            <form action="{{ route('admin.logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-light btn-sm">
                    <i class="fas fa-sign-out-alt me-1"></i>Salir del Modo Edición
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Text Edit Modal -->
<div class="modal fade" id="textEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Editar Texto</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="textEditForm">
                    <input type="hidden" id="textKey">
                    <input type="hidden" id="textSection">
                    <input type="hidden" id="textType">
                    
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-flag me-1"></i>Texto en Español</label>
                        <textarea class="form-control" id="textValueEs" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-flag me-1"></i>Texto en Inglés</label>
                        <textarea class="form-control" id="textValueEn" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="saveText()">
                    <i class="fas fa-save me-1"></i>Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Image Edit Modal -->
<div class="modal fade" id="imageEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-image me-2"></i>Cambiar Imagen</h5>
                <button type="button" class="btn-close btn-close-white" onclick="closeImageModal()"></button>
            </div>
            <div class="modal-body">
                <form id="imageEditForm" enctype="multipart/form-data">
                    <input type="hidden" id="imageKey">
                    <input type="hidden" id="imageSection">
                    
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-upload me-1"></i>Nueva Imagen</label>
                        <input type="file" class="form-control" id="imageFile" accept="image/*" required>
                        <div class="form-text">Formatos soportados: JPG, PNG, GIF, SVG, WebP, AVIF (máx. 10MB)</div>
                    </div>
                    
                    <!-- Preview de imagen original -->
                    <div class="image-preview-container" id="imagePreviewContainer">
                        <h6><i class="fas fa-eye me-1"></i>Vista previa:</h6>
                        <img id="imagePreview" src="" alt="Vista previa">
                        <div class="mt-2">
                            <button type="button" class="btn btn-primary btn-sm" onclick="startCropping()">
                                <i class="fas fa-crop me-1"></i>Recortar Imagen
                            </button>
                        </div>
                    </div>
                    
                    <!-- Contenedor del cropper -->
                    <div class="crop-container" id="cropContainer" style="display: none;">
                        <h6><i class="fas fa-crop me-1"></i>Recortar imagen:</h6>
                        <img id="cropImage" src="">
                        <div class="crop-controls">
                            <button type="button" class="btn btn-success btn-sm" onclick="confirmCrop()">
                                <i class="fas fa-check me-1"></i>Confirmar Recorte
                            </button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="cancelCrop()">
                                <i class="fas fa-times me-1"></i>Cancelar
                            </button>
                            <button type="button" class="btn btn-warning btn-sm" onclick="resetCrop()">
                                <i class="fas fa-undo me-1"></i>Restablecer
                            </button>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-flag me-1"></i>Texto alternativo (Español)</label>
                        <input type="text" class="form-control" id="altTextEs">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-flag me-1"></i>Texto alternativo (Inglés)</label>
                        <input type="text" class="form-control" id="altTextEn">
                    </div>
                    
                    <!-- Campo de orden solo para sliders -->
                    <div class="mb-3" id="sliderOrderSection" style="display: none;">
                        <label class="form-label"><i class="fas fa-sort me-1"></i>Orden en el Slider</label>
                        <select class="form-control" id="sliderOrder">
                            <option value="1">1 - Primero</option>
                            <option value="2">2 - Segundo</option>
                            <option value="3">3 - Tercero</option>
                            <option value="4">4 - Cuarto</option>
                            <option value="5">5 - Quinto</option>
                            <option value="6">6 - Sexto</option>
                            <option value="7">7 - Séptimo</option>
                            <option value="8">8 - Octavo</option>
                            <option value="9">9 - Noveno</option>
                            <option value="10">10 - Décimo</option>
                        </select>
                        <div class="form-text">Selecciona la posición donde quieres que aparezca esta imagen en el slider</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeImageModal()">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="saveImage()">
                    <i class="fas fa-save me-1"></i>Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap específico para el editor inline (sin conflictos) -->
<script>
// Verificar y cargar jQuery si no está disponible
if (typeof jQuery === 'undefined') {
    const jqueryScript = document.createElement('script');
    jqueryScript.src = 'https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js';
    document.head.appendChild(jqueryScript);
}

// Verificar y cargar Bootstrap si no está disponible
if (typeof bootstrap === 'undefined') {
    const bootstrapScript = document.createElement('script');
    bootstrapScript.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js';
    document.head.appendChild(bootstrapScript);
}
</script>

<!-- Inline Editor JavaScript -->
<script>
// FUNCIÓN INMEDIATA - No esperar nada, ejecutar ahora mismo
(function() {
    console.log('=== EDITOR INLINE CARGANDO INMEDIATAMENTE ===');
    
    let currentElement = null;

    // Función completamente independiente para editar imagen
    window.editImage = function(key, section) {
        console.log('=== EDITANDO IMAGEN (FUNCIÓN INDEPENDIENTE) ===');
        console.log('Key:', key);
        console.log('Section:', section);
        
        try {
            // Llenar campos del modal
            const keyField = document.getElementById('imageKey');
            const sectionField = document.getElementById('imageSection');
            
            if (keyField) keyField.value = key;
            if (sectionField) sectionField.value = section;
            
            // Mostrar/ocultar campo de orden para slider
            const orderSection = document.getElementById('sliderOrderSection');
            if (orderSection) {
                if (section === 'slider' || key.includes('slider_thumb')) {
                    orderSection.style.display = 'block';
                    
                    // Extraer número de orden actual si existe
                    const orderMatch = key.match(/(\d+)$/);
                    if (orderMatch) {
                        const currentOrder = orderMatch[1];
                        const orderSelect = document.getElementById('sliderOrder');
                        if (orderSelect) {
                            orderSelect.value = currentOrder;
                        }
                    }
                } else {
                    orderSection.style.display = 'none';
                }
            }
            
            console.log('Valores establecidos en campos ocultos');
            
            // Mostrar modal sin Bootstrap (método directo)
            const modal = document.getElementById('imageEditModal');
            if (modal) {
                modal.style.display = 'block';
                modal.classList.add('show');
                modal.style.backgroundColor = 'rgba(0,0,0,0.5)';
                modal.style.zIndex = '9999';
                
                // Agregar backdrop si no existe
                let backdrop = document.querySelector('.modal-backdrop');
                if (!backdrop) {
                    backdrop = document.createElement('div');
                    backdrop.className = 'modal-backdrop fade show';
                    document.body.appendChild(backdrop);
                }
                
                console.log('Modal mostrado exitosamente (método directo)');
            } else {
                console.error('Modal no encontrado');
                alert('Error: Modal no encontrado en la página');
            }
        } catch (error) {
            console.error('Error en editImage:', error);
            alert('Error al abrir el editor de imagen: ' + error.message);
        }
    };
    
    // Función para cerrar modal
    window.closeImageModal = function() {
        // Limpiar cropper si existe
        if (window.cropper) {
            window.cropper.destroy();
            window.cropper = null;
        }
        
        // Ocultar contenedores
        const previewContainer = document.getElementById('imagePreviewContainer');
        const cropContainer = document.getElementById('cropContainer');
        if (previewContainer) previewContainer.style.display = 'none';
        if (cropContainer) cropContainer.style.display = 'none';
        
        // Limpiar formulario
        document.getElementById('imageEditForm').reset();
        
        const modal = document.getElementById('imageEditModal');
        if (modal) {
            modal.style.display = 'none';
            modal.classList.remove('show');
        }
        
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
            backdrop.remove();
        }
    };
    
    // Event listener para preview de imagen
    document.addEventListener('change', function(e) {
        if (e.target && e.target.id === 'imageFile') {
            const file = e.target.files[0];
            if (file) {
                previewImage(file);
            }
        }
    });

    console.log('=== FUNCIONES BÁSICAS CARGADAS ===');
})();

// Cargar el resto cuando sea posible
setTimeout(function() {
    console.log('=== CARGANDO FUNCIONES COMPLETAS ===');
    
    let currentElement = null;

function editContent(key, section, type, element) {
    currentElement = element.parentElement;
    
    document.getElementById('textKey').value = key;
    document.getElementById('textSection').value = section;
    document.getElementById('textType').value = type;
    
    // Obtener texto actual
    const currentText = currentElement.childNodes[0].textContent.trim();
    document.getElementById('textValueEs').value = currentText;
    document.getElementById('textValueEn').value = '';
    
    // Cambiar textarea/input según el tipo
    const textValueEs = document.getElementById('textValueEs');
    const textValueEn = document.getElementById('textValueEn');
    
    if (type === 'textarea' || type === 'html') {
        textValueEs.rows = 5;
        textValueEn.rows = 5;
    } else {
        textValueEs.rows = 2;
        textValueEn.rows = 2;
    }
    
    const textModal = new bootstrap.Modal(document.getElementById('textEditModal'));
    textModal.show();
}

function editImage(key, section) {
    console.log('=== EDITANDO IMAGEN ===');
    console.log('Key:', key);
    console.log('Section:', section);
    
    try {
        document.getElementById('imageKey').value = key;
        document.getElementById('imageSection').value = section;
        
        // Mostrar/ocultar campo de orden para slider
        const orderSection = document.getElementById('sliderOrderSection');
        if (orderSection) {
            if (section === 'slider' || key.includes('slider_thumb')) {
                orderSection.style.display = 'block';
                
                // Extraer el ID del slider de la key
                const slideId = key.split('_').pop();
                
                // Obtener el orden actual del slider
                fetch(`/admin/slider/get/${slideId}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.slide) {
                        const orderSelect = document.getElementById('sliderOrder');
                        if (orderSelect) {
                            orderSelect.value = data.slide.slider_order || 1;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error al obtener orden del slider:', error);
                });
            } else {
                orderSection.style.display = 'none';
            }
        }
        
        console.log('Valores establecidos en campos ocultos');
        
        const imageModal = new bootstrap.Modal(document.getElementById('imageEditModal'));
        console.log('Modal creado, mostrando...');
        imageModal.show();
        console.log('Modal mostrado exitosamente');
    } catch (error) {
        console.error('Error en editImage:', error);
        alert('Error al abrir el editor de imagen: ' + error.message);
    }
}

function deleteImage(key, section) {
    console.log('=== INICIO deleteImage FRONTEND ===');
    console.log('Key:', key);
    console.log('Section:', section);
    
    // Verificar si existe el meta tag CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('ERROR: No se encontró meta tag csrf-token');
        alert('Error: Token CSRF no encontrado en la página');
        return;
    }
    
    const token = csrfToken.getAttribute('content');
    console.log('CSRF Token encontrado:', token);
    
    if (confirm('¿Estás seguro de que quieres restaurar la imagen original? Esto eliminará la imagen personalizada.')) {
        console.log('Usuario confirmó eliminación');
        
        const formData = new FormData();
        formData.append('_token', token);
        formData.append('key', key);
        formData.append('section', section);

        console.log('FormData preparado:', {
            key: key,
            section: section,
            token: token ? token.substring(0, 10) + '...' : 'null'
        });

        const url = '{{ route("api.image.delete") }}';
        console.log('URL destino:', url);

        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('=== RESPUESTA RECIBIDA ===');
            console.log('Status:', response.status);
            console.log('Status Text:', response.statusText);
            console.log('Headers:', response.headers);
            console.log('OK:', response.ok);
            
            // Intentar leer como texto primero para debug
            return response.text().then(text => {
                console.log('Response text:', text);
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText} - ${text}`);
                }
                
                try {
                    return JSON.parse(text);
                } catch (e) {
                    throw new Error('Response is not valid JSON: ' + text);
                }
            });
        })
        .then(data => {
            console.log('=== DATOS PARSEADOS ===');
            console.log('Response data:', data);
            
            if (data.success) {
                console.log('Éxito! Mensaje:', data.message);
                showToast(data.message, 'success');
                // Recargar la página para mostrar la imagen original
                setTimeout(() => {
                    console.log('Recargando página...');
                    location.reload();
                }, 1500);
            } else {
                console.error('Error en respuesta exitosa:', data);
                showToast(data.error || 'Error al restaurar la imagen', 'error');
            }
        })
        .catch(error => {
            console.error('=== ERROR COMPLETO ===');
            console.error('Error:', error);
            console.error('Stack:', error.stack);
            showToast('Error: ' + error.message, 'error');
        });
    } else {
        console.log('Usuario canceló eliminación');
    }
}

function saveText() {
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('key', document.getElementById('textKey').value);
    formData.append('section', document.getElementById('textSection').value);
    formData.append('value_es', document.getElementById('textValueEs').value);
    formData.append('value_en', document.getElementById('textValueEn').value);
    
    fetch('{{ route("api.content.update") }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Actualizar el texto en la página
            if (currentElement) {
                const newText = document.getElementById('textValueEs').value || document.getElementById('textValueEn').value;
                currentElement.childNodes[0].textContent = newText;
            }
            
            const textModal = bootstrap.Modal.getInstance(document.getElementById('textEditModal'));
            if (textModal) {
                textModal.hide();
            }
            showToast('Texto actualizado correctamente', 'success');
        } else {
            showToast('Error al actualizar el texto', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error al actualizar el texto', 'error');
    });
}

function saveImage() {
    console.log('=== GUARDANDO IMAGEN ===');
    
    const key = document.getElementById('imageKey').value;
    const section = document.getElementById('imageSection').value;
    const imageFile = document.getElementById('imageFile').files[0];
    
    console.log('Key:', key);
    console.log('Section:', section);
    console.log('Archivo seleccionado:', imageFile);
    
    if (!imageFile) {
        console.error('No se seleccionó archivo');
        showToast('Por favor selecciona una imagen', 'error');
        return;
    }
    
    console.log('Tamaño del archivo:', imageFile.size, 'bytes');
    console.log('Tipo de archivo:', imageFile.type);
    
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('key', key);
    formData.append('section', section);
    formData.append('image', imageFile);
    formData.append('alt_text_es', document.getElementById('altTextEs').value);
    formData.append('alt_text_en', document.getElementById('altTextEn').value);
    
    // Si es una imagen del slider, agregar orden
    const orderSection = document.getElementById('sliderOrderSection');
    if (orderSection && orderSection.style.display !== 'none') {
        const sliderOrder = document.getElementById('sliderOrder').value;
        formData.append('slider_order', sliderOrder);
        console.log('Orden del slider:', sliderOrder);
    }
    
    console.log('FormData preparado, enviando...');
    
    fetch('{{ route("api.image.update") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Respuesta recibida, status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Datos de respuesta:', data);
        
        if (data.success) {
            console.log('Éxito! Nueva ruta:', data.new_path);
            
            // Actualizar la imagen en la página
            const imgElement = document.getElementById('img-' + key);
            if (imgElement) {
                console.log('Actualizando imagen en DOM');
                imgElement.src = data.new_path + '?t=' + Date.now(); // Cache bust
            } else {
                console.error('No se encontró elemento img con ID: img-' + key);
            }
            
            // Si se cambió el orden del slider, actualizarlo
            const orderSection = document.getElementById('sliderOrderSection');
            if (orderSection && orderSection.style.display !== 'none') {
                const newOrder = document.getElementById('sliderOrder').value;
                console.log('Actualizando orden del slider a:', newOrder);
                
                // Extraer el ID del slider de la key (por ejemplo, de "slider_thumb_5" obtener "5")
                const slideId = key.split('_').pop();
                
                fetch('/admin/slider/update-order', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        id: slideId,
                        new_order: parseInt(newOrder)
                    })
                })
                .then(response => response.json())
                .then(orderData => {
                    if (orderData.success) {
                        console.log('Orden actualizado exitosamente');
                        showToast('Imagen y orden actualizados correctamente', 'success');
                        
                        // Recargar página para mostrar nuevo orden
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        console.error('Error al actualizar orden:', orderData.error);
                        showToast('Imagen actualizada, pero error al cambiar orden', 'warning');
                    }
                })
                .catch(error => {
                    console.error('Error al actualizar orden:', error);
                    showToast('Imagen actualizada, pero error al cambiar orden', 'warning');
                });
            } else {
                showToast('Imagen actualizada correctamente', 'success');
            }

            const imageModal = bootstrap.Modal.getInstance(document.getElementById('imageEditModal'));
            if (imageModal) {
                imageModal.hide();
            }
            
            // Limpiar formulario
            document.getElementById('imageEditForm').reset();
        } else {
            console.error('Error en respuesta:', data);
            showToast(data.error || 'Error al actualizar la imagen', 'error');
        }
    })
    .catch(error => {
        console.error('Error completo:', error);
        showToast('Error al actualizar la imagen: ' + error.message, 'error');
    });
}

function showToast(message, type) {
    // Crear toast notification
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
    
    // Crear contenedor si no existe
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '10000';
        document.body.appendChild(toastContainer);
    }
    
    // Agregar toast
    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    const toastElement = toastContainer.lastElementChild;
    new bootstrap.Toast(toastElement).show();
    
    // Remover después de mostrar
    toastElement.addEventListener('hidden.bs.toast', () => {
        toastElement.remove();
    });
}

    // Variables globales para cropper
    let cropper = null;
    let originalFile = null;

    // Función para previsualizar imagen
    window.previewImage = function(file) {
        console.log('=== PREVISUALIZANDO IMAGEN ===');
        originalFile = file;
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('imagePreview');
            preview.src = e.target.result;
            
            document.getElementById('imagePreviewContainer').style.display = 'block';
            document.getElementById('cropContainer').style.display = 'none';
            
            // Limpiar cropper anterior si existe
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
        };
        reader.readAsDataURL(file);
    };

    // Función para iniciar cropping
    window.startCropping = function() {
        console.log('=== INICIANDO CROPPING ===');
        
        const preview = document.getElementById('imagePreview');
        const cropImage = document.getElementById('cropImage');
        
        // Copiar imagen al contenedor de crop
        cropImage.src = preview.src;
        
        // Mostrar contenedor de crop y ocultar preview
        document.getElementById('cropContainer').style.display = 'block';
        document.getElementById('imagePreviewContainer').style.display = 'none';
        
        // Inicializar cropper
        setTimeout(() => {
            cropper = new Cropper(cropImage, {
                aspectRatio: 16 / 9, // Ratio típico para sliders
                viewMode: 1,
                autoCropArea: 0.8,
                responsive: true,
                background: false,
                zoomable: true,
                scalable: true,
                cropBoxResizable: true,
                cropBoxMovable: true
            });
            console.log('Cropper inicializado');
        }, 100);
    };

    // Función para confirmar crop
    window.confirmCrop = function() {
        console.log('=== CONFIRMANDO CROP ===');
        
        if (!cropper) {
            console.error('Cropper no inicializado');
            return;
        }
        
        // Obtener canvas con imagen croppeada
        const canvas = cropper.getCroppedCanvas({
            width: 800, // Ancho fijo para optimización
            height: 450, // Height basado en ratio 16:9
            imageSmoothingQuality: 'high'
        });
        
        // Convertir canvas a blob
        canvas.toBlob(function(blob) {
            // Crear nuevo File object
            const croppedFile = new File([blob], originalFile.name, {
                type: originalFile.type,
                lastModified: Date.now()
            });
            
            // Reemplazar archivo en input
            const dt = new DataTransfer();
            dt.items.add(croppedFile);
            document.getElementById('imageFile').files = dt.files;
            
            console.log('Imagen croppeada aplicada al input');
            
            // Ocultar cropper
            document.getElementById('cropContainer').style.display = 'none';
            
            // Actualizar preview
            const preview = document.getElementById('imagePreview');
            preview.src = canvas.toDataURL();
            document.getElementById('imagePreviewContainer').style.display = 'block';
            
            showToast('Imagen recortada correctamente', 'success');
        }, originalFile.type, 0.9);
    };

    // Función para cancelar crop
    window.cancelCrop = function() {
        console.log('=== CANCELANDO CROP ===');
        
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        
        document.getElementById('cropContainer').style.display = 'none';
        document.getElementById('imagePreviewContainer').style.display = 'block';
    };

    // Función para resetear crop
    window.resetCrop = function() {
        console.log('=== RESETEANDO CROP ===');
        
        if (cropper) {
            cropper.reset();
        }
    };

    // Hacer las funciones globales para que se puedan llamar desde el HTML
    window.editContent = editContent;
    window.editImage = editImage;
    window.deleteImage = deleteImage;
    window.saveText = saveText;
    window.saveImage = saveImage;
    window.showToast = showToast;
    
    console.log('=== EDITOR INLINE INICIALIZADO CORRECTAMENTE ===');
});

// Función de respaldo en caso de que los scripts problemáticos impidan la carga
setTimeout(function() {
    if (typeof window.editImage === 'undefined') {
        console.log('=== CARGANDO EDITOR INLINE DE RESPALDO ===');
        
        window.editImage = function(key, section) {
            console.log('=== RESPALDO: EDITANDO IMAGEN ===');
            console.log('Key:', key);
            console.log('Section:', section);
            
            try {
                document.getElementById('imageKey').value = key;
                document.getElementById('imageSection').value = section;
                
                // Mostrar/ocultar campo de orden para slider
                const orderSection = document.getElementById('sliderOrderSection');
                if (orderSection) {
                    if (section === 'slider' || key.includes('slider_thumb')) {
                        orderSection.style.display = 'block';
                        
                        // Extraer número de orden actual si existe
                        const orderMatch = key.match(/(\d+)$/);
                        if (orderMatch) {
                            const currentOrder = orderMatch[1];
                            const orderSelect = document.getElementById('sliderOrder');
                            if (orderSelect) {
                                orderSelect.value = currentOrder;
                            }
                        }
                    } else {
                        orderSection.style.display = 'none';
                    }
                }
                
                // Usar una implementación más directa del modal
                const modal = document.getElementById('imageEditModal');
                modal.style.display = 'block';
                modal.classList.add('show');
                
                console.log('Modal mostrado con método de respaldo');
            } catch (error) {
                console.error('Error en editImage de respaldo:', error);
                alert('Error al abrir el editor de imagen: ' + error.message);
            }
        };
    }
}, 3000);
</script>
@endif 