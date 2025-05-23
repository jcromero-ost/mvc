document.addEventListener('DOMContentLoaded', function () {
    const modalEditarCliente = new bootstrap.Modal(document.getElementById('modalEditarCliente'));
    
    const table = document.getElementById('tabla_clientes');
    const tbody = table.querySelector('tbody');

    // Realiza las operaciones cuando se pulsa el boton
    tbody.addEventListener('click', function (event) {
        const btn = event.target.closest('.btn-editar');
        if (!btn) return;

        const clienteId = btn.getAttribute('data-id');

        // Obtener los datos del cliente desde el atributo data
        const cliente = JSON.parse(btn.getAttribute('data-cliente'));

        // Llenar los campos del modal
        document.getElementById('nombre').value = cliente.CNOM || '';
        document.getElementById('telefono').value = cliente.CTEL1 || '';
        document.getElementById('dni').value = cliente.CDNI || '';
        document.getElementById('email').value = cliente.CMAIL1 || '';
        document.getElementById('direccion').value = cliente.CDOM || '';
        document.getElementById('ciudad').value = cliente.CPOB || '';
        document.getElementById('cp').value = cliente.CCODPO || '';
        document.getElementById('provincia').value = cliente.CPAIS || '';

        modalEditarCliente.show();
    });

        document.getElementById('dni').addEventListener('input', function () {
        let value = this.value.toUpperCase(); // Convertir a mayúsculas
        let numbers = value.slice(0, 8).replace(/\D/g, ''); // Solo dígitos en los primeros 8
        let letter = value.slice(8, 9).replace(/[^A-Z]/g, ''); // Solo letra en la posición 9

        this.value = numbers + letter;
    });


    // Validar letra del DNI al hacer clic
    document.getElementById('validarDNI').addEventListener('click', function () {
        const dni = document.getElementById('dni').value.toUpperCase();
        const mensaje = document.getElementById('dniMensaje');

        if (!/^\d{8}[A-Z]$/.test(dni)) {
            mensaje.textContent = "Formato incorrecto. Debe ser 8 números seguidos de una letra";
            mensaje.className = "form-text text-danger";
            return;
        }

        const letras = "TRWAGMYFPDXBNJZSQVHLCKE";
        const numero = parseInt(dni.slice(0, 8), 10);
        const letraEsperada = letras[numero % 23];

        if (dni[8] === letraEsperada) {
            mensaje.textContent = "DNI válido";
            mensaje.className = "form-text text-success";
        } else {
            mensaje.textContent = "DNI no válido";
            mensaje.className = "form-text text-danger";
        }
    });

    document.getElementById('telefono').addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 9);
    });

    //Editar cliente
    const botonEditarCliente = document.getElementById('botonEditarCliente');
    const form_editar_cliente = document.getElementById('form_editar_cliente');

    botonEditarCliente.addEventListener('click', function () {
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
                alert('Cliente actualizado correctamente');
                modalEditarCliente.hide();
            } else {
                alert('Error al actualizar: ' + (data.error || 'Desconocido'));
            }
        })
        .catch(error => {
            console.error('Error en la petición AJAX:', error);
            alert('Error en la conexión con el servidor.');
        });
    });
});