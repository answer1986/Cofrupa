@extends('admin.layout')

@section('title', 'Crear Contenido - Onizzo Admin')

@section('content')
    <div class="header-card">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1><i class="fas fa-plus-circle me-3"></i>Crear Nuevo Contenido</h1>
                <p class="mb-0">Agrega un nuevo texto editable para tu sitio web</p>
            </div>
            <a href="{{ route('admin.contents.index') }}" class="btn btn-outline-light">
                <i class="fas fa-arrow-left me-2"></i>Volver
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Formulario de Contenido</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.contents.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="key" class="form-label">
                            <i class="fas fa-key me-1"></i>Clave Única *
                        </label>
                        <input type="text" 
                               class="form-control @error('key') is-invalid @enderror" 
                               id="key" 
                               name="key" 
                               value="{{ old('key') }}"
                               placeholder="ej: about_us_title"
                               required>
                        @error('key')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Identificador único para este contenido. Solo letras, números y guiones bajos.
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="section" class="form-label">
                            <i class="fas fa-layer-group me-1"></i>Sección *
                        </label>
                        <select class="form-select @error('section') is-invalid @enderror" 
                                id="section" 
                                name="section" 
                                required>
                            <option value="">Seleccionar sección</option>
                            <option value="general" {{ old('section') === 'general' ? 'selected' : '' }}>General</option>
                            <option value="productos" {{ old('section') === 'productos' ? 'selected' : '' }}>Productos</option>
                            <option value="nosotros" {{ old('section') === 'nosotros' ? 'selected' : '' }}>Nosotros</option>
                            <option value="slider" {{ old('section') === 'slider' ? 'selected' : '' }}>Slider</option>
                            <option value="mercados" {{ old('section') === 'mercados' ? 'selected' : '' }}>Mercados</option>
                            <option value="contacto" {{ old('section') === 'contacto' ? 'selected' : '' }}>Contacto</option>
                            <option value="footer" {{ old('section') === 'footer' ? 'selected' : '' }}>Footer</option>
                        </select>
                        @error('section')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="type" class="form-label">
                            <i class="fas fa-text-height me-1"></i>Tipo de Contenido *
                        </label>
                        <select class="form-select @error('type') is-invalid @enderror" 
                                id="type" 
                                name="type" 
                                required>
                            <option value="">Seleccionar tipo</option>
                            <option value="text" {{ old('type') === 'text' ? 'selected' : '' }}>Texto Corto</option>
                            <option value="textarea" {{ old('type') === 'textarea' ? 'selected' : '' }}>Texto Largo</option>
                            <option value="html" {{ old('type') === 'html' ? 'selected' : '' }}>HTML</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">
                        <i class="fas fa-info-circle me-1"></i>Descripción
                    </label>
                    <input type="text" 
                           class="form-control @error('description') is-invalid @enderror" 
                           id="description" 
                           name="description" 
                           value="{{ old('description') }}"
                           placeholder="Descripción breve de este contenido">
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="value_es" class="form-label">
                            <i class="fas fa-flag me-1"></i>Contenido en Español
                        </label>
                        <div id="spanish-input">
                            <input type="text" 
                                   class="form-control @error('value_es') is-invalid @enderror" 
                                   id="value_es" 
                                   name="value_es" 
                                   value="{{ old('value_es') }}"
                                   placeholder="Texto en español">
                        </div>
                        @error('value_es')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="value_en" class="form-label">
                            <i class="fas fa-flag me-1"></i>Contenido en Inglés
                        </label>
                        <div id="english-input">
                            <input type="text" 
                                   class="form-control @error('value_en') is-invalid @enderror" 
                                   id="value_en" 
                                   name="value_en" 
                                   value="{{ old('value_en') }}"
                                   placeholder="Text in English">
                        </div>
                        @error('value_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.contents.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Guardar Contenido
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Consejos</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <h6><i class="fas fa-key me-2 text-primary"></i>Claves</h6>
                    <ul class="text-muted small">
                        <li>Usa nombres descriptivos</li>
                        <li>Formato: seccion_elemento</li>
                        <li>Solo letras, números y _</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6><i class="fas fa-text-height me-2 text-success"></i>Tipos</h6>
                    <ul class="text-muted small">
                        <li><strong>Texto:</strong> Títulos, enlaces</li>
                        <li><strong>Textarea:</strong> Párrafos largos</li>
                        <li><strong>HTML:</strong> Contenido con formato</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6><i class="fas fa-language me-2 text-info"></i>Idiomas</h6>
                    <ul class="text-muted small">
                        <li>Español es el idioma principal</li>
                        <li>Inglés es opcional</li>
                        <li>Si falta inglés, usa español</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const spanishInput = document.getElementById('spanish-input');
    const englishInput = document.getElementById('english-input');

    function updateInputType() {
        const type = typeSelect.value;
        const spanishValue = document.getElementById('value_es').value;
        const englishValue = document.getElementById('value_en').value;

        if (type === 'textarea' || type === 'html') {
            // Cambiar a textarea
            spanishInput.innerHTML = `<textarea class="form-control" id="value_es" name="value_es" rows="4" placeholder="Texto en español">${spanishValue}</textarea>`;
            englishInput.innerHTML = `<textarea class="form-control" id="value_en" name="value_en" rows="4" placeholder="Text in English">${englishValue}</textarea>`;
        } else {
            // Cambiar a input
            spanishInput.innerHTML = `<input type="text" class="form-control" id="value_es" name="value_es" value="${spanishValue}" placeholder="Texto en español">`;
            englishInput.innerHTML = `<input type="text" class="form-control" id="value_en" name="value_en" value="${englishValue}" placeholder="Text in English">`;
        }
    }

    typeSelect.addEventListener('change', updateInputType);
});
</script>
@endpush 