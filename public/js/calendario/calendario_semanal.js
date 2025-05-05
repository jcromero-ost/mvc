import { nombresMes, redirigirVista } from './calendario_nav.js';

export function cargarSemanal(anio, mes) {
  const tituloMes = document.getElementById('tituloMes');
  const vistaSemanal = document.getElementById('vistaSemanal');
  const btnHoy = document.getElementById('btnHoy');
  const btnAnterior = document.getElementById('btnMesAnterior');
  const btnSiguiente = document.getElementById('btnMesSiguiente');

  const url = new URL(window.location.href);
  let semana = parseInt(url.searchParams.get('semana'));

  // Calcular semana actual si no viene en URL
  const hoy = new Date();
  const primeraSemana = new Date(anio, mes - 1, 1);
  const currentSemana = getNumeroSemana(hoy);

  if (!semana || isNaN(semana)) {
    semana = getNumeroSemana(primeraSemana);
  }

  tituloMes.textContent = `Semana ${semana} - ${anio}`;
  vistaSemanal?.classList.remove('d-none');

  btnHoy?.addEventListener('click', () => {
    const actual = new Date();
    const actualAnio = actual.getFullYear();
    const actualMes = actual.getMonth() + 1;
    const actualSemana = getNumeroSemana(actual);
    redirigirSemana(actualAnio, actualMes, actualSemana);
  });

  btnAnterior?.addEventListener('click', () => {
    semana--;
    if (semana < 1) {
      semana = 52;
      anio--;
    }
    redirigirSemana(anio, mes, semana);
  });

  btnSiguiente?.addEventListener('click', () => {
    semana++;
    if (semana > 52) {
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

function getNumeroSemana(date) {
  const temp = new Date(Date.UTC(date.getFullYear(), date.getMonth(), date.getDate()));
  const dayNum = temp.getUTCDay() || 7;
  temp.setUTCDate(temp.getUTCDate() + 4 - dayNum);
  const yearStart = new Date(Date.UTC(temp.getUTCFullYear(), 0, 1));
  return Math.ceil((((temp - yearStart) / 86400000) + 1) / 7);
}
