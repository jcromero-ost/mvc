document.addEventListener("DOMContentLoaded", function () {
    const modalDescripcionCompleta = new bootstrap.Modal(document.getElementById('modalDescripcionCompleta'));

    const descripcionCompleta_texto = document.getElementById('descripcionCompleta_texto');

    // Recorre cada botón y agrega el event listener
    document.querySelectorAll('.btn-descripcion-completa').forEach(btn => {
        btn.addEventListener('click', function () {
            const descripcion = this.dataset.descripcion;
            
            descripcionCompleta_texto.innerHTML = descripcion; // Mostrar bien saltos de línea

            // Mostrar el modal
            modalDescripcionCompleta.show();
        });
    });
});
