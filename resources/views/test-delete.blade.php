<!DOCTYPE html>
<html>
<head>
    <title>Test Delete Button</title>
    <meta name="csrf-token" content="test-token">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <h1>Test de Botón Eliminar</h1>
    
    <!-- Simulando el componente editable-image -->
    <div class="editable-image-container" style="display: inline-block; position: relative;">
        <img src="http://127.0.0.1:8001/image/uploads/1750889328_carousel_image_3.jpeg" alt="Test" style="width: 200px;">
        
        <!-- Botón de editar -->
        <i class="fas fa-edit edit-icon" 
           style="position: absolute; top: 5px; right: 5px; color: #007bff; cursor: pointer; 
                  background: rgba(255,255,255,0.9); padding: 4px; border-radius: 3px; font-size: 14px;
                  box-shadow: 0 2px 4px rgba(0,0,0,0.2);"
           title="Cambiar esta imagen"
           onclick="alert('Botón editar funciona')"></i>
        
        <!-- Botón de eliminar -->
        <i class="fas fa-undo restore-icon" 
           style="position: absolute; top: 5px; right: 35px; color: #dc3545; cursor: pointer; 
                  background: rgba(255,255,255,0.9); padding: 4px; border-radius: 3px; font-size: 14px;
                  box-shadow: 0 2px 4px rgba(0,0,0,0.2);"
           title="Restaurar imagen original"
           onclick="testDeleteImage()"></i>
    </div>

    <script>
        function testDeleteImage() {
            console.log('testDeleteImage called');
            alert('Botón eliminar clickeado');
            
            if (confirm('¿Estás seguro de que quieres restaurar la imagen original?')) {
                console.log('User confirmed');
                
                const formData = new FormData();
                formData.append('_token', 'test-token');
                formData.append('key', 'carousel_image_3');
                formData.append('section', 'nosotros');

                console.log('Sending request to:', 'http://127.0.0.1:8001/api/admin/image/delete');

                fetch('http://127.0.0.1:8001/api/admin/image/delete', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    console.log('Response:', response);
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    alert('Respuesta: ' + JSON.stringify(data));
                })
                .catch(error => {
                    console.error('Error completo:', error);
                    alert('Error: ' + error.message);
                });
            }
        }
    </script>
</body>
</html> 