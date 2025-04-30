document.addEventListener('click', function (e) {
    if (e.target.closest('.btn-ver-descansos')) {
        const btn = e.target.closest('.btn-ver-descansos');
        const dataEncoded = btn.getAttribute('data-descansos');
        const data = JSON.parse(decodeURIComponent(dataEncoded));

        const contenedor = document.getElementById('contenido-descansos');
        contenedor.innerHTML = '';

        if (data.length === 0) {
            contenedor.innerHTML = '<p>No se registraron descansos.</p>';
        } else {
            const lista = document.createElement('ul');
            lista.className = 'list-group';
            data.forEach(d => {
                const li = document.createElement('li');
                li.className = 'list-group-item';
                li.textContent = `Inicio: ${d.inicio} - Fin: ${d.fin}`;
                lista.appendChild(li);
            });
            contenedor.appendChild(lista);
        }

        const modal = new bootstrap.Modal(document.getElementById('modalDescansos'));
        modal.show();
    }
});
