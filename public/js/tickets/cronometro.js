document.addEventListener('DOMContentLoaded', () => {

    const toggleBtn = document.getElementById('btnToggleCronometro');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            document.body.classList.toggle('sidebar-open');
        });
    }

    const btnIniciar = document.getElementById('btnIniciarCrono');
    const btnParar = document.getElementById('btnPararCrono');
    let quillEditor = null;
    let comentarioIdActual = null;

    let cronometroInterval = null;
    let segundosTranscurridos = 0;

    // Mostrar solo botón iniciar al cargar
    if (btnIniciar) btnIniciar.style.display = 'inline-block';
    if (btnParar) btnParar.style.display = 'none';

    if (btnIniciar && btnIniciar.dataset.ticketId) {
        btnIniciar.addEventListener('click', () => {
            const ticketId = btnIniciar.dataset.ticketId;

            fetch('/cronometro/iniciar', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `ticket_id=${encodeURIComponent(ticketId)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    comentarioIdActual = data.comentario_id;
                    abrirModalEditorComentario(comentarioIdActual);
                    iniciarVisualCronometro();
                    alternarBotonesCronometro('parar');
                } else {
                    console.error('Error al iniciar comentario:', data.message);
                }
            })
            .catch(error => console.error('Error en la petición AJAX:', error));
        });
    }

    if (btnParar) {
        btnParar.addEventListener('click', () => {
            detenerVisualCronometro();
            verificarComentarioAntesDeFinalizar();
            alternarBotonesCronometro('iniciar');
        });
    }

    function alternarBotonesCronometro(accion) {
        if (!btnIniciar || !btnParar) return;
        if (accion === 'parar') {
            btnIniciar.style.display = 'none';
            btnParar.style.display = 'inline-block';
        } else {
            btnIniciar.style.display = 'inline-block';
            btnParar.style.display = 'none';
        }
    }

    function iniciarVisualCronometro() {
        if (cronometroInterval) return;
        cronometroInterval = setInterval(() => {
            segundosTranscurridos++;
            actualizarDisplayCronometro();
        }, 1000);
    }

    function actualizarDisplayCronometro() {
        const horas = String(Math.floor(segundosTranscurridos / 3600)).padStart(2, '0');
        const minutos = String(Math.floor((segundosTranscurridos % 3600) / 60)).padStart(2, '0');
        const segundos = String(segundosTranscurridos % 60).padStart(2, '0');

        const display = document.getElementById('cronometroDisplay');
        if (display) {
            display.textContent = `${horas}:${minutos}:${segundos}`;
        }
    }

    function detenerVisualCronometro() {
        if (cronometroInterval) {
            clearInterval(cronometroInterval);
            cronometroInterval = null;
        }
    }

    function verificarComentarioAntesDeFinalizar() {
        if (!quillEditor || !comentarioIdActual) return;

        const contenidoHTML = quillEditor.root.innerHTML.trim();

        if (!contenidoHTML || contenidoHTML === '<p><br></p>') {
            mostrarMensajeInformativo('El comentario está vacío. Por favor complétalo antes de finalizar.');
            $('#modalEditarComentario').modal('show');
        } else {
            guardarContenidoComentario(true);
        }
    }

    function abrirModalEditorComentario(comentarioId) {
        $('#modalEditarComentario').modal('show');

        if (!quillEditor) {
            quillEditor = new Quill('#editorComentario', {
                theme: 'snow',
                placeholder: 'Escribe tu comentario...'
            });
        } else {
            quillEditor.setContents([]);
        }

        fetch(`/comentarios/obtener/${comentarioId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.comentario) {
                    quillEditor.root.innerHTML = data.comentario;
                }
            })
            .catch(error => console.error('Error al cargar el comentario:', error));
    }

    const btnEditarHoras = document.getElementById('btnEditarHoras');
    const contenedorHoras = document.getElementById('contenedorHoras');

    if (btnEditarHoras && contenedorHoras) {
        btnEditarHoras.addEventListener('click', () => {
            contenedorHoras.classList.toggle('d-none');
        });
    }

    function mostrarMensajeInformativo(mensaje) {
        const mensajeExistente = document.getElementById('mensajeInformativo');
        if (mensajeExistente) mensajeExistente.remove();

        const mensajeDiv = document.createElement('div');
        mensajeDiv.id = 'mensajeInformativo';
        mensajeDiv.className = 'alert alert-success mensaje-flotante';
        mensajeDiv.textContent = mensaje;

        const container = document.querySelector('.container-fluid') || document.body;
        container.insertBefore(mensajeDiv, container.firstChild);

        setTimeout(() => {
            mensajeDiv.remove();
        }, 3000);
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
                console.log('Comentario guardado correctamente');
                $('#modalEditarComentario').modal('hide');
                mostrarMensajeInformativo('Comentario guardado correctamente.');
                if (finalizando) {
                    resetearCronometroVisual();
                }
            } else {
                console.error('Error al guardar comentario:', data.message);
            }
        })
        .catch(error => {
            console.error('Error en la petición AJAX:', error);
        });
    }

    function resetearCronometroVisual() {
        detenerVisualCronometro();
        segundosTranscurridos = 0;
        actualizarDisplayCronometro();
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

    const btnGuardarComentario = document.getElementById('btnGuardarComentario');
    if (btnGuardarComentario) {
        btnGuardarComentario.addEventListener('click', () => {
            guardarContenidoComentario(true);
        });
    }

});
