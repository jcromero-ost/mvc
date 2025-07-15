document.addEventListener('DOMContentLoaded', () => {
  // ================================
  // AUTOCOMPLETADO DE CLIENTE
  // ================================
  const input = document.getElementById('cliente_search');
  const suggestionsBox = document.getElementById('cliente_suggestions');
  const clienteIdInput = document.getElementById('cliente_id');

  let suggestions = [];
  let selectedIndex = -1;

  input.addEventListener('input', async () => {
    const query = input.value.trim();
    if (query.length < 2) {
      clearSuggestions();
      return;
    }

    try {
      const res = await fetch(`/xgest/buscar-clientes?q=${encodeURIComponent(query)}`);
      if (!res.ok) throw new Error('Error en la búsqueda');
      suggestions = await res.json();
    } catch (error) {
      console.error('Error al buscar clientes:', error);
      return;
    }

    renderSuggestions();
  });

  input.addEventListener('keydown', (e) => {
    const items = suggestionsBox.querySelectorAll('button');
    if (e.key === 'ArrowDown') {
      e.preventDefault();
      selectedIndex = (selectedIndex + 1) % items.length;
      updateActive(items);
    } else if (e.key === 'ArrowUp') {
      e.preventDefault();
      selectedIndex = (selectedIndex - 1 + items.length) % items.length;
      updateActive(items);
    } else if (e.key === 'Enter' && selectedIndex >= 0) {
      e.preventDefault();
      selectCliente(selectedIndex);
    }
  });

  document.addEventListener('click', (e) => {
    if (!suggestionsBox.contains(e.target) && e.target !== input) {
      clearSuggestions();
    }
  });

  function renderSuggestions() {
    suggestionsBox.innerHTML = '';
    selectedIndex = -1;

    suggestions.forEach((cliente, index) => {
      const item = document.createElement('button');
      item.type = 'button';
      item.className = 'list-group-item list-group-item-action d-flex justify-content-between align-items-center';
      item.dataset.index = index;

      const label = document.createElement('span');
      label.className = 'me-4';
      label.textContent = `${cliente.nombre} | ${cliente.telefono} | ${cliente.dni}`;

      const icon = document.createElement('i');
      icon.className = 'bi bi-check-circle-fill check-icon d-none';

      item.appendChild(label);
      item.appendChild(icon);

      item.addEventListener('click', () => selectCliente(index));
      suggestionsBox.appendChild(item);
    });
  }

  function updateActive(items) {
    items.forEach(btn => {
      btn.classList.remove('list-group-item-primary');
      const icon = btn.querySelector('.check-icon');
      if (icon) icon.classList.add('d-none');
    });

    if (items[selectedIndex]) {
      items[selectedIndex].classList.add('list-group-item-primary');
      const icon = items[selectedIndex].querySelector('.check-icon');
      if (icon) icon.classList.remove('d-none');
    }
  }

  function selectCliente(index) {
    const cliente = suggestions[index];
    input.value = cliente.nombre;
    clienteIdInput.value = cliente.id;
    clearSuggestions();
    showSelectedIcon();
  }

  function clearSuggestions() {
    suggestionsBox.innerHTML = '';
    selectedIndex = -1;
  }

  function showSelectedIcon() {
    const existingIcon = document.getElementById('cliente_check_icon');
    if (existingIcon) existingIcon.remove();

    const icon = document.createElement('i');
    icon.id = 'cliente_check_icon';
    icon.className = 'bi bi-check-circle-fill';
    icon.style.position = 'absolute';
    icon.style.right = '0.99rem';
    icon.style.top = '75%';
    icon.style.transform = 'translateY(-55%)';
    icon.style.color = 'var(--color-primary)';
    icon.style.fontSize = '1.2rem';
    icon.style.pointerEvents = 'none';

    input.parentElement.style.position = 'relative';
    input.parentElement.appendChild(icon);
  }

  // ================================
  // GESTIÓN DE ASIGNACIÓN (RADIO)
  // ================================
  const radioDept = document.getElementById("asignar_departamento");
  const radioTec = document.getElementById("asignar_tecnico");
  const radioNone = document.getElementById("sin_asignar");

  const selectDept = document.getElementById("select_departamentos");
  const selectTec = document.getElementById("select_tecnicos");

  const actualizarVistaAsignacion = () => {
    if (radioDept.checked) {
      selectDept.classList.remove("d-none");
      selectTec.classList.add("d-none");
    } else if (radioTec.checked) {
      selectDept.classList.add("d-none");
      selectTec.classList.remove("d-none");
    } else {
      selectDept.classList.add("d-none");
      selectTec.classList.add("d-none");
    }
  };

  [radioDept, radioTec, radioNone].forEach(radio => {
    radio.addEventListener("change", actualizarVistaAsignacion);
  });

  actualizarVistaAsignacion(); // Estado inicial
});
