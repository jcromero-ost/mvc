document.addEventListener('DOMContentLoaded', function () {

    const campos = ['nombre', 'direccion', 'ciudad', 'provincia'];
    const form = document.querySelector('form');
    const dniInput = document.getElementById('dni');
    const dniMensaje = document.getElementById('dniMensaje');
    const validarDNIButton = document.getElementById('validarDNI');
    const telefonoInput = document.getElementById('telefono');

    if (form) {
        form.addEventListener('submit', function () {
            campos.forEach(id => {
                const input = document.getElementById(id);
                if (input && input.value) {
                    input.value = input.value.toUpperCase();
                }
            });
        });
    }

    if (dniInput) {
        dniInput.addEventListener('input', function () {
            let value = this.value.toUpperCase();
            let numbers = value.slice(0, 8).replace(/\D/g, '');
            let letter = value.slice(8, 9).replace(/[^A-Z]/g, '');
            this.value = numbers + letter;
        });
    }

    if (validarDNIButton) {
        validarDNIButton.addEventListener('click', function () {
            const dni = dniInput.value.toUpperCase();

            if (!/^\d{8}[A-Z]$/.test(dni)) {
                dniMensaje.textContent = "Formato incorrecto. Debe ser 8 números seguidos de una letra";
                dniMensaje.className = "form-text text-danger";
                return;
            }

            const letras = "TRWAGMYFPDXBNJZSQVHLCKE";
            const numero = parseInt(dni.slice(0, 8), 10);
            const letraEsperada = letras[numero % 23];

            if (dni[8] === letraEsperada) {
                dniMensaje.textContent = "DNI válido";
                dniMensaje.className = "form-text text-success";
            } else {
                dniMensaje.textContent = "DNI no válido";
                dniMensaje.className = "form-text text-danger";
            }
        });
    }

    if (telefonoInput) {
        telefonoInput.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 9);
        });
    }
});
