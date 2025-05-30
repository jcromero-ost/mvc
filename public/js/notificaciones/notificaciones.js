document.addEventListener('DOMContentLoaded', function () {
    //Guardamos en variables el contenedor donde vamos a mostrar los tickets
    const lista_vacaciones = document.getElementById('lista_vacaciones');

    //Guardamos la bola roja de las notificaciones en una variable
    const bola_notificaciones = document.getElementById('bola_notificaciones');

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
            let motivo = data.motivo;
            if (data.success) {
                if (data.data.length === 0) {
                    bola_notificaciones.classList.add('d-none');
                    lista_vacaciones.innerHTML = '<li class="dropdown-item text-center">No hay tickets sin asignar</li>';
                } else {
                    bola_notificaciones.classList.remove('d-none');
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
                        const modalRevisarSolicitud = new bootstrap.Modal(document.getElementById('modalRevisarSolicitud'));
                        const fecha_inicio_modal = document.getElementById('fecha_inicio_modal');
                        const fecha_fin_modal = document.getElementById('fecha_fin_modal');
                        const motivo_modal = document.getElementById('motivo_modal');

                        button.addEventListener('click', function(){
                            modalRevisarSolicitud.show();

                            fecha_fin_modal.value = data.fecha_fin;
                            console.log(motivo);
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