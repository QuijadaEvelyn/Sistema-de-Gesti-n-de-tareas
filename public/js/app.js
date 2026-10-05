document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Ocultar alertas y mensajes de éxito automáticamente después de 5 segundos
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.remove();
            }, 500); // Espera a que termine la transición para removerlo del HTML
        }, 5000); // 5000 milisegundos = 5 segundos
    });

    // 2. Confirmación al eliminar o calificar (evita clics por accidente)
    // Se usa agregando la clase "form-delete" a los formularios que requieran confirmación
    const confirmForms = document.querySelectorAll('.form-delete');
    confirmForms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            if (!confirm('¿Estás seguro de que deseas realizar esta acción?')) {
                e.preventDefault(); // Detiene el envío del formulario si el usuario cancela
            }
        });
    });

    // 3. Validación rápida para asegurarse de que el alumno solo suba PDFs
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(function(input) {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            // Verifica que haya un archivo y que sea tipo PDF
            if (file && file.type !== 'application/pdf') {
                alert('Error: Por favor, selecciona únicamente un archivo en formato PDF.');
                e.target.value = ''; // Limpia el input para que no se suba el archivo incorrecto
            }
        });
    });

});