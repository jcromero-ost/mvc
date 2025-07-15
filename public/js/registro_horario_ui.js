// registro_horario_ui.js
import { iniciarCronometro, detenerCronometro, resetearCronometro } from './registro_horario_cronometro.js';

export function actualizarUIEstado(estado, datos) {
    if (!datos || typeof datos !== 'object') return;

    const textoEstado = document.getElementById('texto-estado');
    const estadoCronometro = document.getElementById('estado-cronometro');
    const btnIniciarJornada = document.getElementById('btn-iniciar-jornada');
    const btnIniciarDescanso = document.getElementById('btn-iniciar-descanso');
    const btnFinalizarDescanso = document.getElementById('btn-finalizar-descanso');
    const btnFinalizarJornada = document.getElementById('btn-finalizar-jornada');

    const horaInicioSpan = document.getElementById('hora-inicio');
    const horaFinSpan = document.getElementById('hora-fin');
    const tiempoTrabajadoSpan = document.getElementById('tiempo-trabajado');

    if (horaInicioSpan && datos.hora_inicio_jornada) {
        const inicio = new Date(datos.hora_inicio_jornada);
        horaInicioSpan.textContent = inicio.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    }

    if (horaFinSpan && datos.hora_fin_jornada) {
        const fin = new Date(datos.hora_fin_jornada);
        horaFinSpan.textContent = fin.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    }

    if (tiempoTrabajadoSpan) {
        const horas = Math.floor(datos.segundos_trabajados / 3600);
        const minutos = Math.floor((datos.segundos_trabajados % 3600) / 60);
        const segundos = datos.segundos_trabajados % 60;
        tiempoTrabajadoSpan.textContent = `${horas.toString().padStart(2, '0')}:${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')}`;
    }

    switch (estado) {
        case 'trabajando':
            if (textoEstado) textoEstado.textContent = 'Jornada en curso';
            if (estadoCronometro) actualizarColor(estadoCronometro, 'text-primary', 'Trabajando');
            iniciarCronometro();
            btnIniciarJornada?.classList.add('d-none');
            btnIniciarDescanso?.classList.remove('d-none');
            btnFinalizarDescanso?.classList.add('d-none');
            btnFinalizarJornada?.classList.remove('d-none');
            break;

        case 'descanso':
            if (textoEstado) textoEstado.textContent = 'En descanso';
            if (estadoCronometro) actualizarColor(estadoCronometro, 'text-warning', 'Descansando');
            detenerCronometro();
            btnIniciarJornada?.classList.add('d-none');
            btnIniciarDescanso?.classList.add('d-none');
            btnFinalizarDescanso?.classList.remove('d-none');
            btnFinalizarJornada?.classList.add('d-none'); // 👈 se oculta correctamente aquí
            break;

        case 'finalizado':
            if (textoEstado) textoEstado.textContent = 'Jornada finalizada';
            if (estadoCronometro) actualizarColor(estadoCronometro, 'text-success', 'Finalizada');
            detenerCronometro();
            btnIniciarJornada?.classList.remove('d-none');
            btnIniciarDescanso?.classList.add('d-none');
            btnFinalizarDescanso?.classList.add('d-none');
            btnFinalizarJornada?.classList.add('d-none');
            break;

        default:
            if (textoEstado) textoEstado.textContent = 'Esperando inicio...';
            resetearCronometro();
            break;
    }
}

function actualizarColor(elemento, claseColor, texto) {
    if (!elemento) return;
    elemento.className = '';
    elemento.classList.add(claseColor);
    elemento.textContent = texto;
}
