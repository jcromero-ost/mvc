document.addEventListener("DOMContentLoaded", function () {  
    const formulario_editar = document.getElementById('formEditarTicket');

    const mensaje = document.getElementById('mensaje');
    
    // Realiza las operaciones cuando se pulsa el boton
    formulario_editar.addEventListener('submit', function(e) {
        e.preventDefault();  // Prevenir el envío tradicional

        const medio_comunicacion = document.getElementById('medio_comunicacion').value;
        const tecnico = document.getElementById('tecnico').value;
        const descripcion = document.getElementById('descripcion').value;

        // Crear FormData con los datos que se necesitan enviar
        const formData = new FormData();
        formData.append('medio_comunicacion', medio_comunicacion); // ID Ticket
        formData.append('tecnico', tecnico); // ID Ticket
        formData.append('descripcion', descripcion); // ID Ticket
        formData.append('id', ticket_id); // ID Ticket

        console.log(medio_comunicacion);
        console.log(tecnico);
        console.log(descripcion);
        console.log(ticket_id);


        fetch('/store_ticket_editar', {
            method: 'POST',
            body: formData
        }).then(response => response.json())
        .then(data => {        
            if (data.success) {
                mensaje.textContent = 'Ticket editado correctamente.';
                mensaje.classList.add('text-black');
                mensaje.classList.add('bg-opacity-25');
                mensaje.classList.remove('bg-danger');
                mensaje.classList.add('bg-success');
            } else {
                mensaje.textContent = 'Error al editar el ticket: ' + data.error;
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

    //-------------------------------------------------BOTONES FINALIZADO/PENDIENTE--------------------------------------------------
    const ticket_id = document.getElementById('id').value;
    const ticket_estado = document.getElementById('estado').value;
    const botonFinalizarTicket = document.getElementById('botonFinalizarTicket');
    const botonAlbaranarTicket = document.getElementById('botonAlbaranarTicket');
    const botonPendienteTicket = document.getElementById('botonPendienteTicket');

    const nuevoComentarioBtn = document.getElementById('nuevoComentarioBtn');    
    const nuevoComentarioInternoBtn = document.getElementById('nuevoComentarioInternoBtn');
    const botonFormulario = document.getElementById('botonFormulario');

    const botonDeleteComentario = document.getElementById('botonDeleteComentario');
    const botonEditComentario = document.getElementById('botonEditComentario');

    if(ticket_estado === 'pendiente'){
        botonFinalizarTicket.classList.remove('d-none');
        botonPendienteTicket.classList.add('d-none');
        botonAlbaranarTicket.classList.add('d-none');

    }else if (ticket_estado === 'finalizado'){
        botonFinalizarTicket.classList.add('d-none');
        botonPendienteTicket.classList.remove('d-none');
        botonAlbaranarTicket.classList.remove('d-none');
    }else if (ticket_estado === 'albaranado'){
        desactivarEdicionFormulario();
    }

    function desactivarEdicionFormulario(){
        const inputs = formulario_editar.querySelectorAll('input, textarea'); // Selecciona todos los inputs, textarea y selects
        const selects = formulario_editar.querySelectorAll('select');

        inputs.forEach(input => {
            input.readOnly = true; // Establece 'readonly' en todos los campos
        });

        selects.forEach(select =>{
            select.disabled = true;
        });

        document.querySelectorAll('button').forEach(el => el.classList.add('d-none'));
    }

    const badge = document.getElementById('estadoBadge');

    botonFinalizarTicket.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
        botonPendienteTicket.classList.remove('d-none');
        botonAlbaranarTicket.classList.remove('d-none');
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
        botonAlbaranarTicket.classList.add('d-none');
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

    botonAlbaranarTicket.addEventListener('click', function() {

    });

  });
  