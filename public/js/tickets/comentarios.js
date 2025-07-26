document.addEventListener('DOMContentLoaded', () => {
    const ticketId = document.getElementById('ticket_id').value;
    const usuarioActualId = document.getElementById('usuario_actual_id').value; // Asegúrate de tener este input hidden

    cargarComentarios(ticketId, usuarioActualId);
});

function cargarComentarios(ticketId, usuarioActualId) {
    fetch(`/comentarios/obtener?ticket_id=${ticketId}`)
        .then(response => response.json())
        .then(comentarios => renderizarComentarios(comentarios, usuarioActualId))
        .catch(error => console.error('Error al cargar comentarios', error));
}

function calcularDiferenciaHoras(horaInicio, horaFin) {
    if (!horaInicio || !horaFin) return '--:--';

    const [hInicio, mInicio] = horaInicio.split(':').map(Number);
    const [hFin, mFin] = horaFin.split(':').map(Number);

    let minutosInicio = hInicio * 60 + mInicio;
    let minutosFin = hFin * 60 + mFin;

    if (minutosFin < minutosInicio) {
        // Si hora_fin es menor, asumimos que pasa de medianoche
        minutosFin += 24 * 60;
    }

    const diferenciaMin = minutosFin - minutosInicio;
    const horas = Math.floor(diferenciaMin / 60);
    const minutos = diferenciaMin % 60;

    return `${String(horas).padStart(2, '0')}:${String(minutos).padStart(2, '0')}`;
}


function renderizarComentarios(comentarios, usuarioActualId) {
    const lista = document.getElementById('listaComentarios');
    

    lista.innerHTML = '';

    function formatearFecha(fechaStr) {
        if (!fechaStr) return '';
        const fecha = new Date(fechaStr);
        const dia = String(fecha.getDate()).padStart(2, '0');
        const mes = String(fecha.getMonth() + 1).padStart(2, '0');
        const anio = fecha.getFullYear();
        return `${dia}-${mes}-${anio}`;
    }

    comentarios.forEach(comentario => {
        const div = document.createElement('div');

        // Determinar si es interno
        const esInterno = comentario.tipo === 'interno';

        div.classList.add('list-group-item', 'comentario-postit', 'mb-2', 'position-relative');
        
        // Alineación a la derecha si es interno
        if (esInterno) {
            div.classList.add('comentario-interno');  // clase nueva para internos
        }

        const fechaFormateada = formatearFecha(comentario.fecha);
        const duracion = calcularDiferenciaHoras(comentario.hora_inicio, comentario.hora_fin);

        const botonEditar = (comentario.usuario_id == usuarioActualId)
            ? `<button class="btn-editar-comentario-mini position-absolute"
                        data-id="${comentario.id}"
                        title="Editar comentario">
                    <i class="bi bi-pencil-square"></i>
                </button>`
            : '';

        div.innerHTML = `
            <div class="top-derecha">
                <small class="text-muted">${fechaFormateada}</small>
                <span class="badge bg-info">${duracion}</span>
            </div>
            <br>
            <div class="contenido-comentario truncate-2">${comentario.contenido}</div>
            <a href="#" class="ver-mas">Ver más</a>

            <strong><small>Por: ${comentario.nombre_usuario ?? 'Desconocido'}</small></strong>

            ${botonEditar}
        `;

        lista.appendChild(div);
    });

}

// Alternar ver más / ver menos
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('ver-mas')) {
        e.preventDefault();
        const comentarioPostit = e.target.closest('.comentario-postit');
        const contenido = comentarioPostit.querySelector('.contenido-comentario');

        contenido.classList.toggle('expandido');
        e.target.textContent = contenido.classList.contains('expandido') ? 'Ver menos' : 'Ver más';
    }
});

// Botón editar comentario
document.addEventListener('click', function(e) {
    if (e.target.closest('.btn-editar-comentario-mini')) {
        e.preventDefault();
        const boton = e.target.closest('.btn-editar-comentario-mini');
        const comentarioId = boton.getAttribute('data-id');
        abrirModalEditarComentario(comentarioId);
    }
});

window.cargarComentarios = cargarComentarios;

document.addEventListener('DOMContentLoaded', () => {
    const btnNuevoComentario = document.getElementById('btnNuevoComentario');
    if (btnNuevoComentario) {
        btnNuevoComentario.addEventListener('click', () => {
            const ticketId = document.getElementById('ticket_id').value;
            const usuarioId = document.getElementById('usuario_actual_id').value;

            fetch('/comentarios/crear-interno', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    ticket_id: ticketId,
                    usuario_id: usuarioId
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    mostrarMensajeInformativo('Comentario interno creado.');
                    cargarComentarios(ticketId, usuarioId); // recarga lista
                    abrirModalEditarComentario(data.id);    // lo edita directamente
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(err => console.error('Error creando comentario interno:', err));
        });
    }
});

