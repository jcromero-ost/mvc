// registro_horario_cronometro.js
let cronometroInterval;
let segundosTranscurridos = 0;

export function iniciarCronometro() {
    detenerCronometro();
    cronometroInterval = setInterval(actualizarCronometro, 1000);
}

export function detenerCronometro() {
    if (cronometroInterval) clearInterval(cronometroInterval);
}

export function resetearCronometro() {
    detenerCronometro();
    segundosTranscurridos = 0;
    actualizarVistaCronometro();
}

export function actualizarCronometro() {
    segundosTranscurridos++;
    actualizarVistaCronometro();
}

export function setSegundos(segundos) {
    segundosTranscurridos = segundos;
}

export function getSegundos() {
    return segundosTranscurridos;
}

function actualizarVistaCronometro() {
    const cronometro = document.getElementById('cronometro');
    if (cronometro) {
        const horas = Math.floor(segundosTranscurridos / 3600);
        const minutos = Math.floor((segundosTranscurridos % 3600) / 60);
        const segundos = segundosTranscurridos % 60;
        cronometro.textContent = `${horas.toString().padStart(2, '0')}:${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')}`;
    }
}
