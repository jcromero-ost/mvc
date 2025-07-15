// === CRONÓMETRO PANEL ACTUALIZADO ===
window.addEventListener('DOMContentLoaded', () => {
  let segundos = 0;
  let intervalo = null;
  let hora_inicio = null;
  let hora_fin = null;
  let ticket_id = null;
  let ultimaApertura = 0;

  const formatTiempo = (seg) => {
    const h = String(Math.floor(seg / 3600)).padStart(2, '0');
    const m = String(Math.floor((seg % 3600) / 60)).padStart(2, '0');
    const s = String(seg % 60).padStart(2, '0');
    return `${h}:${m}:${s}`;
  };

  const updateDisplay = () => {
    const tiempo = formatTiempo(segundos);
    document.getElementById('cronometroTiempo').textContent = tiempo;
    document.getElementById('tiempo_trabajado_crono').value = tiempo;
  };

  const calcularTiempo = (inicio, fin) => {
    const total = Math.floor((fin - inicio) / 1000);
    return formatTiempo(total);
  };

  const cronometroCanvas = document.getElementById('offcanvasCronometro');
  const mainContainer = document.querySelector('main');
  const btnIniciar = document.getElementById('cronometroIniciar');
  const btnDetener = document.getElementById('cronometroDetener');
  const btnToggle = document.getElementById('btnToggleCronometro');
  const btnCerrar = document.getElementById('btnCerrarCronometro');
  const contenedorComentario = document.getElementById('contenedorNuevoComentario');
  const comentarioIdInput = document.getElementById('comentarioId');

  const cronometro = new bootstrap.Offcanvas(cronometroCanvas, {
    backdrop: false,
    keyboard: false
  });

  btnToggle?.addEventListener('click', () => {
    ticket_id = btnToggle.dataset.id;
    cronometro.show();
  });

  btnCerrar?.addEventListener('click', () => {
    if (intervalo !== null) {
      alert('Debes detener el cronómetro antes de cerrar.');
    } else {
      cronometro.hide();
    }
  });

  cronometroCanvas.addEventListener('hide.bs.offcanvas', (e) => {
    if (intervalo !== null) {
      e.preventDefault();
      alert('Debes detener el cronómetro antes de cerrar el panel.');
    }
  });

  cronometroCanvas.addEventListener('show.bs.offcanvas', () => {
    mainContainer?.classList.add('con-cronometro');
    ultimaApertura = Date.now();
  });

  cronometroCanvas.addEventListener('hidden.bs.offcanvas', () => {
    mainContainer?.classList.remove('con-cronometro');
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && intervalo === null && Date.now() - ultimaApertura > 300) {
      cronometro.hide();
    }
  });

  btnIniciar?.addEventListener('click', () => {
    if (intervalo !== null) return;

    hora_inicio = new Date();
    const horaInicioStr = hora_inicio.toTimeString().slice(0, 8);
    ticket_id = btnIniciar.dataset.ticketId;

    const formData = new FormData();
    formData.append('ticket_id', ticket_id);
    formData.append('hora_inicio', horaInicioStr);

    fetch('/store_ticket_comentariosSoloFecha', {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.id && comentarioIdInput) {
        comentarioIdInput.value = data.id;
        comentarioIdInput.dataset.horaInicio = horaInicioStr;
        comentarioIdInput.dataset.horaFin = '';

        const modal = new bootstrap.Modal(document.getElementById('modalComentario'));
        modal.show();

        segundos = 0;
        updateDisplay();
        intervalo = setInterval(() => {
          segundos++;
          updateDisplay();
        }, 1000);

        btnIniciar.classList.add('d-none');
        btnDetener.classList.remove('d-none');
      } else {
        console.error('Error del servidor:', data.error || 'Sin respuesta válida');
      }
    })
    .catch(err => console.error('Error al iniciar cronómetro:', err));
  });

  btnDetener?.addEventListener('click', () => {
    clearInterval(intervalo);
    intervalo = null;
    hora_fin = new Date();
    const tiempoTotal = calcularTiempo(hora_inicio, hora_fin);
    updateDisplay();
    segundos = 0;

    btnDetener.classList.add('d-none');
    btnIniciar.classList.remove('d-none');

    const formData = new FormData();
    formData.append('ticket_id', ticket_id);
    formData.append('hora_inicio', hora_inicio.toTimeString().slice(0, 8));
    formData.append('hora_fin', hora_fin.toTimeString().slice(0, 8));
    formData.append('tiempo', tiempoTotal);

    fetch('/store_ticket_cronometro', {
      method: 'POST',
      body: formData
    })
    .then(res => res.ok ? res.text() : Promise.reject('Error al guardar'))
    .then(() => {
      if (comentarioIdInput) {
        comentarioIdInput.dataset.horaFin = hora_fin.toTimeString().slice(0, 8);
      }
      console.log('Cronómetro guardado correctamente.');
    })
    .catch(err => console.error('Error al guardar cronómetro:', err));
  });
});
// === FIN CRONÓMETRO PANEL ===
