<div class="editable-image-container" style="display: inline-block; position: relative;">
    @php
        $imageRecord = \App\Models\Image::where('key', $key)->first();
        $actualPath = $imageRecord ? $imageRecord->path : $path;
        $hasCustomImage = $imageRecord && strpos($imageRecord->path, 'uploads/') !== false;
        
        // Limpiar la ruta para display
        $displayPath = $actualPath;
        
        // Si empieza con ./ removerlo
        if (strpos($displayPath, './') === 0) {
            $displayPath = substr($displayPath, 2);
            }
        
        // Si no empieza con /, agregarlo
        if (strpos($displayPath, '/') !== 0) {
            $displayPath = '/' . $displayPath;
        }
    @endphp
    
    <img src="{{ $displayPath }}" alt="{{ $alt }}" class="{{ $class }}" id="img-{{ $key }}" @if($style ?? false) style="{{ $style }}" @endif>
    
    @if(session('admin_authenticated'))
        <i class="fas fa-edit edit-icon" 
           style="position: absolute; top: 5px; right: 5px; color: #007bff; cursor: pointer; 
                  background: rgba(255,255,255,0.9); padding: 4px; border-radius: 3px; font-size: 14px;
                  box-shadow: 0 2px 4px rgba(0,0,0,0.2); z-index: 100;"
           title="Cambiar esta imagen"
           onclick="editImage('{{ $key }}', '{{ $section }}')"></i>
        
        @if($hasCustomImage)
        <i class="fas fa-undo restore-icon" 
           style="position: absolute; top: 5px; right: 35px; color: #dc3545; cursor: pointer; 
                  background: rgba(255,255,255,0.9); padding: 4px; border-radius: 3px; font-size: 14px;
                  box-shadow: 0 2px 4px rgba(0,0,0,0.2); z-index: 100;"
           title="Restaurar imagen original"
           onclick="deleteImage('{{ $key }}', '{{ $section }}')"></i>
        @endif
    @endif
</div> 