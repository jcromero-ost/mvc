document.addEventListener('DOMContentLoaded', () => {
  const filas = document.querySelectorAll('.fila-contrato');

  filas.forEach(fila => {
    const toggleBtn = fila.querySelector('.toggle-servicios');

    if (toggleBtn) {
      toggleBtn.addEventListener('click', () => {
        const contratoId = fila.dataset.id;
        const detalle = document.getElementById(`detalle-servicios-${contratoId}`);

        if (detalle.classList.contains('d-none')) {
          detalle.classList.remove('d-none');
          toggleBtn.classList.add('rotate-down');
        } else {
          detalle.classList.add('d-none');
          toggleBtn.classList.remove('rotate-down');
        }
      });
    }
  });
});

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.toggle-servicios').forEach(button => {
      button.addEventListener('click', () => {
        const targetId = button.getAttribute('data-target');
        const row = document.querySelector(targetId);
        if (row.style.display === 'none') {
          row.style.display = 'table-row';
          button.textContent = 'Ocultar servicios';
        } else {
          row.style.display = 'none';
          button.textContent = 'Ver servicios';
        }
      });
    });
  });
