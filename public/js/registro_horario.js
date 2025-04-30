// registro_horario.js
import { enviarEvento, cargarEstadoActual } from './registro_horario_api.js';

document.addEventListener('DOMContentLoaded', function () {
    const btnIniciarJornada = document.getElementById('btn-iniciar-jornada');
    const btnIniciarDescanso = document.getElementById('btn-iniciar-descanso');
    const btnFinalizarDescanso = document.getElementById('btn-finalizar-descanso');
    const btnFinalizarJornada = document.getElementById('btn-finalizar-jornada');

    btnIniciarJornada?.addEventListener('click', () => enviarEvento('inicio_jornada'));
    btnIniciarDescanso?.addEventListener('click', () => enviarEvento('inicio_descanso'));
    btnFinalizarDescanso?.addEventListener('click', () => enviarEvento('fin_descanso'));
    btnFinalizarJornada?.addEventListener('click', () => enviarEvento('fin_jornada'));

    setInterval(() => {
        const reloj = document.getElementById('reloj');
        if (reloj) {
            reloj.textContent = new Date().toLocaleTimeString('es-ES', { hour12: false });
        }
    }, 1000);

    cargarEstadoActual();
});
