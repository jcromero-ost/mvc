import { nombresMes, redirigirVista, getNumeroSemana, getFechaDesdeURL, getMaxSemanasAnio } from './calendario_nav.js';

export function cargarSemanal(anio, mes) {
  const tituloMes = document.getElementById('tituloMes');
  const vistaSemanal = document.getElementById('vistaSemanal');
  const btnHoy = document.getElementById('btnHoy');
  const btnAnterior = document.getElementById('btnMesAnterior');
  const btnSiguiente = document.getElementById('btnMesSiguiente');

  const { semana: semanaUrl } = getFechaDesdeURL();

  const primeraSemana = new Date(anio, mes - 1, 1);
  let semana = semanaUrl || getNumeroSemana(primeraSemana);

  tituloMes.textContent = `Semana ${semana} - ${anio}`;
  vistaSemanal?.classList.remove('d-none');

  btnHoy?.addEventListener('click', () => {
    const hoy = new Date();
    const actualAnio = hoy.getFullYear();
    const actualMes = hoy.getMonth() + 1;
    const actualSemana = getNumeroSemana(hoy);
    redirigirVista(actualAnio, actualMes, 'semanal');
  });

  btnAnterior?.addEventListener('click', () => {
    semana--;
    if (semana < 1) {
      semana = getMaxSemanasAnio(anio - 1);
      anio--;
    }
    redirigirSemana(anio, mes, semana);
  });

  btnSiguiente?.addEventListener('click', () => {
    semana++;
    const maxSemanas = getMaxSemanasAnio(anio);
    if (semana > maxSemanas) {
      semana = 1;
      anio++;
    }
    redirigirSemana(anio, mes, semana);
  });
}

function redirigirSemana(anio, mes, semana) {
  const base = window.location.pathname;
  window.location.href = `${base}?anio=${anio}&mes=${mes}&semana=${semana}&vista=semanal`;
}
