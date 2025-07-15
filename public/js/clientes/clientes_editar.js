document.addEventListener('DOMContentLoaded', function () {

    // Convertir campos a mayúsculas al guardar
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function () {
            const campos = ['nombre', 'direccion', 'ciudad', 'provincia'];

            campos.forEach(id => {
                const input = document.getElementById(id);
                if (input && input.value) {
                    input.value = input.value.toUpperCase();
                }
            });
        });
    }

    // Validar formato del DNI al escribir
    const dniInput = document.getElementById('dni');
    if (dniInput) {
        dniInput.addEventListener('input', function () {
            let value = this.value.toUpperCase();
            let numbers = value.slice(0, 8).replace(/\D/g, '');
            let letter = value.slice(8, 9).replace(/[^A-Z]/g, '');
            this.value = numbers + letter;
        });
    }

    // Validar letra del DNI al hacer clic
    const validarDNI = document.getElementById('validarDNI');
    if (validarDNI) {
        validarDNI.addEventListener('click', function () {
            const dni = dniInput.value.toUpperCase();
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
    }

    // Validar solo números en el teléfono
    const telefonoInput = document.getElementById('telefono');
    if (telefonoInput) {
        telefonoInput.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 9);
        });
    }
});
