// Variables para el cronómetro
var intervalo;
var isRunning = false;
var segundos = 0;
var minutos = 0;
var horas = 0;
var horaInicio = null;

// Referencias a los elementos HTML
const timerDisplay = document.getElementById('timer');
const iniciarBtn = document.getElementById('iniciarBtn');
const detenerBtn = document.getElementById('detenerBtn');
const ticketInput = document.getElementById('id');
const tablaModificaciones = document.getElementById("tablaModificaciones");
const guardarRegistro = document.getElementById("guardarRegistro");

const nuevoComentarioBtn = document.getElementById('nuevoComentarioBtn');

// Función para actualizar el tiempo en pantalla
function actualizarTiempo() {
    let sec = segundos < 10 ? "0" + segundos : segundos;
    let min = minutos < 10 ? "0" + minutos : minutos;
    let hr = horas < 10 ? "0" + horas : horas;
    timerDisplay.innerHTML = `<p>${hr}:${min}:${sec}</p>`;
}

// Función para obtener la hora en formato HH:MM:SS
function obtenerHoraFormato() {
    const ahora = new Date();
    const horas = String(ahora.getHours()).padStart(2, '0');
    const minutos = String(ahora.getMinutes()).padStart(2, '0');
    const segundos = String(ahora.getSeconds()).padStart(2, '0');
    return `${horas}:${minutos}:${segundos}`;
}

// Función para iniciar el cronómetro
function iniciarTiempo() {
  intervalo = setInterval(() => {
      segundos++;
        if (segundos === 60) {
            segundos = 0;
            minutos++;
        }
        if (minutos === 60) {
            minutos = 0;
            horas++;
        }
        actualizarTiempo();
    }, 1000);

    // Guardar la hora de inicio al pulsar Iniciar
    horaInicio = obtenerHoraFormato();  // Guardamos la hora exacta de fin
    console.log('Hora de inicio:', horaInicio);
}

// Función para detener el cronómetro
function pararTiempo() {
    clearInterval(intervalo);
}

// Evento para iniciar o pausar el cronómetro
iniciarBtn.addEventListener('click', () => {
    nuevoComentarioBtn.classList.remove('d-none');

    if (!isRunning) {
        iniciarTiempo();
        iniciarBtn.textContent = 'Pausar';
        isRunning = true;
    } else {
        pararTiempo();
        nuevoComentarioBtn.classList.add('d-none');
        iniciarBtn.textContent = 'Reanudar';
        isRunning = false;
    }
});

// Evento para detener y guardar los datos
detenerBtn.addEventListener('click', () => {
    nuevoComentarioBtn.classList.add('d-none');

    pararTiempo();

    // Guardar la hora de fin al pulsar "Detener"
    const horaFin = obtenerHoraFormato();  // Guardamos la hora exacta de fin
    console.log('Hora de fin:', horaFin);

    const tiempo = timerDisplay.querySelector('p').textContent;
    const fecha = new Date().toISOString().split('T')[0]; // YYYY-MM-DD

    // Resetear cronómetro
    segundos = 0;
    minutos = 0;
    horas = 0;
    actualizarTiempo();
    iniciarBtn.textContent = 'Iniciar';
    isRunning = false;
});