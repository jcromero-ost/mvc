document.addEventListener('DOMContentLoaded', function () {
    const inputRegistrosPerPage = document.getElementById('cantidad');
    const table = document.getElementById('tabla_tickets');
    const tbody = table.querySelector('tbody');
    const paginacionDiv = document.getElementById('paginacion');
    let rows = Array.from(tbody.querySelectorAll('tr')); // Filas originales
    let filteredRows = [...rows]; // Filas después de aplicar filtro

    let currentPage = 1;
    let rowsPerPage = parseInt(inputRegistrosPerPage.value);

    // Función para obtener el número total de páginas basado en filas filtradas
    function getTotalPages() {
        return Math.ceil(filteredRows.length / rowsPerPage);
    }

    // Mostrar las filas de la página actual
    function mostrarPagina(pagina) {
        currentPage = pagina;
        tbody.innerHTML = ''; // Limpiar contenido actual

        const inicio = (pagina - 1) * rowsPerPage;
        const fin = inicio + rowsPerPage;

        filteredRows.slice(inicio, fin).forEach(row => tbody.appendChild(row));

        renderPaginacion();
    }

    // Función para renderizar la paginación
    function renderPaginacion() {
        const totalPages = getTotalPages();
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
        const totalPagesValue = getTotalPages();
        if (end > totalPagesValue) {
            end = totalPagesValue;
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

        if (end < totalPagesValue) {
            if (end < totalPagesValue - 1) {
                ul.appendChild(crearElementoEllipsis());
            }
            ul.appendChild(crearElementoPagina(totalPagesValue));
        }

        // Botón Siguiente
        const liSiguiente = document.createElement('li');
        liSiguiente.className = `page-item ${currentPage === totalPagesValue ? 'disabled' : ''}`;
        liSiguiente.innerHTML = `<a class="page-link" href="#">Siguiente →</a>`;
        liSiguiente.addEventListener('click', (e) => {
            e.preventDefault();
            if (currentPage < totalPagesValue) mostrarPagina(currentPage + 1);
        });
        ul.appendChild(liSiguiente);

        nav.appendChild(ul);
        paginacionDiv.appendChild(nav);
    }

    // Crear elemento de página
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

    // Crear elemento de elipsis
    function crearElementoEllipsis() {
        const li = document.createElement('li');
        li.className = 'page-item disabled';
        li.innerHTML = `<span class="page-link">...</span>`;
        return li;
    }

    // Cambiar cantidad por página
    inputRegistrosPerPage.addEventListener('change', function () {
        rowsPerPage = parseInt(this.value);
        currentPage = 1;
        mostrarPagina(currentPage);
    });

    // Función de filtro
    function filtrar() {
        const cliente = document.getElementById("filtrar_cliente").value.toLowerCase();
        const id = document.getElementById("filtrar_id").value.toLowerCase();
        const tecnico = document.getElementById("filtrar_tecnico").value.toLowerCase();
        const fecha_inicio = document.getElementById("filtrar_fecha_inicio").value.toLowerCase();

        // Filtrar filas basadas en los valores
        filteredRows = rows.filter(fila => {
            const tdCliente = fila.children[0].textContent.toLowerCase();
            const tdId = fila.children[1].textContent.toLowerCase();
            const tdTecnico = fila.children[3].textContent.toLowerCase();
            const tdFecha = fila.children[4].textContent.toLowerCase();

            const coincideId = id === "" || tdId === id;  // Si ID está vacío, no se aplica filtro de ID
            const coincideTecnico = tecnico === "" || tdTecnico === tecnico;  // Si técnico está vacío, no se aplica filtro de técnico

            return tdCliente.includes(cliente) && coincideId && coincideTecnico && tdFecha.includes(fecha_inicio);
        });

        currentPage = 1;  // Reiniciar a la primera página después de filtrar
        mostrarPagina(currentPage);  // Mostrar los resultados filtrados
    }

    // Aplicar filtro al hacer clic en el botón
    const botonFiltrar = document.getElementById('btn-filtrar');
    botonFiltrar.addEventListener("click", filtrar);

    // Inicializar la tabla y la paginación
    mostrarPagina(currentPage);
});
