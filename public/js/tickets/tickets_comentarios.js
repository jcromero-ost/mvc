document.addEventListener("DOMContentLoaded", function () {
    // Div comentarios pagina principal  
    const comentariosDiv = document.getElementById('comentariosDiv');

    const nuevoComentarioBtn = document.getElementById('nuevoComentarioBtn');

    nuevoComentarioBtn.addEventListener('click', function() {
        const comentarioIndividualDiv = document.createElement('div');
        comentarioIndividualDiv.classList.add('m-4');

        // Crear textarea donde ira el comentario
        const newTextarea = document.createElement('textarea');
        newTextarea.classList.add('form-control');
        newTextarea.placeholder = "Escribe un comentario...";

        // Crear botón para eliminar el textarea
        const botonDeleteComentario = document.createElement('button');
        botonDeleteComentario.classList.add('btn', 'btn-danger', 'me-2', 'btn-sm', 'mt-1');
        botonDeleteComentario.textContent = "Eliminar Comentario";
        botonDeleteComentario.setAttribute('type', 'button');

        // Crear boton para guardar el comentario
        const botonSaveComentario = document.createElement('button');
        botonSaveComentario.classList.add('btn', 'btn-primary', 'btn-sm', 'mt-1');
        botonSaveComentario.textContent = "Guardar Comentario";
        botonSaveComentario.setAttribute('type', 'button');

        // Crear un span para mostrar la fecha y hora
        const fechaHora = document.createElement('span');
        const fechaActual = new Date();

        // Obtener la fecha formateada en YYYY-MM-DD HH:MM:SS
        const anio = fechaActual.getFullYear();
        const mes = String(fechaActual.getMonth() + 1).padStart(2, '0');
        const dia = String(fechaActual.getDate()).padStart(2, '0');
        const horas = String(fechaActual.getHours()).padStart(2, '0');
        const minutos = String(fechaActual.getMinutes()).padStart(2, '0');
        const segundos = String(fechaActual.getSeconds()).padStart(2, '0');

        const dateTimeFormatted = `${anio}-${mes}-${dia} ${horas}:${minutos}:${segundos}`;

        
        fechaHora.textContent = `Fecha y Hora: ${dateTimeFormatted}`;
        fechaHora.classList.add('block', 'mt-2', 'text-sm', 'text-gray-500');

        // Agregar el textarea y la fecha al div contenedor
        comentarioIndividualDiv.appendChild(fechaHora);
        comentarioIndividualDiv.appendChild(newTextarea);
        comentarioIndividualDiv.appendChild(botonDeleteComentario);
        comentarioIndividualDiv.appendChild(botonSaveComentario);

        //Agregar el div individual al div de la pagina principal
        comentariosDiv.appendChild(comentarioIndividualDiv);

        // Agregar funcionalidad al botón de eliminar
        botonDeleteComentario.addEventListener('click', function() {
            comentariosDiv.removeChild(comentarioIndividualDiv); // Eliminar el div completo
        });

        // Agregar funcionalidad al botón de guardar
        botonSaveComentario.addEventListener('click', function() {
            // Crear FormData con los datos que se necesitan enviar
            const formData = new FormData();
            formData.append('id_ticket', 2); // ID Ticket
            formData.append('fecha', dateTimeFormatted); // Fecha y hora actual en formato ISO
            formData.append('contenido', newTextarea.value); // El comentario

            console.log(formData);

            fetch('/store_ticket_comentarios', {
                method: 'POST',
                body: formData
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Comentario guardado correctamente');
                } else {
                    alert('Error al guardar comentario: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error en la petición:', error);
            });
        });
    });

});
  