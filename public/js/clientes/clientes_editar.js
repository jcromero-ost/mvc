document.addEventListener('DOMContentLoaded', function () {
    const modalEditarCliente = new bootstrap.Modal(document.getElementById('modalEditarCliente'));
    
    const table = document.getElementById('tabla_clientes');
    const tbody = table.querySelector('tbody');

    const mensaje = document.getElementById('mensaje');

    let clienteId;

    // Realiza las operaciones cuando se pulsa el boton
    tbody.addEventListener('click', function (event) {
        const btn = event.target.closest('.btn-editar');
        if (!btn) return;


        const cliente = JSON.parse(btn.getAttribute('data-cliente'));
        clienteId = cliente.CCODCL;
        console.log('clienteId asignado:', clienteId);  // <--- Aquí

        // Llenar campos del modal
        document.getElementById('nombre').value = cliente.CNOM || '';
        document.getElementById('telefono').value = cliente.CTEL1 || '';
        document.getElementById('dni').value = cliente.CDNI || '';
        document.getElementById('email').value = cliente.CMAIL1 || '';
        document.getElementById('direccion').value = cliente.CDOM || '';
        document.getElementById('ciudad').value = cliente.CPOB || '';
        document.getElementById('cp').value = cliente.CCODPO || '';
        document.getElementById('provincia').value = cliente.CPAIS || '';

        // **Asignar el id al input hidden**
        document.getElementById('edit_id').value = clienteId;

        modalEditarCliente.show();
    });


    // Validar letra del DNI al hacer clic
    document.getElementById('validarDNI').addEventListener('click', function () {
        const dni = document.getElementById('dni').value.toUpperCase();
        const mensajeDNI = document.getElementById('dniMensaje');

        if (!/^\d{8}[A-Z]$/.test(dni)) {
            mensajeDNI.textContent = "Formato incorrecto. Debe ser 8 números seguidos de una letra";
            mensajeDNI.className = "form-text text-danger";
            return;
        }

        const letras = "TRWAGMYFPDXBNJZSQVHLCKE";
        const numero = parseInt(dni.slice(0, 8), 10);
        const letraEsperada = letras[numero % 23];

        if (dni[8] === letraEsperada) {
            mensajeDNI.textContent = "DNI válido";
            mensajeDNI.className = "form-text text-success";
        } else {
            mensajeDNI.textContent = "DNI no válido";
            mensajeDNI.className = "form-text text-danger";
        }
    });

    document.getElementById('telefono').addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 9);
    });

    //Editar cliente
    const botonEditarCliente = document.getElementById('botonEditarCliente');
    const form_editar_cliente = document.getElementById('form_editar_cliente');

    botonEditarCliente.addEventListener('click', function () {

            if (!clienteId) {
        mensaje.textContent = 'Error: No se ha seleccionado ningún cliente para editar.';
        mensaje.classList.remove('d-none', 'bg-success');
        mensaje.classList.add('bg-danger', 'text-black', 'bg-opacity-100');
        return;
    }
        const formData = new FormData(form_editar_cliente);

        // Mostrar cada clave-valor del FormData en la consola
        for (let pair of formData.entries()) {
            console.log(`${pair[0]}: ${pair[1]}`);
        }

        fetch('/update_cliente', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                modalEditarCliente.hide();

                mensaje.textContent = 'Cliente editado correctamente.';
                mensaje.classList.add('text-black');
                mensaje.classList.add('bg-opacity-25');
                mensaje.classList.remove('bg-danger');
                mensaje.classList.add('bg-success');
            } else {
                mensaje.textContent = 'Error al editar el cliente: ' + data.error;
                mensaje.classList.add('text-black');
                mensaje.classList.add('bg-opacity-100');
                mensaje.classList.remove('bg-success');
                mensaje.classList.add('bg-danger');
            }
            mensaje.classList.remove('d-none');

            // Ocultar después de 2 segundos (2000 ms)
            setTimeout(() => {
                mensaje.classList.add('d-none');
            }, 2000);
        })
        .catch(error => {
            console.error('Error en la petición:', error);
        });
    });
});