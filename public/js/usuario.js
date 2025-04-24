document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.btn-editar').forEach(btn => {
      btn.addEventListener('click', () => {
        const user = JSON.parse(btn.dataset.usuario);
        document.getElementById('edit_id').value = user.id;
        document.getElementById('edit_nombre').value = user.nombre;
        document.getElementById('edit_alias').value = user.alias || '';
        document.getElementById('edit_email').value = user.email;
        document.getElementById('edit_telefono').value = user.telefono || '';
        document.getElementById('edit_fecha_ingreso').value = user.fecha_ingreso || '';
        document.getElementById('edit_activo').value = user.activo;
  
        const modal = new bootstrap.Modal(document.getElementById('modalEditarUsuario'));
        modal.show();
      });
    });
  });
  