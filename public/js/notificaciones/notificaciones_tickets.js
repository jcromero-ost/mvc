document.addEventListener('DOMContentLoaded', function () {
   //Guardamos en variables el contenedor donde vamos a mostrar los tickets
    const lista_tickets = document.getElementById('lista_tickets');

    //Creamos una funcion para que carguen los tickets
    function cargar_tickets(){
        //Limpiamos el contenedor antes de cargar los datos nuevos
        lista_tickets.innerHTML = '';

        //Enviamos los datos al servidor
        fetch('/tickets/tecnico', {
            method: 'POST',
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.data.length === 0) {
                    bola_notificaciones.classList.add('d-none');
                    lista_tickets.innerHTML = '<li class="dropdown-item text-center">No tienes tickets asignados</li>';
                } else {
                    bola_notificaciones.classList.remove('d-none');
                    lista_tickets.innerHTML = ''; // limpio la lista

                    data.data.forEach((ticket) => {
                        //Creamos un contenedor
                        const div_li = document.createElement('div');
                        div_li.classList.add('d-flex', 'mb-2', 'me-2');

                        //Creamos una lista
                        const li = document.createElement('li');
                        //Creamos un boton para revisar la solicitud
                        const button2 = document.createElement('button');
                        button2.classList.add('btn', 'btn-primary', 'btn-sm', 'btn-asignar-ticket');
                        button2.textContent = 'Asignar';
                        // Asignar el valor al atributo data-id_ticket
                        button2.setAttribute('data-id_ticket', ticket.id); // Asegúrate de que `ticket.id` exista en tu contexto


                        //Definimos las variables de los modals

                        button2.addEventListener('click', function(){
                            const ticketId = this.getAttribute('data-id_ticket');
                            const modalId = document.getElementById('modalAsignarTicketLabel');
                            modalId.textContent = `Asignar ticket #${ticketId}`;
                            
                            const id_ticket_asignar = document.getElementById('id');

                            id_ticket_asignar.value = ticketId;

                            // Aquí abrirías el modal (depende de la librería que uses)
                            // Por ejemplo con Bootstrap 5:
                            const modalAsignarTicket = new bootstrap.Modal(document.getElementById('modalAsignarTicket'));
                            modalAsignarTicket.show();

                        });


                        //Agregamos elementos al contenedor
                        div_li.appendChild(li);
                        div_li.appendChild(button2);

                        li.classList.add('dropdown-item');
                        li.textContent = `Ticket #${ticket.id} - ${ticket.descripcion.substring(0, 30)}...`;
                        lista_tickets.appendChild(div_li);
                    });
                }
            }
        })
        .catch(error => {
            console.error('ERROR', error);
        });
    }

    cargar_tickets();

  // ================================
  // GESTIÓN DE ASIGNACIÓN (RADIO)
  // ================================
  const radioDept = document.getElementById("asignar_departamento");
  const radioTec = document.getElementById("asignar_tecnico");
  const radioNone = document.getElementById("sin_asignar");

  const selectDept = document.getElementById("select_departamentos");
  const selectTec = document.getElementById("select_tecnicos");

  const actualizarVistaAsignacion = () => {
    if (radioDept.checked) {
      selectDept.classList.remove("d-none");
      selectTec.classList.add("d-none");
    } else if (radioTec.checked) {
      selectDept.classList.add("d-none");
      selectTec.classList.remove("d-none");
    } else {
      selectDept.classList.add("d-none");
      selectTec.classList.add("d-none");
    }
  };

  [radioDept, radioTec, radioNone].forEach(radio => {
    radio.addEventListener("change", actualizarVistaAsignacion);
  });

  actualizarVistaAsignacion(); // Estado inicial
});
