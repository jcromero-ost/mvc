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
                const tabla = document.querySelector('#tabla_tickets tbody');
                const tickets_thead = document.querySelector('#tabla_tickets thead');
                tabla.innerHTML = ''; // Limpiar tabla

                // Convertir a array si no lo es
                const tickets = Array.isArray(data) ? data : (data === false ? [] : [data]);

                if (tickets.length === 0) {
                    // Mostrar mensaje de "sin tickets"
                    const row = document.createElement('tr');
                    const columnas = document.querySelectorAll('#tabla_tickets thead th').length;
                    row.innerHTML = `
                        <td colspan="${columnas}" class="text-center text-muted">
                            No hay tickets para este cliente
                        </td>
                    `;
                    tabla.appendChild(row);
                    tickets_thead.classList.remove('d-none');

                    // Mantener el mensaje sin borrarlo automáticamente
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    // Mostrar filas de tickets
                    tickets.forEach(ticket => {
                        // Comprobaciones del estado del ticket
                        let estado_ticket;
                        let estado_color;

                        if (ticket.estado === 'pendiente') {
                            estado_ticket = 'Pendiente';
                            estado_color = 'danger';
                        } else if (ticket.estado === 'en_revision') {
                            estado_ticket = 'En Revisión';
                            estado_color = 'warning';
                        } else if (ticket.estado === 'finalizado') {
                            estado_ticket = 'Finalizado';
                            estado_color = 'success';
                        }
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${ticket.id}</td>
                            <td>${ticket.medio_nombre}</td>
                            <td>${ticket.tecnico_nombre}</td>
                            <td>${ticket.fecha_inicio}</td>
                            <td>
                            ${mostrarResumenDescripcion(ticket.descripcion, 45)}
                            <a 
                                href="#"
                                class="btn btn-primary btn-extra-small btn-descripcion-completa" 
                                data-id="${ticket.id}" 
                                data-descripcion="${encodeHTML(ticket.descripcion)}"
                                data-bs-toggle="modal" 
                                data-bs-target="#modalDescripcionCompleta">
                                ...ver más
                            </a>
                            </td>
                            <td>
                            <span class="badge bg-${estado_color}">
                                ${estado_ticket}
                            </span>
                            </td>
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

                    tickets_thead.classList.remove('d-none');
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });
        }
    });

    function mostrarResumenDescripcion(texto, limitePalabras) {
        const palabras = texto.split(' ');
        const resumen = palabras.slice(0, limitePalabras).join(' ');
        return encodeHTML(resumen);
    }

    function encodeHTML(str) {
        return str
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    const modalDescripcionCompleta = new bootstrap.Modal(document.getElementById('modalDescripcionCompleta'));
    const descripcionCompleta_texto = document.getElementById('descripcionCompleta_texto');

    // Delegación de eventos para los botones dinámicos
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-descripcion-completa')) {
            e.preventDefault(); // Prevenir navegación si es un <a href="#">
            const descripcion = e.target.getAttribute('data-descripcion');
            descripcionCompleta_texto.innerHTML = descripcion;
            modalDescripcionCompleta.show();
        }
    });
});
