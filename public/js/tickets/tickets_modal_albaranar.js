document.addEventListener("DOMContentLoaded", function () {
    const ticket_id = document.getElementById('id').value;

    const botonFinalizarTicket = document.getElementById('botonFinalizarTicket');
    const botonAlbaranarTicket = document.getElementById('botonAlbaranarTicket');
    const botonPendienteTicket = document.getElementById('botonPendienteTicket');

    const mensaje = document.getElementById('mensaje');
    const badge = document.getElementById('estadoBadge');

    const modalAlbaranarTicket = new bootstrap.Modal(document.getElementById('modalAlbaranarTicket'));

    const botonConfirmAlbaranar = document.getElementById('botonConfirmAlbaranar');

    botonAlbaranarTicket.addEventListener('click', function(e){
        // Mostrar el modal
        modalAlbaranarTicket.show();
    });

    const descripcion_amplia_albaranar = document.getElementById('descripcion_amplia_albaranar');
    const descripcion = document.getElementById('descripcion').value;

    descripcion_amplia_albaranar.value = `Motivo: ${descripcion}\n\n`;

    botonConfirmAlbaranar.addEventListener('click', function(e){
        // Mostrar el modal
        modalAlbaranarTicket.show();

        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });

        botonPendienteTicket.classList.add('d-none');
        botonAlbaranarTicket.classList.add('d-none');
        botonFinalizarTicket.classList.add('d-none');

        const cliente_albaranar = document.getElementById('cliente_albaranar').value;
        const fecha_albaranar = document.getElementById('fecha_albaranar').value;
        const codigo_articulo_albaranar = document.getElementById('codigo_articulo_albaranar').value;
        const cantidad_albaranar = document.getElementById('cantidad_albaranar').value;
        const precio_albaranar = document.getElementById('precio_albaranar').value;

        // Crear FormData con los datos que se necesitan enviar
        const formData = new FormData();
        formData.append('cliente_albaranar', cliente_albaranar); // ID Cliente
        formData.append('fecha_albaranar', fecha_albaranar); // Fecha albaranar
        formData.append('codigo_articulo_albaranar', codigo_articulo_albaranar); // Codigo articulo
        formData.append('cantidad_albaranar', cantidad_albaranar); // Cantidad albaranar
        formData.append('precio_albaranar', precio_albaranar); // Precio albaranar
        formData.append('descripcion_amplia_albaranar', descripcion_amplia_albaranar.value); // Descripcion albaranar

        fetch('/store_albaranar', {
            method: 'POST',
            body: formData
        }).then(response => response.json())
        .then(data => {        
            if (data.success) {
                mensaje.textContent = 'Ticket albaranado correctamente.';
                mensaje.classList.add('text-black');
                mensaje.classList.add('bg-opacity-25');
                mensaje.classList.remove('bg-danger');
                mensaje.classList.add('bg-success');

                badge.textContent = 'Albaranado';
                badge.className = 'badge bg-primario mb-4';

                actualizarEstado();
                desactivarEdicionFormulario();
                document.querySelectorAll('button').forEach(el => el.classList.add('d-none'));

            } else {
                mensaje.textContent = 'Error al albaranar el ticket: ' + data.error;
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

    function actualizarEstado(){
        // Crear FormData con los datos que se necesitan enviar
        const formData = new FormData();
        formData.append('estado', 'albaranado'); // ID Ticket
        formData.append('id', ticket_id); // ID Ticket

        fetch('/update_estado', {
            method: 'POST',
            body: formData
        }).then(response => response.json())
        .then(data => {        
            if (data.success) {
                mensaje.textContent = 'Ticket albaranado correctamente.';
                mensaje.classList.add('text-black');
                mensaje.classList.add('bg-opacity-25');
                mensaje.classList.remove('bg-danger');
                mensaje.classList.add('bg-success');

                badge.textContent = 'Albaranado';
                badge.className = 'badge bg-primario mb-4';
            } else {
                mensaje.textContent = 'Error al albaranar el ticket: ' + data.error;
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
    }

    const nuevoComentarioBtn = document.getElementById('nuevoComentarioBtn');    
    const nuevoComentarioInternoBtn = document.getElementById('nuevoComentarioInternoBtn');
    const botonFormulario = document.getElementById('botonFormulario');

    const formulario_editar = document.getElementById('formEditarTicket');

    function desactivarEdicionFormulario(){
        const inputs = formulario_editar.querySelectorAll('input, textarea'); // Selecciona todos los inputs, textarea y selects
        const selects = formulario_editar.querySelectorAll('select');

        inputs.forEach(input => {
            input.readOnly = true; // Establece 'readonly' en todos los campos
        });

        selects.forEach(select =>{
            select.disabled = true;
        });

        botonFinalizarTicket.classList.add('d-none');
        botonPendienteTicket.classList.add('d-none');
        botonAlbaranarTicket.classList.add('d-none');

        nuevoComentarioBtn.classList.add('d-none');
        nuevoComentarioInternoBtn.classList.add('d-none');

        botonFormulario.classList.add('d-none');
    }
});