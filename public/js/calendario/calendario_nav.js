export function getFechaDesdeURL() {
  const url = new URL(window.location.href);
  return {
    anio: parseInt(url.searchParams.get('anio')) || new Date().getFullYear(),
    mes: parseInt(url.searchParams.get('mes')) || (new Date().getMonth() + 1),
    vista: url.searchParams.get('vista') || 'mensual'
  };
}

export function redirigirVista(anio, mes, modo) {
  const base = window.location.pathname;

  if (modo === 'anual') {
    window.location.href = `${base}?anio=${anio}&vista=anual`;
  } else if (modo === 'semanal') {
    const semana = getNumeroSemana(new Date(anio, mes - 1, 1));
    window.location.href = `${base}?anio=${anio}&mes=${mes}&semana=${semana}&vista=semanal`;
  } else {
    window.location.href = `${base}?anio=${anio}&mes=${mes}&vista=mensual`;
  }
}

export function getNumeroSemana(fecha) {
  const temp = new Date(Date.UTC(fecha.getFullYear(), fecha.getMonth(), fecha.getDate()));
  const dayNum = temp.getUTCDay() || 7;
  temp.setUTCDate(temp.getUTCDate() + 4 - dayNum);
  const yearStart = new Date(Date.UTC(temp.getUTCFullYear(), 0, 1));
  return Math.ceil((((temp - yearStart) / 86400000) + 1) / 7);
}

export function getMaxSemanasAnio(anio) {
  const d = new Date(anio, 11, 31); // 31 de diciembre
  return getNumeroSemana(d);
}

export const nombresMes = [
  'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
  'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
];
