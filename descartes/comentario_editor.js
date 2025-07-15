// === COMENTARIO EDITOR ===
window.addEventListener('DOMContentLoaded', () => {
  let quillEditor = null;
  const contenedorComentario = document.getElementById('contenedorNuevoComentario');
  const modalComentarioEl = document.getElementById('modalComentario');
  const comentarioIdInput = document.getElementById('comentarioId');
  const guardarBtn = document.getElementById('guardarComentario');

  const modalComentarioInstancia = modalComentarioEl ? new bootstrap.Modal(modalComentarioEl) : null;

  // 1. Mostrar botón de comentario al iniciar cronómetro
  document.getElementById('cronometroIniciar')?.addEventListener('click', () => {
    if (contenedorComentario && contenedorComentario.classList.contains('d-none')) {
      const btn = document.createElement('button');
      btn.className = 'btn btn-primary';
      btn.id = 'btnNuevoComentario';
      btn.innerHTML = '<i class="bi bi-chat-left-text me-1"></i> Nuevo comentario';
      btn.onclick = () => modalComentarioInstancia?.show();
      contenedorComentario.appendChild(btn);
      contenedorComentario.classList.remove('d-none');
    }
  });

  // 2. Inicializar editor Quill
  const quillContainer = document.getElementById('editorQuill');
  if (quillContainer) {
    quillEditor = new Quill(quillContainer, {
      theme: 'snow',
      placeholder: 'Escribe aquí tu comentario...',
      modules: {
        toolbar: [
          ['bold', 'italic', 'underline'],
          [{ list: 'ordered' }, { list: 'bullet' }],
          ['clean']
        ]
      }
    });
  }

  // 3. Guardar comentario
  guardarBtn?.addEventListener('click', () => {
    if (!quillEditor) return alert('El editor no está disponible.');

    const contenidoHTML = quillEditor.root.innerHTML.trim();
    const textoPlano = quillEditor.getText().trim();

    if (!textoPlano || contenidoHTML === '<p><br></p>' || contenidoHTML === '') {
      return alert('El comentario está vacío o no contiene contenido válido.');
    }

    const comentarioId = comentarioIdInput?.value;
    if (!comentarioId) return alert('No se pudo obtener el ID del comentario.');

    // Detener cronómetro si existe esa función
    if (typeof detenerCronometro === 'function') detenerCronometro();

    // Ocultar panel lateral si está abierto
    const panel = document.getElementById('cronometroPanel');
    if (panel?.classList.contains('show')) panel.classList.remove('show');

    // Cerrar modal y limpiar editor
    modalComentarioInstancia?.hide();
    quillEditor.setContents([]);

    // 4. Enviar datos al backend
    const formData = new FormData();
    formData.append('id', comentarioId);
    formData.append('contenido', contenidoHTML);
    formData.append('hora_fin', new Date().toTimeString().slice(0, 8));

    fetch('/update_ticket_comentariosSoloFecha', {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.status === 'ok') {
        window.location.href = data.redirect;
      } else {
        alert(data.message || 'Error al guardar comentario.');
      }
    })
    .catch(err => {
      console.error('Error al guardar comentario:', err);
      alert('Hubo un error al guardar el comentario.');
    });

    contenedorComentario.classList.add('d-none');
  });
});
