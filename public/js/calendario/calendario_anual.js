export function cargarAnual(anio) {
  const tituloMes = document.getElementById('tituloMes');
  const vistaAnual = document.getElementById('vistaAnual');

  tituloMes.textContent = `${anio}`;
  vistaAnual?.classList.remove('d-none');
}
