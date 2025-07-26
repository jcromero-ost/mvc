document.addEventListener('DOMContentLoaded', () => {
    const modalContent = document.getElementById('cliente-info-content');
    const modalElement = document.getElementById('modalClienteInfo');
    const clienteModal = new bootstrap.Modal(modalElement);

    // Delegación por si hay múltiples botones con clase 'btn-ver-cliente'
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('btn-ver-cliente')) {
            const clienteId = e.target.getAttribute('data-cliente-id');

            if (!clienteId) {
                modalContent.innerHTML = '<p class="text-danger">ID de cliente no disponible.</p>';
                clienteModal.show();
                return;
            }

            modalContent.innerHTML = '<p class="text-center text-muted">Cargando información del cliente...</p>';

            fetch(`/xgest/cliente_info_modal?id=${clienteId}`)
                .then(response => response.text())
                .then(html => {
                    modalContent.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error al cargar información del cliente:', error);
                    modalContent.innerHTML = '<p class="text-danger">Error al cargar la información.</p>';
                });

            clienteModal.show();
        }
    });
});
