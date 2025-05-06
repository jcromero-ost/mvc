import { getFechaDesdeURL, redirigirVista, nombresMes } from './calendario_nav.js';
import { cargarMensual } from './calendario_mensual.js';
import { cargarAnual } from './calendario_anual.js';
import { cargarSemanal } from './calendario_semanal.js';

document.addEventListener('DOMContentLoaded', () => {
  const modoVista = document.getElementById('modoVista');
  const btnHoy = document.getElementById('btnHoy');
  const btnAnterior = document.getElementById('btnMesAnterior');
  const btnSiguiente = document.getElementById('btnMesSiguiente');
  const tituloMes = document.getElementById('tituloMes');

  let { anio, mes, vista } = getFechaDesdeURL();

  // 👇 Esto corrige el selector de vista con lo que viene en la URL
  if (modoVista && vista) {
    modoVista.value = vista;
  }

  const actualizarVista = () => {
    document.getElementById('vistaMensual')?.classList.add('d-none');
    document.getElementById('vistaAnual')?.classList.add('d-none');
    document.getElementById('vistaSemanal')?.classList.add('d-none');

    const modo = modoVista.value;

    if (modo === 'mensual') {
      tituloMes.textContent = `${nombresMes[mes - 1]} de ${anio}`;
      cargarMensual(anio, mes);
    } else if (modo === 'anual') {
      tituloMes.textContent = `${anio}`;
      cargarAnual(anio);
    } else if (modo === 'semanal') {
      tituloMes.textContent = `Semana de ${nombresMes[mes - 1]} ${anio}`;
      cargarSemanal(anio, mes);
    }
  };


  // Botón Hoy
  btnHoy?.addEventListener('click', () => {
    const hoy = new Date();
    const modo = modoVista.value;
    redirigirVista(hoy.getFullYear(), hoy.getMonth() + 1, modo);
  });

  // Botón Anterior
  btnAnterior?.addEventListener('click', () => {
    let nuevoAnio = anio;
    let nuevoMes = mes;
    const modo = modoVista.value;

    if (modo === 'anual') {
      nuevoAnio--;
    } else {
      nuevoMes--;
      if (nuevoMes < 1) {
        nuevoMes = 12;
        nuevoAnio--;
      }
    }

    redirigirVista(nuevoAnio, nuevoMes, modo);
  });

  // Botón Siguiente
  btnSiguiente?.addEventListener('click', () => {
    let nuevoAnio = anio;
    let nuevoMes = mes;
    const modo = modoVista.value;

    if (modo === 'anual') {
      nuevoAnio++;
    } else {
      nuevoMes++;
      if (nuevoMes > 12) {
        nuevoMes = 1;
        nuevoAnio++;
      }
    }

    redirigirVista(nuevoAnio, nuevoMes, modo);
  });

  // Cambiar vista → actualiza la URL para mantenerla sincronizada
  modoVista?.addEventListener('change', () => {
    redirigirVista(anio, mes, modoVista.value);
  });

  // Mostrar vista inicial
  actualizarVista();
});
