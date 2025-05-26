document.addEventListener("DOMContentLoaded", function () {
  // Inicializar edición con datos del usuario
  document.querySelectorAll('.btn-password').forEach(btn => {
    btn.addEventListener('click', () => {
      const user = JSON.parse(btn.dataset.usuario);

      document.getElementById('cambiar_id').value = user.id;

      // Mostrar el modal
      const modal = new bootstrap.Modal(document.getElementById('modalCambiarPassword'));
      modal.show();
    });
  });

  // Validaciones al enviar el formulario
  const form = document.getElementById('formCambiarPassword');
  form.addEventListener('submit', function (e) {
    const password = document.getElementById('password').value.trim();

    if (!password) {
      alert('Por favor completa los campos obligatorios.');
      e.preventDefault();
      return;
    }
  });
});
