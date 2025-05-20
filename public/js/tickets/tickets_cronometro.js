document.addEventListener("DOMContentLoaded", function () {  
    // Variables para el cronómetro
    var intervalo;
    var isRunning = false;
    var segundos = 0;
    var minutos = 0;
    var horas = 0;
    var horaInicio = null;

    const ticket_id = document.getElementById('id').value;
    const ticket_estado = document.getElementById('estado').value;

    const mensaje = document.getElementById('mensaje');
    const badge = document.getElementById('estadoBadge');



    // Referencias a los elementos HTML
    const timerDisplay = document.getElementById('timer');
    const iniciarBtn = document.getElementById('iniciarBtn');
    const detenerBtn = document.getElementById('detenerBtn');
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

        // Actualizar estado
        const botonFinalizarTicket = document.getElementById('botonFinalizarTicket');
        const botonPendienteTicket = document.getElementById('botonPendienteTicket');
        const botonAlbaranarTicket = document.getElementById('botonAlbaranarTicket');

        // Crear FormData con los datos que se necesitan enviar
        const formData = new FormData();
        formData.append('estado', 'en_revision'); // ID Ticket
        formData.append('id', ticket_id); // ID Ticket

        fetch('/update_estado', {
            method: 'POST',
            body: formData
        }).then(response => response.json())
        .then(data => {        
            if (data.success) {
                badge.textContent = 'En Revisión';
                badge.className = 'badge bg-warning mb-4';

                botonFinalizarTicket.classList.remove('d-none');
                botonPendienteTicket.classList.add('d-none');
                botonAlbaranarTicket.classList.add('d-none');
            } else {
                mensaje.textContent = 'Error al actualizar el estado: ' + data.error;
                mensaje.classList.add('text-black');
                mensaje.classList.add('bg-opacity-100');
                mensaje.classList.remove('bg-success');
                mensaje.classList.add('bg-danger');
            }
        })
        .catch(error => {
            console.error('Error en la petición:', error);
        });
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

        // Crear FormData con los datos que se necesitan enviar
        const formData = new FormData();
        formData.append('ticket_id', ticket_id); // ID Ticket
        formData.append('fecha', fecha); // Fecha y hora actual en formato ISO
        formData.append('tiempo', tiempo); // Tiempo
        formData.append('hora_inicio', horaInicio); // Hora incio
        formData.append('hora_fin', horaFin); // Hora incio


        fetch('/store_ticket_cronometro', {
            method: 'POST',
            body: formData
        }).then(response => response.json())
        .then(data => {
            if (data.success) { 
                                //Actualiza el tiempo
                document.getElementById('tiempo-total').textContent = data.tiempo_total;
            }else{
                console.error('Error:', data.error);
            }
        })
        .catch(error => {
            console.error('Error en la petición:', error);
        });

        cargarRegistrosCronometro(ticket_id);
    });

    //Cargar registros en la tabla
    function cargarRegistrosCronometro(id_ticket) {
        fetch('/get_cronometro', {
            method: 'POST',
            headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `id=${encodeURIComponent(id_ticket)}`
        }).then(response => response.json())
        .then(data => {
            if (data.success && data.cronometro_registros.length === 0) {
                tablaModificaciones.innerHTML = "<tr><td colspan='4' class='text-center py-4'>Sin registros</td></tr>";
            } else if (data.success && data.cronometro_registros.length > 0) {
                tablaModificaciones.innerHTML = data.cronometro_registros.map((registro, i) => `
                    <tr class="${i % 2 === 0 ? 'bg-white text-dark' : 'table-secondary text-dark'}">
                        <td>${registro.tiempo}</td>
                        <td>${registro.nombre_usuario}</td>
                    </tr>
                `).join("");
            }
        })
        .catch(error => {
            console.error('Error en la petición:', error);
            tablaModificaciones.innerHTML = "<tr><td colspan='4' class='text-center py-4'>Error al procesar la solicitud.</td></tr>";
        });
    }

    cargarRegistrosCronometro(ticket_id);

    //Agregar registro a mano
    guardarRegistro.addEventListener('click', () => {
        // Obtener las horas de inicio y fin desde los campos de entrada tipo TIME
        const hora_inicio_input = document.getElementById('registroHoraInicio');
        const hora_fin_input = document.getElementById('registroHoraFin');
        
        const hora_inicio = hora_inicio_input ? hora_inicio_input.value : null;
        const hora_fin = hora_fin_input ? hora_fin_input.value : null;

        if (!hora_inicio || !hora_fin) {
            console.error("Debe ingresar ambas horas de inicio y fin.");
            return;
        }

        if (hora_inicio > hora_fin){
            return;
        }

        const fecha = new Date().toISOString().split('T')[0]; // YYYY-MM-DD
        
        // Calcular el tiempo entre las dos horas (en formato HH:MM:SS)
        const tiempo = calcularTiempo(hora_inicio, hora_fin);

        if (!tiempo) {
            console.error("Las horas de inicio y fin no son válidas.");
            return;
        }

        // Crear FormData con los datos que se necesitan enviar
        const formData = new FormData();
        formData.append('ticket_id', ticket_id); // ID Ticket
        formData.append('fecha', fecha); // Fecha y hora actual en formato ISO
        formData.append('tiempo', tiempo); // Tiempo
        formData.append('hora_inicio', hora_inicio); // Hora incio
        formData.append('hora_fin', hora_fin); // Hora incio


        fetch('/store_ticket_cronometro', {
            method: 'POST',
            body: formData
        }).then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error('Error:', data.error); 
            }
        })
        .catch(error => {
            console.error('Error en la petición:', error);
        });

        cargarRegistrosCronometro(ticket_id);

    });

    // Funcion para calcular el tiempo
    function calcularTiempo(hora_inicio, hora_fin) {
        const [inicio_hora, inicio_minutos] = hora_inicio.split(':').map(Number);
        const [fin_hora, fin_minutos] = hora_fin.split(':').map(Number);

        // Crear objetos de fecha para las horas de inicio y fin
        const inicio = new Date(0, 0, 0, inicio_hora, inicio_minutos);
        const fin = new Date(0, 0, 0, fin_hora, fin_minutos);

        // Calcular la diferencia en milisegundos
        const diferencia = fin - inicio;

        // Si la diferencia es negativa, asumimos que la hora de fin es al día siguiente
        if (diferencia < 0) {
            fin.setDate(fin.getDate() + 1);
        }

        // Calcular la diferencia en horas, minutos y segundos
        const horas = String(Math.floor(diferencia / (1000 * 60 * 60))).padStart(2, '0');
        const minutos = String(Math.floor((diferencia % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
        const segundos = String(Math.floor((diferencia % (1000 * 60)) / 1000)).padStart(2, '0');

        return `${horas}:${minutos}:${segundos}`;
    }

    const botonTraspasarTicket = document.getElementById('botonTraspasarTicket');
    const modalTraspasarTicket = new bootstrap.Modal(document.getElementById('modalTraspasarTicket'));
    const botonConfirmTraspaso = document.getElementById('botonConfirmTraspaso');

    botonTraspasarTicket.addEventListener('click', () =>{
        modalTraspasarTicket.show();
    })

    botonConfirmTraspaso.addEventListener('click', () =>{
        const tecnico_modal = document.getElementById('tecnico_modal').value;
        const tecnico = document.getElementById('tecnico');

        // Crear FormData con los datos que se necesitan enviar
        const formData = new FormData();
        formData.append('tecnico_id', tecnico_modal); // ID Ticket
        formData.append('id', ticket_id); // ID Ticket

        fetch('/update_tecnico', {
            method: 'POST',
            body: formData
        }).then(response => response.json())
        .then(data => {        
            if (data.success) {
                mensaje.textContent = 'Ticket traspasado correctamente.';
                mensaje.classList.add('text-black');
                mensaje.classList.add('bg-opacity-25');
                mensaje.classList.remove('bg-danger');
                mensaje.classList.add('bg-success');

                tecnico.value = tecnico_modal;
            } else {
                mensaje.textContent = 'Error al traspasar el ticket: ' + data.error;
                mensaje.classList.add('text-black');
                mensaje.classList.add('bg-opacity-100');
                mensaje.classList.remove('bg-success');
                mensaje.classList.add('bg-danger');
            }

            mensaje.classList.remove('d-none');

            // Ocultar después de 2 segundos (2000 ms)
            setTimeout(() => {
                mensaje.classList.add('d-none');
            }, 2000);
        })
        .catch(error => {
            console.error('Error en la petición:', error);
        });
    })

    const agregarRegistroBtn = document.getElementById('agregarRegistroBtn');
    const timer = document.getElementById('timer');

    if(ticket_estado === 'albaranado'){
        document.querySelectorAll('button').forEach(el => el.classList.add('d-none'));

        timer.classList.add('d-none');
    } 
});