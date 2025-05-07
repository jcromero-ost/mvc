document.addEventListener("DOMContentLoaded", function () {
    const modalEliminarTicket = new bootstrap.Modal(document.getElementById('modalEliminarTicket'));

    const delete_id_ticket = document.getElementById('delete_id_ticket');
    const delete_id = document.getElementById('delete_id');

    // Recorre cada botón y agrega el event listener
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            
            delete_id.value = id;
            delete_id_ticket.innerHTML = id;

            // Mostrar el modal
            modalEliminarTicket.show();
        });
    });
});
