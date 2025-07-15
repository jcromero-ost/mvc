document.addEventListener('DOMContentLoaded', function () {
    const inputRegistrosPerPage = document.getElementById('cantidad');
    const table = document.getElementById('tabla_clientes');
    const tbody = table.querySelector('tbody');
    const paginacionDiv = document.getElementById('paginacion');
    const botonFiltrar = document.getElementById('btn-filtrar');

    let rows = Array.from(tbody.querySelectorAll('tr'));
    let filteredRows = [...rows];

    let currentPage = 1;
    let rowsPerPage = parseInt(inputRegistrosPerPage.value);

    function getTotalPages() {
        return Math.ceil(filteredRows.length / rowsPerPage);
    }

    function mostrarPagina(pagina) {
        currentPage = pagina;
        tbody.innerHTML = '';

        const inicio = (pagina - 1) * rowsPerPage;
        const fin = inicio + rowsPerPage;

        filteredRows.slice(inicio, fin).forEach(row => tbody.appendChild(row));

        renderPaginacion();
    }

    function renderPaginacion() {
        const totalPages = getTotalPages();
        paginacionDiv.innerHTML = '';

        const nav = document.createElement('nav');
        const ul = document.createElement('ul');
        ul.className = 'pagination justify-content-center';

        const liAnterior = document.createElement('li');
        liAnterior.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
        liAnterior.innerHTML = `<a class="page-link" href="#">← Anterior</a>`;
        liAnterior.addEventListener('click', (e) => {
            e.preventDefault();
            if (currentPage > 1) mostrarPagina(currentPage - 1);
        });
        ul.appendChild(liAnterior);

        const maxVisible = 5;
        let start = Math.max(1, currentPage - Math.floor(maxVisible / 2));
        let end = start + maxVisible - 1;
        if (end > totalPages) {
            end = totalPages;
            start = Math.max(1, end - maxVisible + 1);
        }

        if (start > 1) {
            ul.appendChild(crearElementoPagina(1));
            if (start > 2) ul.appendChild(crearElementoEllipsis());
        }

        for (let i = start; i <= end; i++) {
            ul.appendChild(crearElementoPagina(i));
        }

        if (end < totalPages) {
            if (end < totalPages - 1) ul.appendChild(crearElementoEllipsis());
            ul.appendChild(crearElementoPagina(totalPages));
        }

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

    inputRegistrosPerPage.addEventListener('change', function () {
        rowsPerPage = parseInt(this.value);
        currentPage = 1;
        mostrarPagina(currentPage);
    });

    function filtrar() {
        const nombre = document.getElementById("filtrar_nombre").value.toLowerCase();
        const id = document.getElementById("filtrar_id").value.toLowerCase();
        const telefono = document.getElementById("filtrar_telefono").value.toLowerCase();
        const dni = document.getElementById("filtrar_dni").value.toLowerCase();

        filteredRows = rows.filter(fila => {
            const tdNombre = fila.children[0].textContent.toLowerCase();
            const tdId = fila.children[1].textContent.toLowerCase();
            const tdTelefono = fila.children[2].textContent.toLowerCase();
            const tdDni = fila.children[3].textContent.toLowerCase();

            const coincideId = id === "" || tdId.includes(id);

            return tdNombre.includes(nombre) && coincideId && tdTelefono.includes(telefono) && tdDni.includes(dni);
        });

        currentPage = 1;
        mostrarPagina(currentPage);
    }

    botonFiltrar.addEventListener("click", filtrar);

    mostrarPagina(currentPage);
});
