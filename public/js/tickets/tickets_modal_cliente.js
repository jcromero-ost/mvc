const inputSeleccionCliente = document.getElementById('seleccionCliente');

// Realiza las operaciones cuando se pulsa el boton
document.querySelectorAll('.btn-seleccionar').forEach(btn => {
    btn.addEventListener('click', () => {
        const cliente = JSON.parse(btn.dataset.cliente);

        inputSeleccionCliente.value = cliente.CNOM;
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const modalCrearCliente = new bootstrap.Modal(document.getElementById('modalCrearCliente'));

    // Realiza las operaciones cuando se pulsa el boton
    document.querySelectorAll('.btn-crear-cliente').forEach(btn => {
        btn.addEventListener('click', () => {
        
        // Mostrar el modal
        modalCrearCliente.show();
        });
    });

    // Realiza las operaciones cuando se pulsa el boton
    document.querySelectorAll('.btn-success').forEach(btn => {
        btn.addEventListener('click', () => {
            const inputClienteForm = document.getElementById('cliente');

            inputClienteForm.value = inputSeleccionCliente.value;
        });
    });
});