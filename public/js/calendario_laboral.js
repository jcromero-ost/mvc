document.addEventListener('DOMContentLoaded', () => {
    const modoVista = document.getElementById('modoVista');
    const vistaMensual = document.getElementById('vistaMensual');
    const vistaAnual = document.getElementById('vistaAnual');
    const vistaSemanal = document.getElementById('vistaSemanal');
    const barraNavegacion = document.getElementById('barraNavegacion');
  
    const btnHoy = document.getElementById('btnHoy');
    const btnAnterior = document.getElementById('btnMesAnterior');
    const btnSiguiente = document.getElementById('btnMesSiguiente');
    const tituloMes = document.getElementById('tituloMes');
  
    // Obtener año y mes desde la URL
    const url = new URL(window.location.href);
    let anio = parseInt(url.searchParams.get('anio')) || new Date().getFullYear();
    let mes = parseInt(url.searchParams.get('mes')) || (new Date().getMonth() + 1);
  
    const nombresMes = [
      'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
      'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ];
  
    function actualizarTitulo() {
      const modo = modoVista.value;
      if (modo === 'mensual') {
        tituloMes.textContent = `${nombresMes[mes - 1]} de ${anio}`;
      } else if (modo === 'anual') {
        tituloMes.textContent = `${anio}`;
      } else if (modo === 'semanal') {
        // Para demo: usar la primera semana del mes
        const primera = new Date(anio, mes - 1, 1);
        const lunes = new Date(primera);
        if (lunes.getDay() !== 1) {
          const diff = (lunes.getDay() === 0) ? -6 : 1 - lunes.getDay();
          lunes.setDate(lunes.getDate() + diff);
        }
        const domingo = new Date(lunes);
        domingo.setDate(lunes.getDate() + 6);
        tituloMes.textContent = `Semana del ${lunes.getDate()} al ${domingo.getDate()} de ${nombresMes[mes - 1]} ${anio}`;
      }
    }
  
    function actualizarVista() {
      const modo = modoVista.value;
  
      vistaMensual?.classList.add('d-none');
      vistaAnual?.classList.add('d-none');
      vistaSemanal?.classList.add('d-none');
  
      if (modo === 'mensual') {
        vistaMensual?.classList.remove('d-none');
        barraNavegacion?.classList.remove('d-none');
        btnAnterior?.classList.remove('d-none');
        btnSiguiente?.classList.remove('d-none');
        btnHoy?.classList.remove('d-none');
      } else if (modo === 'anual') {
        vistaAnual?.classList.remove('d-none');
        barraNavegacion?.classList.add('d-none'); // opcionalmente ocultar nav
      } else if (modo === 'semanal') {
        vistaSemanal?.classList.remove('d-none');
        barraNavegacion?.classList.remove('d-none');
        btnAnterior?.classList.remove('d-none');
        btnSiguiente?.classList.remove('d-none');
        btnHoy?.classList.remove('d-none');
      }
  
      actualizarTitulo();
    }
  
    function redirigir(a, m) {
      const modo = modoVista.value;
      const base = window.location.pathname;
      window.location.href = `${base}?anio=${a}&mes=${m}&vista=${modo}`;
    }
  
    // Navegación
    btnHoy?.addEventListener('click', () => {
      const hoy = new Date();
      redirigir(hoy.getFullYear(), hoy.getMonth() + 1);
    });
  
    btnAnterior?.addEventListener('click', () => {
      if (modoVista.value === 'mensual' || modoVista.value === 'semanal') {
        mes--;
        if (mes < 1) {
          mes = 12;
          anio--;
        }
        redirigir(anio, mes);
      }
    });
  
    btnSiguiente?.addEventListener('click', () => {
      if (modoVista.value === 'mensual' || modoVista.value === 'semanal') {
        mes++;
        if (mes > 12) {
          mes = 1;
          anio++;
        }
        redirigir(anio, mes);
      }
    });
  
    modoVista?.addEventListener('change', actualizarVista);
    actualizarVista();
  });
  