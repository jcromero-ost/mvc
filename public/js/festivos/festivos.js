document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form-agregar-festivo');
    const filtroAnio = document.getElementById('filtro-anio');

    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(form);

            fetch('/festivos/store', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    mostrarAlerta('Festivo guardado correctamente.', 'success');
                    cargarTablaFestivos();
                    form.reset();
                } else {
                    mostrarAlerta(data.message || 'Error al guardar el festivo.', 'danger');
                }
            })
            .catch(err => {
                console.error('Error al guardar festivo:', err);
                mostrarAlerta('Error inesperado al guardar.', 'danger');
            });
        });
    }

    if (filtroAnio) {
        filtroAnio.addEventListener('change', () => {
            cargarTablaFestivos();
        });
    }

    document.body.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-eliminar')) {
            const id = e.target.dataset.id;

            if (confirm('¿Estás seguro de que deseas eliminar este festivo?')) {
                fetch('/festivos/delete', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${id}`
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        mostrarAlerta('Festivo eliminado correctamente.', 'success');
                        cargarTablaFestivos();
                    } else {
                        mostrarAlerta('No se pudo eliminar el festivo.', 'danger');
                    }
                })
                .catch(err => {
                    console.error('Error al eliminar festivo:', err);
                    mostrarAlerta('Error inesperado al eliminar.', 'danger');
                });
            }
        }
    });

    function cargarTablaFestivos() {
        const year = filtroAnio ? filtroAnio.value : new Date().getFullYear();

        fetch(`/festivos?anio=${year}`)
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const nuevaTabla = doc.querySelector('#tabla-festivos');
                const contenedor = document.querySelector('#tabla-festivos');

                if (nuevaTabla && contenedor) {
                    contenedor.innerHTML = nuevaTabla.innerHTML;
                }
            })
            .catch(err => {
                console.error('Error al recargar la tabla:', err);
                mostrarAlerta('No se pudo cargar la tabla.', 'danger');
            });
    }

    function mostrarAlerta(mensaje, tipo = 'success') {
        const alerta = document.getElementById('alerta-festivo');
        if (alerta) {
            alerta.className = `alert alert-${tipo}`;
            alerta.textContent = mensaje;
            alerta.classList.remove('d-none');
            setTimeout(() => alerta.classList.add('d-none'), 4000);
        }
    }
});
