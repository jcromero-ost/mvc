document.addEventListener('DOMContentLoaded', function () {
    const rowsPerPage = 12;
    const table = document.getElementById('tabla_clientes');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    const paginacionDiv = document.getElementById('paginacion');

    let currentPage = 1;
    const totalPages = Math.ceil(rows.length / rowsPerPage);

    function mostrarPagina(pagina) {
        currentPage = pagina;
        tbody.innerHTML = '';

        const inicio = (pagina - 1) * rowsPerPage;
        const fin = inicio + rowsPerPage;

        rows.slice(inicio, fin).forEach(row => tbody.appendChild(row));

        renderPaginacion();
    }

    function renderPaginacion() {
        paginacionDiv.innerHTML = '';

        const nav = document.createElement('nav');
        const ul = document.createElement('ul');
        ul.className = 'pagination justify-content-center';

        // Botón Anterior
        const liAnterior = document.createElement('li');
        liAnterior.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
        liAnterior.innerHTML = `<a class="page-link" href="#">← Anterior</a>`;
        liAnterior.addEventListener('click', (e) => {
            e.preventDefault();
            if (currentPage > 1) mostrarPagina(currentPage - 1);
        });
        ul.appendChild(liAnterior);

        // Rango de páginas
        const maxVisible = 5;
        let start = Math.max(1, currentPage - Math.floor(maxVisible / 2));
        let end = start + maxVisible - 1;
        if (end > totalPages) {
            end = totalPages;
            start = Math.max(1, end - maxVisible + 1);
        }

        if (start > 1) {
            ul.appendChild(crearElementoPagina(1));
            if (start > 2) {
                ul.appendChild(crearElementoEllipsis());
            }
        }

        for (let i = start; i <= end; i++) {
            ul.appendChild(crearElementoPagina(i));
        }

        if (end < totalPages) {
            if (end < totalPages - 1) {
                ul.appendChild(crearElementoEllipsis());
            }
            ul.appendChild(crearElementoPagina(totalPages));
        }

        // Botón Siguiente
        const liSiguiente = document.createElement('li');
        liSiguiente.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
        liSiguiente.innerHTML = `<a class="page-link" href="#">Siguiente →</a>`;
        liSiguiente.addEventListener('click', (e) => {
            e.preventDefault();
            if (currentPage < totalPages) mostrarPagina(currentPage + 1);
        });
        ul.appendChild(liSiguiente);

        nav.appendChild(ul);
        paginacionDiv.appendChild(nav);
    }

    function crearElementoPagina(num) {
        const li = document.createElement('li');
        li.className = `page-item ${num === currentPage ? 'active' : ''}`;
        li.innerHTML = `<a class="page-link" href="#">${num}</a>`;
        li.addEventListener('click', (e) => {
            e.preventDefault();
            mostrarPagina(num);
        });
        return li;
    }

    function crearElementoEllipsis() {
        const li = document.createElement('li');
        li.className = 'page-item disabled';
        li.innerHTML = `<span class="page-link">...</span>`;
        return li;
    }

    // Inicializar
    mostrarPagina(1);
});
