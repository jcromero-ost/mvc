import { nombresMes } from './calendario_nav.js';

export function cargarMensual(anio, mes) {
  const tituloMes = document.getElementById('tituloMes');
  const vistaMensual = document.getElementById('vistaMensual');

  tituloMes.textContent = `${nombresMes[mes - 1]} de ${anio}`;
  vistaMensual?.classList.remove('d-none');
}
