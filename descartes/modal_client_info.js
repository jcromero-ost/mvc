document.getElementById('verInfoCliente')?.addEventListener('click', function () {
  document.getElementById('clienteNombre').textContent = this.dataset.nombre;
  document.getElementById('clienteTelefono').textContent = this.dataset.telefono;
  document.getElementById('clienteDni').textContent = this.dataset.dni;
  document.getElementById('clienteEmail').textContent = this.dataset.email;
  document.getElementById('clienteDireccion').textContent = this.dataset.direccion;
  document.getElementById('clienteCiudad').textContent = this.dataset.ciudad;
  document.getElementById('clienteCP').textContent = this.dataset.cp;
  document.getElementById('clienteProvincia').textContent = this.dataset.provincia;

  const modal = new bootstrap.Modal(document.getElementById('modalInfoCliente'));
  modal.show();
});
