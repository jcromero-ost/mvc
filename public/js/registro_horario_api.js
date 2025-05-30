// registro_horario_api.js
import { actualizarUIEstado } from './registro_horario_ui.js';
import { setSegundos } from './registro_horario_cronometro.js';

const userId = document.getElementById('user_id')?.value || 1;

export async function enviarEvento(tipoEvento) {
    try {
        const response = await fetch('/registro_horario/store', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `user_id=${encodeURIComponent(userId)}&tipo_evento=${encodeURIComponent(tipoEvento)}`
        });

        // Redirige al login si la sesión ha expirado
        if (response.status === 401) {
            window.location.href = '/login';
            return;
        }

        const data = await response.json();
        if (data.success) {
            await cargarEstadoActual();
        } else {
            console.error('Error al registrar evento', data.message);
        }
    } catch (error) {
        console.error('Error enviando evento:', error);
        window.location.href = '/login'; // fallback por error grave
    }
}

export async function cargarEstadoActual() {
    try {
        const response = await fetch('/registro_horario/historial');

        // Redirige al login si no hay sesión activa
        if (response.status === 401 || response.status === 403) {
            window.location.href = '/login';
            return;
        }

        const data = await response.json();
        if (data.success && data.data) {
            setSegundos(data.data.segundos_trabajados);
            actualizarUIEstado(data.data.estado_actual, data.data);
        } else {
            console.error('Error al cargar estado actual', data.message);
        }
    } catch (error) {
        console.error('Error cargando estado actual:', error);
        window.location.href = '/login'; // fallback por error grave
    }
}
