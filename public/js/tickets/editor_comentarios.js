// editor_comentarios.js

let quillEditor = null;
let comentarioIdActual = null;

function inicializarEditor() {
    if (!quillEditor) {
        quillEditor = new Quill('#editorComentario', {
            theme: 'snow',
            placeholder: 'Escribe tu comentario...'
        });
    } else {
        quillEditor.setContents([]);
    }
}

function abrirModalEditarComentario(comentarioId) {
    comentarioIdActual = comentarioId;
    inicializarEditor();

    fetch(`/comentarios/obtener-uno?id=${comentarioId}`)
        .then(response => response.json())
        .then(comentario => {
            if (comentario) {
                quillEditor.root.innerHTML = comentario.contenido;
                document.getElementById('horaInicioManual').value = comentario.hora_inicio;
                document.getElementById('horaFinManual').value = comentario.hora_fin;
                document.getElementById('fechaManual').value = comentario.fecha;
            }
            $('#modalEditarComentario').modal('show');
        })
        .catch(error => console.error('Error al obtener el comentario:', error));
}

function guardarContenidoComentario(finalizando = false) {
    if (!quillEditor || !comentarioIdActual) {
        console.error('Editor no inicializado o comentario ID no definido');
        return;
    }

    const contenidoHTML = quillEditor.root.innerHTML;
    const horaInicioManual = document.getElementById('horaInicioManual')?.value || '';
    const horaFinManual = document.getElementById('horaFinManual')?.value || '';
    const fechaManual = document.getElementById('fechaManual')?.value || '';

    const bodyParams = new URLSearchParams({
        comentario_id: comentarioIdActual,
        contenido: contenidoHTML,
        finalizar: finalizando ? '1' : '0',
        hora_inicio: horaInicioManual,
        hora_fin: horaFinManual,
        fecha: fechaManual
    });

    fetch('/comentario/guardar', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: bodyParams.toString()
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarMensajeInformativo('Comentario guardado correctamente.');

            // Cerrar el modal
            const modalElement = document.getElementById('modalEditarComentario');
            const modalInstance = bootstrap.Modal.getInstance(modalElement);
            if (modalInstance) {
                modalInstance.hide();
            }

            // Esperar brevemente y luego recargar
            setTimeout(() => {
                const ticketId = document.getElementById('ticket_id').value;
                const usuarioActualId = document.getElementById('usuario_actual_id').value;

                if (typeof cargarComentarios === 'function') {
                    cargarComentarios(ticketId, usuarioActualId);
                } else {
                    console.error("La función cargarComentarios no está disponible.");
                }
            }, 300); // da tiempo a que el modal se oculte

        } else {
            console.error('Error al guardar comentario:', data.message);
        }
    })
    .catch(error => console.error('Error en la petición AJAX:', error));
}


function mostrarMensajeInformativo(mensaje) {
    const mensajeExistente = document.getElementById('mensajeInformativo');
    if (mensajeExistente) mensajeExistente.remove();

    const mensajeDiv = document.createElement('div');
    mensajeDiv.id = 'mensajeInformativo';
    mensajeDiv.className = 'alert alert-success mensaje-flotante';
    mensajeDiv.textContent = mensaje;

    const container = document.querySelector('.content-main') || document.body;
    container.insertBefore(mensajeDiv, container.firstChild);

    setTimeout(() => {
        mensajeDiv.remove();
    }, 3000);
}

// Evento botón guardar
const btnGuardarComentario = document.getElementById('btnGuardarComentario');
if (btnGuardarComentario) {
    btnGuardarComentario.addEventListener('click', () => {
        guardarContenidoComentario(true);
    });
}
