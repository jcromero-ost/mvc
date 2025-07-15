document.addEventListener('DOMContentLoaded', function () {
    //Guardamos en variables el contenedor donde vamos a mostrar los tickets
    const lista_vacaciones = document.getElementById('lista_vacaciones');

    //Definimos las variables de los modals
    const modalRevisarSolicitud = new bootstrap.Modal(document.getElementById('modalRevisarSolicitud'));
    const modalRechazarSolicitud = new bootstrap.Modal(document.getElementById('modalRechazarSolicitud'));

    //Guardamos la bola roja de las notificaciones en una variable
    const bola_notificaciones_vacaciones = document.getElementById('bola_notificaciones_vacaciones');

    //Creamos una funcion para que carguen las reservas
    function cargar_vacaciones(){
        //Limpiamos el contenedor antes de cargar los datos nuevos
        lista_vacaciones.innerHTML = '';

        //Enviamos los datos al servidor
        fetch('/vacaciones/estado', {
            method: 'POST',
            body: new URLSearchParams({ estado: 'pendiente' }) // Envías el estado que quieres filtrar
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.data.length === 0) {
                    bola_notificaciones_vacaciones.classList.add('d-none');
                    lista_vacaciones.innerHTML = '<li class="dropdown-item text-center">No hay vacaciones sin revisar</li>';
                } else {
                    bola_notificaciones_vacaciones.classList.remove('d-none');
                    lista_vacaciones.innerHTML = ''; // limpio la lista

                    data.data.forEach((vacacion) => {
                        //Creamos un contenedor
                        const div_li = document.createElement('div');
                        div_li.classList.add('d-flex', 'mb-2', 'me-2');

                        //Creamos una lista
                        const li = document.createElement('li');
                        //Creamos un boton para revisar la solicitud
                        const button = document.createElement('button');
                        button.classList.add('btn', 'btn-primary', 'btn-sm');
                        button.textContent = 'Revisar';

                        //Creamos las variables del modal
                        const fecha_inicio_modal = document.getElementById('fecha_inicio_modal');
                        const fecha_fin_modal = document.getElementById('fecha_fin_modal');
                        const motivo_modal = document.getElementById('motivo_modal');

                        //Varibales de los botones
                        const botonAceptarSolicitud = document.getElementById('botonAceptarSolicitud');
                        const botonRechazarSolicitud = document.getElementById('botonRechazarSolicitud');

                        button.addEventListener('click', function(){
                            modalRevisarSolicitud.show();

                            fecha_inicio_modal.value = vacacion.fecha_inicio;
                            fecha_fin_modal.value = vacacion.fecha_fin;
                            motivo_modal.value = vacacion.motivo;
                        });

                        botonAceptarSolicitud.addEventListener('click', function() {
                            const idSolicitud = vacacion.id;
                            const estado = 'aprobado';
                            const rechazo_motivo = null;

                            document.getElementById('form-id').value = idSolicitud;
                            document.getElementById('form-estado').value = estado;
                            document.getElementById('form-rechazo-motivo').value = rechazo_motivo;

                        });
            
                        //Creamos las variables del modal
                        const motivo_modal_rechazar = document.getElementById('motivo_modal_rechazar');

                        botonRechazarSolicitud.addEventListener('click', function() {
                            modalRechazarSolicitud.show();

                            document.getElementById('form-id-rechazar').value = vacacion.id;
                        });

                        // Cuando el usuario hace clic en "Confirmar rechazo"
                        const botonConfirmarRechazo = document.getElementById('botonConfirmarRechazo');

                        botonConfirmarRechazo.addEventListener('click', function () {
                            const motivo = motivo_modal_rechazar.value.trim();

                            document.getElementById('form-estado-rechazar').value = 'rechazado';
                            document.getElementById('form-rechazo-motivo-rechazar').value = motivo;

                        });

                        //Agregamos elementos al contenedor
                        div_li.appendChild(li);
                        div_li.appendChild(button);

                        li.classList.add('dropdown-item');
                        li.textContent = `Solicitud de ${vacacion.usuario_nombre} - ${vacacion.fecha_creacion}`;
                        lista_vacaciones.appendChild(div_li);
                    });
                }
            }
        })
        .catch(error => {
            console.error('ERROR', error);
        });
    }

    cargar_vacaciones();
});