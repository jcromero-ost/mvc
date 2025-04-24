document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = btn.dataset.id;
        const nombre = btn.dataset.nombre;
  
        document.getElementById('delete_id').value = id;
        document.getElementById('delete_nombre').textContent = nombre;
  
        const modal = new bootstrap.Modal(document.getElementById('modalEliminarUsuario'));
        modal.show();
      });
    });
  });
  