document.addEventListener("DOMContentLoaded", function () {
    const inputSeleccionCliente = document.getElementById('seleccionCliente');
    const modalSeleccionarCliente = new bootstrap.Modal(document.getElementById('modalSeleccionarCliente'));

    const modalCrearCliente = new bootstrap.Modal(document.getElementById('modalCrearCliente'));

    const inputNombreCliente = document.getElementById('nombre');
    const inputClienteForm = document.getElementById('cliente');

    const modal = new bootstrap.Modal(document.getElementById('modalSeleccionarCliente'));

    // Realiza las operaciones cuando se pulsa el boton
    document.querySelectorAll('.btn-success').forEach(btn => {
        btn.addEventListener('click', () => {

            console.log(inputNombreCliente.value);


            inputClienteForm.value = inputNombreCliente.value;
        });
    });

    document.getElementById('formCrearCliente').addEventListener('submit', function (e) {
        e.preventDefault();

        const campos = ['nombre', 'direccion', 'ciudad', 'provincia'];

        campos.forEach(id => {
            const input = document.getElementById(id);
            if (input && input.value) {
                input.value = input.value.toUpperCase();
            }
        });

        const form = e.target;
        const formData = new FormData(form);

        fetch('/store_cliente_ticket', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                modalCrearCliente.hide();

                inputSeleccionCliente.value = formData.get('nombre');
            } else {

            }
        })
        .catch(error => {
            console.error('Error en la petición:', error);
            document.getElementById('respuesta').textContent = 'Error inesperado en la petición.';
        });
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
});