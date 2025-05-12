document.addEventListener("DOMContentLoaded", function () {  
    // Realiza las operaciones cuando se pulsa el boton
    document.querySelectorAll('.btn-lupa').forEach(btn => {
        btn.addEventListener('click', () => {
        const modal = new bootstrap.Modal(document.getElementById('modalSeleccionarCliente'));

        // Mostrar el modal
        modal.show();
        });
    });

    //-------------------------------------------------BOTONES FINALIZADO/PENDIENTE--------------------------------------------------
    const ticket_id = document.getElementById('id').value;
    const ticket_estado = document.getElementById('estado').value;
    const botonFinalizarTicket = document.getElementById('botonFinalizarTicket');
    const botonPendienteTicket = document.getElementById('botonPendienteTicket');

    if(ticket_estado === 'pendiente'){
        botonFinalizarTicket.classList.remove('d-none');
        botonPendienteTicket.classList.add('d-none');

    }else if (ticket_estado === 'finalizado'){
        botonFinalizarTicket.classList.add('d-none');
        botonPendienteTicket.classList.remove('d-none');
    }

    const mensaje = document.getElementById('mensaje');
    const badge = document.getElementById('estadoBadge');

    botonFinalizarTicket.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
        botonPendienteTicket.classList.remove('d-none');
        botonFinalizarTicket.classList.add('d-none');

        // Crear FormData con los datos que se necesitan enviar
        const formData = new FormData();
        formData.append('estado', 'finalizado'); // ID Ticket
        formData.append('id', ticket_id); // ID Ticket

        fetch('/update_estado', {
            method: 'POST',
            body: formData
        }).then(response => response.json())
        .then(data => {        
            if (data.success) {
                mensaje.textContent = 'Ticket finalizado correctamente.';
                mensaje.classList.add('text-black');
                mensaje.classList.add('bg-opacity-25');
                mensaje.classList.remove('bg-danger');
                mensaje.classList.add('bg-success');

                badge.textContent = 'Finalizado';
                badge.className = 'badge bg-success mb-4';

            } else {
                mensaje.textContent = 'Error al finalizar el ticket: ' + data.error;
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
    });

    botonPendienteTicket.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
        botonPendienteTicket.classList.add('d-none');
        botonFinalizarTicket.classList.remove('d-none');

        // Crear FormData con los datos que se necesitan enviar
        const formData = new FormData();
        formData.append('estado', 'pendiente'); // ID Ticket
        formData.append('id', ticket_id); // ID Ticket

        fetch('/update_estado', {
            method: 'POST',
            body: formData
        }).then(response => response.json())
        .then(data => {        
            if (data.success) {
                mensaje.textContent = 'Ticket marcado como pendiente correctamente.';
                mensaje.classList.add('text-black');
                mensaje.classList.add('bg-opacity-25');
                mensaje.classList.remove('bg-danger');
                mensaje.classList.add('bg-success');

                badge.textContent = 'Pendiente';
                badge.className = 'badge bg-danger mb-4';
            } else {
                mensaje.textContent = 'Error al finalizar el ticket: ' + data.error;
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
    });
  });
  