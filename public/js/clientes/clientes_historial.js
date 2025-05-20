document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('click', function (e) {
    if (e.target.closest('.btn-seleccionar')) {
        const cliente = JSON.parse(e.target.closest('.btn-seleccionar').getAttribute('data-cliente'));
        const clienteId = cliente.CCODCL;
        document.getElementById('cliente_historial').value = cliente.CNOM;


        fetch('/obtener_tickets_por_cliente', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `cliente_id=${encodeURIComponent(clienteId)}`
        })
        .then(response => response.json())
        .then(data => {
            console.log('Data recibida:', data);

            const tabla = document.querySelector('#tabla_tickets tbody');
            tabla.innerHTML = ''; // Limpiar tabla

            // Si data no es array, convertirlo en array
            const tickets = Array.isArray(data) ? data : [data];

            if(data === false){
                tabla.innerHTML = 'No hay tickets para este cliente'; // Limpiar tabla

                // Quitar el mensaje después de 2 segundos (2000 ms)
                setTimeout(() => {
                tabla.innerHTML = ''; // Limpiar el contenido
                }, 2000);

                // Hacer scroll arriba de la página
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }else{
                tickets.forEach(ticket => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                    <td>${ticket.cliente_id}</td>
                    <td>${ticket.id}</td>
                    <td>${ticket.medio_id}</td>
                    <td>${ticket.tecnico_id}</td>
                    <td>${ticket.fecha_inicio}</td>
                    <td>${ticket.descripcion}</td>
                    <td><span class="badge bg-secondary">${ticket.estado}</span></td>
                    <td class="text-center">
                        <a href="/editar_ticket?id=${ticket.id}">
                        <button type="button" class="btn btn-sm btn-primary me-1">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        </a>
                    </td>
                    `;
                    tabla.appendChild(row);
                });

                // Hacer scroll arriba de la página
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }

        });

    }
    });
});