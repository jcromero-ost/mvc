document.addEventListener("DOMContentLoaded", function () {
  const botonInfoCliente = document.getElementById('botonInfoCliente');
  if (!botonInfoCliente) {
    console.error('No existe el botón botonInfoCliente');
    return;
  }

  const campos = ['nombre', 'telefono', 'dni', 'email', 'direccion', 'ciudad', 'cp', 'provincia'];

  campos.forEach(id => {
    const input = document.getElementById(id);
    if(input) {
      input.value = botonInfoCliente.dataset[id] || '';
    }
  });
});
