<span class="editable-content" data-key="{{ $key }}" data-section="{{ $section }}" data-type="{{ $type }}">
    {{ $value }}
    <i class="fas fa-edit edit-icon" 
       style="margin-left: 5px; color: #007bff; cursor: pointer; font-size: 12px; opacity: 0.7;"
       title="Editar este texto"
       onclick="editContent('{{ $key }}', '{{ $section }}', '{{ $type }}', this)"></i>
</span> 