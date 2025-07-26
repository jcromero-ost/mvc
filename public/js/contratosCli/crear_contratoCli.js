document.addEventListener('DOMContentLoaded', () => {
  const inputSearch = document.getElementById('cliente_search');
  const suggestionsBox = document.getElementById('cliente_suggestions');
  const clienteIdInput = document.getElementById('cliente_id');
  const clienteInfoCard = document.getElementById('cliente_info');
  const clienteInfoBody = document.getElementById('cliente_info_body');
  const formContrato = document.getElementById('formContrato');
  const formClienteId = document.getElementById('form_cliente_id');

  let selectedIndex = -1;
  let currentSuggestions = [];

  // Buscar clientes al escribir
  inputSearch.addEventListener('input', async () => {
    const query = inputSearch.value.trim();
    if (query.length < 2) {
      clearSuggestions();
      return;
    }

    try {
      const res = await fetch(`/xgest/buscar-clientes?q=${encodeURIComponent(query)}`);
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      const data = await res.json();

      currentSuggestions = data;
      selectedIndex = -1;
      renderSuggestions(data);
    } catch (err) {
      console.error('Error al buscar clientes:', err);
      suggestionsBox.innerHTML = '<div class="text-danger p-2">Error al buscar clientes</div>';
    }
  });

  // Navegación por teclado
  inputSearch.addEventListener('keydown', (e) => {
    const items = suggestionsBox.querySelectorAll('.list-group-item');
    if (items.length === 0) return;

    if (e.key === 'ArrowDown') {
      e.preventDefault();
      selectedIndex = (selectedIndex + 1) % items.length;
      updateActiveSuggestion(items);
    } else if (e.key === 'ArrowUp') {
      e.preventDefault();
      selectedIndex = (selectedIndex - 1 + items.length) % items.length;
      updateActiveSuggestion(items);
    } else if (e.key === 'Enter' && selectedIndex >= 0) {
      e.preventDefault();
      seleccionarCliente(currentSuggestions[selectedIndex]);
    } else {
      selectedIndex = -1;
    }
  });

  function renderSuggestions(data) {
    suggestionsBox.innerHTML = '';
    data.forEach((cliente, index) => {
        const item = document.createElement('button');
        item.type = 'button';
        item.classList.add('list-group-item', 'list-group-item-action', 'd-flex', 'justify-content-between', 'align-items-center');
        item.textContent = `${cliente.nombre} (${cliente.telefono || cliente.email || ''})`;
        item.dataset.index = index;

        const icon = document.createElement('i');
        icon.className = 'bi bi-check-circle-fill check-icon d-none';
        item.appendChild(icon);

        item.addEventListener('click', () => seleccionarCliente(cliente));
        suggestionsBox.appendChild(item);
    });
    }

  function updateActiveSuggestion(items) {
    items.forEach((item, index) => {
        item.classList.remove('bg-primary-subtle', 'text-primary', 'fw-semibold');
        const icon = item.querySelector('i');
        if (icon) icon.classList.add('d-none');

        if (index === selectedIndex) {
        item.classList.add('bg-primary-subtle', 'text-primary', 'fw-semibold');
        if (icon) icon.classList.remove('d-none');
        }
    });
    }

  function clearSuggestions() {
    suggestionsBox.innerHTML = '';
    selectedIndex = -1;
    currentSuggestions = [];
  }

  function seleccionarCliente(cliente) {
    clienteIdInput.value = cliente.id;
    formClienteId.value = cliente.id;
    inputSearch.value = cliente.nombre;
    clearSuggestions();

    clienteInfoBody.innerHTML = `
      <div class="col-md-6">
        <p><strong>Nombre:</strong> ${cliente.nombre}</p>
        <p><strong>Teléfono:</strong> ${cliente.telefono || 'N/D'}</p>
        <p><strong>Email:</strong> ${cliente.email || 'N/D'}</p>
      </div>
      <div class="col-md-6">
        <p><strong>Dirección:</strong> ${cliente.direccion || 'N/D'}</p>
        <p><strong>Población:</strong> ${cliente.poblacion || 'N/D'}</p>
      </div>
    `;

    clienteInfoCard.classList.remove('d-none');
    formContrato.classList.remove('d-none');
  }

  // Validación antes de enviar el formulario
  formContrato.addEventListener('submit', (e) => {
    const clienteId = clienteIdInput.value.trim();
    const fechaAlta = document.getElementById('fecha_alta').value.trim();
    const servicios = document.querySelectorAll('input[name="servicio[]"]:checked');
    const submitBtn = formContrato.querySelector('button[type="submit"]');

    if (!clienteId || !fechaAlta || servicios.length === 0) {
      e.preventDefault();
      alert('Por favor, completa todos los campos y selecciona al menos un servicio.');
      return;
    }

    submitBtn.disabled = true;
    submitBtn.innerHTML = 'Guardando...';
  });
});
