
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.btn-editar').forEach(button => {
    button.addEventListener('click', () => {
      document.getElementById('editar-id').value = button.getAttribute('data-id');
      document.getElementById('editar-nombre').value = button.getAttribute('data-nombre');
    });
  });

  document.querySelectorAll('.btn-confirmar-eliminar').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.getAttribute('data-id');
      const nombre = btn.getAttribute('data-nombre');
      document.getElementById('idDepartamentoEliminar').value = id;
      document.getElementById('nombreDepartamentoEliminar').textContent = nombre;
    });
  });
});
