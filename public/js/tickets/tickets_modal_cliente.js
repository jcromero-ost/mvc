document.addEventListener('DOMContentLoaded', function () {
  const inputBusqueda = document.getElementById('inputBuscarCliente');
  const tabla = document.getElementById('tablaClientes');
  const cuerpoTabla = document.querySelector('#tablaClientes tbody');
  const btnAceptar = document.getElementById('btnAceptarCliente');
  let resultados = [];
  let clienteSeleccionado = null;

  if (!inputBusqueda || !tabla || !cuerpoTabla || !btnAceptar) return;

  inputBusqueda.addEventListener('input', async function () {
    const termino = inputBusqueda.value.trim();
    if (termino.length < 3) {
      cuerpoTabla.innerHTML = '';
      return;
    }

    try {
      const response = await fetch(`/xgest/buscar-clientes?filtro=${encodeURIComponent(termino)}`);
      const data = await response.json();

      if (data.success) {
        resultados = data.clientes;
        renderizarTabla(resultados);
      } else {
        cuerpoTabla.innerHTML = '<tr><td colspan="5">No se encontraron resultados</td></tr>';
      }
    } catch (error) {
      console.error('Error al buscar clientes:', error);
    }
  });

  function renderizarTabla(lista) {
    cuerpoTabla.innerHTML = '';
    if (!lista.length) {
      cuerpoTabla.innerHTML = '<tr><td colspan="5">No se encontraron resultados</td></tr>';
      return;
    }

    lista.slice(0, 10).forEach(cliente => {
      const fila = document.createElement('tr');
      fila.innerHTML = `
        <td>${cliente.CNOM}</td>
        <td>${cliente.CTEL1}</td>
        <td>${cliente.CDNI}</td>
        <td>${cliente.CDOM}</td>
        <td class="text-center">
          <button type="button" class="btn btn-sm btn-primary seleccionar-cliente" data-id="${cliente.CCODCL}" data-nombre="${cliente.CNOM}">✔</button>
        </td>
      `;
      cuerpoTabla.appendChild(fila);
    });

    // Reasignar eventos
    document.querySelectorAll('.seleccionar-cliente').forEach(btn => {
      btn.addEventListener('click', () => {
        clienteSeleccionado = {
          id: btn.dataset.id,
          nombre: btn.dataset.nombre
        };

        document.getElementById('cliente').value = clienteSeleccionado.nombre;
        document.getElementById('cliente_id').value = clienteSeleccionado.id;

        const modal = bootstrap.Modal.getInstance(document.getElementById('modalSeleccionarCliente'));
        if (modal) modal.hide();
      });
    });
  }

  btnAceptar.addEventListener('click', () => {
    if (clienteSeleccionado) {
      document.getElementById('cliente').value = clienteSeleccionado.nombre;
      document.getElementById('cliente_id').value = clienteSeleccionado.id;
    }

    const modal = bootstrap.Modal.getInstance(document.getElementById('modalSeleccionarCliente'));
    if (modal) modal.hide();
  });
});
