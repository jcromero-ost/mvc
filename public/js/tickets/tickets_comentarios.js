document.addEventListener("DOMContentLoaded", function () {
    // Id ticket
    const ticket_id = document.getElementById('id').value;
    // Div comentarios pagina principal  
    const comentariosDiv = document.getElementById('comentariosDiv');
    // Alerta comentarios
    const mensajeComentarios = document.getElementById('comentario-mensaje');

    //-----------------------------------------------COMENTARIOS NORMALES ----------------------------------------------------------------------------
    const nuevoComentarioBtn = document.getElementById('nuevoComentarioBtn');
    nuevoComentarioBtn.classList.add('d-none');


    nuevoComentarioBtn.addEventListener('click', function() {
        nuevoComentarioBtn.disabled = true;
        const comentarioIndividualDiv = document.createElement('div');
        comentarioIndividualDiv.classList.add('m-4');

        // Crear textarea donde ira el comentario
        const newTextarea = document.createElement('textarea');
        newTextarea.classList.add('form-control', 'bg-dark', 'bg-opacity-10');
        newTextarea.placeholder = "Escribe un comentario...";

        // Crear botón para cancelar el textarea
        const botonCancelarComentario = document.createElement('button');
        botonCancelarComentario.classList.add('btn', 'btn-danger', 'me-2', 'btn-sm', 'mt-1');
        botonCancelarComentario.textContent = "Cancelar Comentario";
        botonCancelarComentario.setAttribute('type', 'button');

        // Crear botón para editar el textarea
        const botonEditComentario = document.createElement('button');
        botonEditComentario.classList.add('btn', 'btn-primary', 'btn-sm', 'mt-1');
        botonEditComentario.textContent = "Editar Comentario";
        botonEditComentario.setAttribute('type', 'button');

        // Crear botón para editar el textarea
        const botonConfirmEdit = document.createElement('button');
        botonConfirmEdit.classList.add('btn', 'btn-primary', 'btn-sm', 'mt-1', 'd-none');
        botonConfirmEdit.textContent = "Confirmar cambios";
        botonConfirmEdit.setAttribute('type', 'button');

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
        comentarioIndividualDiv.appendChild(botonCancelarComentario);
        comentarioIndividualDiv.appendChild(botonSaveComentario);

        console.log(comentariosDiv.firstChild); // Verifica qué tiene firstChild

        // Agregar el nuevo comentario arriba de los existentes
        if (comentariosDiv.firstChild) {
            comentariosDiv.insertBefore(comentarioIndividualDiv, comentariosDiv.firstChild); // Insertar antes del primer hijo
        } else {
            comentariosDiv.appendChild(comentarioIndividualDiv); // Si no hay comentarios, agregar normalmente
        }

        // Agregar funcionalidad al botón de cancelar
        botonCancelarComentario.addEventListener('click', function() {
            comentariosDiv.removeChild(comentarioIndividualDiv); // Eliminar el div completo
            nuevoComentarioBtn.disabled = false;
        });

        // Agregar funcionalidad al botón de guardar
        botonSaveComentario.addEventListener('click', function() { 
            if (newTextarea.value.trim() === '') {
                mensajeComentarios.textContent = 'El comentario no puede estar vacío';
                mensajeComentarios.classList.add('text-black');
                mensajeComentarios.classList.add('bg-opacity-25');
                mensajeComentarios.classList.remove('bg-success');
                mensajeComentarios.classList.add('bg-danger');

                // Ocultar después de 2 segundos (2000 ms)
                setTimeout(() => {
                    mensajeComentarios.classList.add('d-none');
                }, 2000);
            }
            // Crear FormData con los datos que se necesitan enviar
            const formData = new FormData();
            formData.append('id', ticket_id); // ID Ticket
            formData.append('fecha_hora', dateTimeFormatted); // Fecha y hora actual en formato ISO
            formData.append('tipo', 'normal'); // Tipo
            formData.append('contenido', newTextarea.value); // El comentario

            fetch('/store_ticket_comentarios', {
                method: 'POST',
                body: formData
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    mensajeComentarios.textContent = 'Comentario guardado correctamente.';
                    mensajeComentarios.classList.add('text-black');
                    mensajeComentarios.classList.add('bg-opacity-25');
                    mensajeComentarios.classList.remove('bg-danger');
                    mensajeComentarios.classList.add('bg-success');
                    nuevoComentarioBtn.disabled = false;

                    cargarComentarios(ticket_id);
                } else {
                    mensajeComentarios.textContent = data.error;
                    mensajeComentarios.classList.add('text-black');
                    mensajeComentarios.classList.add('bg-opacity-25');
                    mensajeComentarios.classList.remove('bg-success');
                    mensajeComentarios.classList.add('bg-danger');
                }

                mensajeComentarios.classList.remove('d-none');

                // Ocultar después de 2 segundos (2000 ms)
                setTimeout(() => {
                    mensajeComentarios.classList.add('d-none');
                }, 2000);
            })
            .catch(error => {
                console.error('Error en la petición:', error);
            });

            newTextarea.classList.remove('bg-dark', 'bg-opacity-10');
        });
    });

    //-----------------------------------------------COMENTARIOS INTERNOS ----------------------------------------------------------------------------
    const nuevoComentarioInternoBtn = document.getElementById('nuevoComentarioInternoBtn');

    nuevoComentarioInternoBtn.addEventListener('click', function() {
        nuevoComentarioInternoBtn.disabled = true;
        const comentarioIndividualDiv = document.createElement('div');
        comentarioIndividualDiv.classList.add('m-4');

        // Crear textarea donde ira el comentario
        const newTextarea = document.createElement('textarea');
        newTextarea.classList.add('form-control', 'bg-primary', 'bg-opacity-10');
        newTextarea.placeholder = "Escribe un comentario...";

        // Crear botón para cancelar el textarea
        const botonCancelarComentario = document.createElement('button');
        botonCancelarComentario.classList.add('btn', 'btn-danger', 'me-2', 'btn-sm', 'mt-1');
        botonCancelarComentario.textContent = "Cancelar Comentario";
        botonCancelarComentario.setAttribute('type', 'button');

        // Crear botón para editar el textarea
        const botonEditComentario = document.createElement('button');
        botonEditComentario.classList.add('btn', 'btn-primary', 'btn-sm', 'mt-1');
        botonEditComentario.textContent = "Editar Comentario";
        botonEditComentario.setAttribute('type', 'button');

        // Crear botón para editar el textarea
        const botonConfirmEdit = document.createElement('button');
        botonConfirmEdit.classList.add('btn', 'btn-primary', 'btn-sm', 'mt-1', 'd-none');
        botonConfirmEdit.textContent = "Confirmar cambios";
        botonConfirmEdit.setAttribute('type', 'button');

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
        comentarioIndividualDiv.appendChild(botonCancelarComentario);
        comentarioIndividualDiv.appendChild(botonSaveComentario);

        console.log(comentariosDiv.firstChild); // Verifica qué tiene firstChild

        // Agregar el nuevo comentario arriba de los existentes
        if (comentariosDiv.firstChild) {
            comentariosDiv.insertBefore(comentarioIndividualDiv, comentariosDiv.firstChild); // Insertar antes del primer hijo
        } else {
            comentariosDiv.appendChild(comentarioIndividualDiv); // Si no hay comentarios, agregar normalmente
        }

        // Agregar funcionalidad al botón de cancelar
        botonCancelarComentario.addEventListener('click', function() {
            comentariosDiv.removeChild(comentarioIndividualDiv); // Eliminar el div completo
            nuevoComentarioInternoBtn.disabled = false;
        });

        // Agregar funcionalidad al botón de guardar
        botonSaveComentario.addEventListener('click', function() { 
            if (newTextarea.value.trim() === '') {
                mensajeComentarios.textContent = 'El comentario no puede estar vacío';
                mensajeComentarios.classList.add('text-black');
                mensajeComentarios.classList.add('bg-opacity-25');
                mensajeComentarios.classList.remove('bg-success');
                mensajeComentarios.classList.add('bg-danger');

                // Ocultar después de 2 segundos (2000 ms)
                setTimeout(() => {
                    mensajeComentarios.classList.add('d-none');
                }, 2000);
            }
            // Crear FormData con los datos que se necesitan enviar
            const formData = new FormData();
            formData.append('id', ticket_id); // ID Ticket
            formData.append('fecha_hora', dateTimeFormatted); // Fecha y hora actual en formato ISO
            formData.append('tipo', 'interno'); // Tipo
            formData.append('contenido', newTextarea.value); // El comentario

            fetch('/store_ticket_comentarios', {
                method: 'POST',
                body: formData
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    mensajeComentarios.textContent = 'Comentario guardado correctamente.';
                    mensajeComentarios.classList.add('text-black');
                    mensajeComentarios.classList.add('bg-opacity-25');
                    mensajeComentarios.classList.remove('bg-danger');
                    mensajeComentarios.classList.add('bg-success');
                    nuevoComentarioInternoBtn.disabled = false;

                    cargarComentarios(ticket_id);
                } else {
                    mensajeComentarios.textContent = data.error;
                    mensajeComentarios.classList.add('text-black');
                    mensajeComentarios.classList.add('bg-opacity-25');
                    mensajeComentarios.classList.remove('bg-success');
                    mensajeComentarios.classList.add('bg-danger');
                }

                mensajeComentarios.classList.remove('d-none');

                // Ocultar después de 2 segundos (2000 ms)
                setTimeout(() => {
                    mensajeComentarios.classList.add('d-none');
                }, 2000);
            })
            .catch(error => {
                console.error('Error en la petición:', error);
            });

            newTextarea.classList.remove('bg-opacity-10');
            newTextarea.classList.add('bg-opacity-50');
        });
    });








    
    function cargarComentarios(ticket_id) {
        fetch('/get_comentarios', {
            method: 'POST',
            headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `id=${encodeURIComponent(ticket_id)}`
        })
        .then(response => response.json())
        .then(data => {
            comentariosDiv.innerHTML = ''; // Limpiar contenido anterior

            if (data.success && data.comentarios.length > 0) {
            data.comentarios.forEach(comentario => {
                
                const comentarioIndividualDiv = document.createElement('div');
                comentarioIndividualDiv.classList.add('m-4');
    
                const newTextarea = document.createElement('textarea');
                newTextarea.classList.add('form-control');
                newTextarea.value = comentario.contenido;
                newTextarea.readOnly = true;
    
                const botonDeleteComentario = document.createElement('button');
                botonDeleteComentario.classList.add('btn', 'btn-danger', 'me-2', 'btn-sm', 'mt-1');
                botonDeleteComentario.textContent = "Eliminar Comentario";
                botonDeleteComentario.setAttribute('type', 'button');
    
                const botonEditComentario = document.createElement('button');
                botonEditComentario.classList.add('btn', 'btn-primary', 'btn-sm', 'mt-1');
                botonEditComentario.textContent = "Editar Comentario";
                botonEditComentario.setAttribute('type', 'button');

                const botonConfirmEdit = document.createElement('button');
                botonConfirmEdit.classList.add('btn', 'btn-primary', 'btn-sm', 'mt-1', 'd-none');
                botonConfirmEdit.textContent = "Confirmar cambios";
                botonConfirmEdit.setAttribute('type', 'button');
    
                const fechaHora = document.createElement('span');
                fechaHora.textContent = `Fecha y Hora: ${comentario.fecha_hora}`;
                fechaHora.classList.add('block', 'mt-2', 'text-sm', 'text-gray-500');
    
                comentarioIndividualDiv.appendChild(fechaHora);
                comentarioIndividualDiv.appendChild(newTextarea);
                comentarioIndividualDiv.appendChild(botonDeleteComentario);
                comentarioIndividualDiv.appendChild(botonEditComentario);
                comentarioIndividualDiv.appendChild(botonConfirmEdit);
    
                comentariosDiv.appendChild(comentarioIndividualDiv);

                if(comentario.tipo === 'interno'){
                    newTextarea.classList.add('bg-primary', 'bg-opacity-25');
                }

                // Agregar funcionalidad al botón de eliminar
                botonDeleteComentario.addEventListener('click', function() {
                    comentariosDiv.removeChild(comentarioIndividualDiv); // Eliminar el div completo

                    // Crear FormData con los datos que se necesitan enviar
                    const formData = new FormData();
                    formData.append('id', comentario.id); // ID Ticket

                    fetch('/delete_comentarios', {
                        method: 'POST',
                        body: formData
                    }).then(response => response.json())
                    .then(data => {        
                        if (data.success) {
                            mensajeComentarios.textContent = 'Comentario eliminado correctamente.';
                            mensajeComentarios.classList.add('text-black');
                            mensajeComentarios.classList.add('bg-opacity-25');
                            mensajeComentarios.classList.remove('bg-success');
                            mensajeComentarios.classList.add('bg-danger');
                        } else {
                            mensajeComentarios.textContent = 'Error al guardar comentario: ' + data.error;
                            mensajeComentarios.classList.add('text-black');
                            mensajeComentarios.classList.add('bg-opacity-100');
                            mensajeComentarios.classList.remove('bg-success');
                            mensajeComentarios.classList.add('bg-danger');
                        }
        
                        mensajeComentarios.classList.remove('d-none');
        
                        // Ocultar después de 2 segundos (2000 ms)
                        setTimeout(() => {
                            mensajeComentarios.classList.add('d-none');
                        }, 2000);
                    })
                    .catch(error => {
                        console.error('Error en la petición:', error);
                    });
                });

                // Agregar funcionalidad al botón de editar
                botonEditComentario.addEventListener('click', function() {
                    nuevoComentarioBtn.disabled = true;
                    nuevoComentarioInternoBtn.disabled = true;

                    newTextarea.readOnly = false;
                    newTextarea.classList.remove('bg-opacity-25');
                    newTextarea.classList.add('form-control', 'bg-dark', 'bg-opacity-10');

                    botonEditComentario.classList.add('d-none');
                    botonConfirmEdit.classList.remove('d-none');

                    botonConfirmEdit.addEventListener('click', function() {
                        // Crear FormData con los datos que se necesitan enviar
                        const formData = new FormData();
                        formData.append('contenido', newTextarea.value); // ID Ticket
                        formData.append('id', comentario.id); // ID Ticket

                        fetch('/update_comentarios', {
                            method: 'POST',
                            body: formData
                        }).then(response => response.json())
                        .then(data => {        
                            if (data.success) {
                                mensajeComentarios.textContent = 'Comentario editado correctamente.';
                                mensajeComentarios.classList.add('text-black');
                                mensajeComentarios.classList.add('bg-opacity-25');
                                mensajeComentarios.classList.remove('bg-danger');
                                mensajeComentarios.classList.add('bg-success');
                            } else {
                                mensajeComentarios.textContent = 'Error al editar comentario: ' + data.error;
                                mensajeComentarios.classList.add('text-black');
                                mensajeComentarios.classList.add('bg-opacity-100');
                                mensajeComentarios.classList.remove('bg-success');
                                mensajeComentarios.classList.add('bg-danger');
                            }
            
                            mensajeComentarios.classList.remove('d-none');
            
                            // Ocultar después de 2 segundos (2000 ms)
                            setTimeout(() => {
                                mensajeComentarios.classList.add('d-none');
                            }, 2000);
                        })
                        .catch(error => {
                            console.error('Error en la petición:', error);
                        });

                        newTextarea.readOnly = true;
                        newTextarea.classList.remove('bg-dark', 'bg-opacity-10');
                        newTextarea.classList.add('bg-opacity-25');
                        
                        botonEditComentario.classList.remove('d-none');
                        botonConfirmEdit.classList.add('d-none');

                        nuevoComentarioBtn.disabled = false;
                        nuevoComentarioInternoBtn.disabled = false;
                    });
                });
            });
            } else {
            comentariosDiv.innerHTML = '';
            }
        })
        .catch(error => {
            console.error('Error al cargar los comentarios:', error);
        });
    }

    cargarComentarios(ticket_id);
});
  