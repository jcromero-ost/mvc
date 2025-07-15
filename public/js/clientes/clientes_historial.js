document.addEventListener('DOMContentLoaded', function () {

    const tablaTicketsBody = document.querySelector('#tabla_tickets tbody');
    const tablaTicketsHead = document.querySelector('#tabla_tickets thead');
    const inputCliente = document.getElementById('cliente_historial');
    const modalDescripcionCompleta = new bootstrap.Modal(document.getElementById('modalDescripcionCompleta'));
    const descripcionCompletaTexto = document.getElementById('descripcionCompleta_texto');

    document.addEventListener('click', async function (e) {
        const btnSeleccionar = e.target.closest('.btn-seleccionar');

        if (btnSeleccionar) {
            const cliente = JSON.parse(btnSeleccionar.getAttribute('data-cliente'));
            const clienteId = cliente.CCODCL;
            inputCliente.value = cliente.CNOM;

            try {
                const response = await fetch('/obtener_tickets_por_cliente', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `cliente_id=${encodeURIComponent(clienteId)}`
                });

                const data = await response.json();
                renderTickets(Array.isArray(data) ? data : (data === false ? [] : [data]));
            } catch (error) {
                console.error('Error al obtener tickets:', error);
            }
        }

        // Mostrar descripción completa en modal
        if (e.target.classList.contains('btn-descripcion-completa')) {
            e.preventDefault();
            descripcionCompletaTexto.innerHTML = e.target.getAttribute('data-descripcion');
            modalDescripcionCompleta.show();
        }
    });

    function renderTickets(tickets) {
        tablaTicketsBody.innerHTML = '';
        tablaTicketsHead.classList.remove('d-none');

        if (!tickets.length) {
            const row = document.createElement('tr');
            const columnas = tablaTicketsHead.querySelectorAll('th').length;
            row.innerHTML = `
                <td colspan="${columnas}" class="text-center text-muted">
                    No hay tickets para este cliente
                </td>
            `;
            tablaTicketsBody.appendChild(row);
            scrollTop();
            return;
        }

        tickets.forEach(ticket => {
            const { id, medio_nombre, tecnico_nombre, fecha_inicio, descripcion, estado } = ticket;

            const estadoMap = {
                pendiente: { texto: 'Pendiente', color: 'danger' },
                en_revision: { texto: 'En Revisión', color: 'warning' },
                finalizado: { texto: 'Finalizado', color: 'success' }
            };

            const estadoInfo = estadoMap[estado] || { texto: 'Desconocido', color: 'secondary' };

            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${id}</td>
                <td>${medio_nombre}</td>
                <td>${tecnico_nombre}</td>
                <td>${fecha_inicio}</td>
                <td>
                    ${mostrarResumenDescripcion(descripcion, 45)}
                    <a href="#" class="btn btn-primary btn-extra-small btn-descripcion-completa"
                        data-id="${id}"
                        data-descripcion="${encodeHTML(descripcion)}"
                        data-bs-toggle="modal"
                        data-bs-target="#modalDescripcionCompleta">...ver más</a>
                </td>
                <td><span class="badge bg-${estadoInfo.color}">${estadoInfo.texto}</span></td>
                <td class="text-center">
                    <a href="/editar_ticket?id=${id}">
                        <button type="button" class="btn btn-sm btn-primary me-1">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                    </a>
                </td>
            `;
            tablaTicketsBody.appendChild(row);
        });

        scrollTop();
    }

    function mostrarResumenDescripcion(texto, limitePalabras) {
        return encodeHTML(texto.split(' ').slice(0, limitePalabras).join(' '));
    }

    function encodeHTML(str) {
        return str
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function scrollTop() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
});
