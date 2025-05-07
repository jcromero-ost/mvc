document.addEventListener("DOMContentLoaded", function () {
    const inputSeleccionCliente = document.getElementById('seleccionCliente');
    const modalSeleccionarCliente = new bootstrap.Modal(document.getElementById('modalSeleccionarCliente'));

    // Realiza las operaciones cuando se pulsa el boton
    document.querySelectorAll('.btn-success').forEach(btn => {
        btn.addEventListener('click', () => {
            const inputNombreCliente = document.getElementById('nombre');

            console.log(inputNombreCliente.value);

            const inputClienteForm = document.getElementById('cliente');

            inputClienteForm.value = inputNombreCliente.value;
        });
    });

    


});