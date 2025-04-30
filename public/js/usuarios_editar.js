document.addEventListener("DOMContentLoaded", function () {
  // Inicializar edición con datos del usuario
  document.querySelectorAll('.btn-editar').forEach(btn => {
    btn.addEventListener('click', () => {
      const user = JSON.parse(btn.dataset.usuario);

      document.getElementById('edit_id').value = user.id;
      document.getElementById('edit_nombre').value = user.nombre;
      document.getElementById('edit_alias').value = user.alias || '';
      document.getElementById('edit_email').value = user.email;
      document.getElementById('edit_telefono').value = user.telefono || '';
      document.getElementById('edit_fecha_ingreso').value = user.fecha_ingreso || '';
      document.getElementById('edit_departamento_id').value = user.departamento_id || '';
      document.getElementById('edit_activo1').checked = user.activo == 1;
      document.getElementById('edit_activo0').checked = user.activo == 0;

      // Previsualizar imagen actual (base64 o ruta)
      const preview = document.getElementById('edit_preview');
      const previewContainer = document.getElementById('edit-preview-container');
      const dropText = document.getElementById('edit-drop-text');

      if (user.foto && user.foto.startsWith('data:image/')) {
        // Foto guardada como base64
        preview.src = user.foto;
      } else if (user.foto) {
        // Foto guardada como archivo (modo anterior)
        preview.src = `/public/images/${user.foto}`;
      } else {
        preview.src = '';
      }

      if (user.foto) {
        preview.classList.remove('d-none');
        previewContainer.classList.remove('d-none');
        dropText.classList.add('d-none');
      } else {
        preview.classList.add('d-none');
        previewContainer.classList.add('d-none');
        dropText.classList.remove('d-none');
      }

      // Mostrar el modal
      const modal = new bootstrap.Modal(document.getElementById('modalEditarUsuario'));
      modal.show();
    });
  });

  // Activar Cropper.js en el modal de edición
  iniciarCropper({
    dropAreaId: "edit-drop-area",
    inputFileId: "edit_foto",
    previewImgId: "edit_preview",
    previewContainerId: "edit-preview-container",
    hiddenInputId: "edit_foto_recortada",
    dropTextId: "edit-drop-text",
    clearButtonId: "edit-btn-clear",
    cropButtonId: "edit-btn-crop"
  });

  // Validaciones al enviar el formulario
  const form = document.getElementById('formEditarUsuario');
  form.addEventListener('submit', function (e) {
    const nombre = document.getElementById('edit_nombre').value.trim();
    const email = document.getElementById('edit_email').value.trim();
    const departamento = document.getElementById('edit_departamento_id').value;
    const base64 = document.getElementById('edit_foto_recortada').value;
    const fileInput = document.getElementById('edit_foto');

    if (!nombre || !email || !departamento) {
      alert('Por favor completa los campos obligatorios.');
      e.preventDefault();
      return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      alert('Por favor ingresa un correo electrónico válido.');
      e.preventDefault();
      return;
    }

    if (fileInput.files.length > 0 && (!base64 || !base64.startsWith("data:image/"))) {
      alert('Por favor espera a que la imagen termine de cargarse y recortarse.');
      e.preventDefault();
      return;
    }
  });
});
