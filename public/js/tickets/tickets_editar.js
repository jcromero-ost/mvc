document.addEventListener("DOMContentLoaded", function () {  
    // Realiza las operaciones cuando se pulsa el boton
    document.querySelectorAll('.btn-lupa').forEach(btn => {
        btn.addEventListener('click', () => {
        const modal = new bootstrap.Modal(document.getElementById('modalSeleccionarCliente'));

        // Mostrar el modal
        modal.show();
        });
    });
  });
  