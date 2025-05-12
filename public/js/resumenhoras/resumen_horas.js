document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('formResumenHoras');
    const usuarioIdInput = document.getElementById('usuario');
    const anioSelect = document.getElementById('anio');
    const contenedorTabla = document.getElementById('tablaResumenHoras');

    if (!form || !usuarioIdInput || !anioSelect || !contenedorTabla) {
        console.error('Error: formulario o elementos requeridos no encontrados.');
        return;
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const usuarioId = usuarioIdInput.value;
        const anio = anioSelect.value;

        if (!usuarioId) {
            contenedorTabla.innerHTML = `<div class="alert alert-warning">Debe seleccionar un usuario válido.</div>`;
            return;
        }

        fetch(`/resumen-horas/usuario?usuario_id=${usuarioId}&anio=${anio}`)
            .then(res => {
                if (!res.ok) throw new Error('No se pudo cargar el resumen.');
                return res.text();
            })
            .then(html => {
                contenedorTabla.innerHTML = html;
            })
            .catch(error => {
                contenedorTabla.innerHTML = `<div class="alert alert-danger">${error.message}</div>`;
            });
    });
});
