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

    let ordenAscendenteTiempo = true; // Estado inicial: ascendente

    // Función para convertir "xdd hh mm" a segundos
    function tiempoAbiertoASegundos(tiempoStr) {
        // Ejemplo: "2d 04h 15m"
        const regex = /(\d+)d\s+(\d+)h\s+(\d+)m/;
        const match = tiempoStr.match(regex);
        if (!match) return 0;

        const dias = parseInt(match[1], 10);
        const horas = parseInt(match[2], 10);
        const minutos = parseInt(match[3], 10);

        return dias * 86400 + horas * 3600 + minutos * 60;
    }


    document.getElementById('th-tiempo-abierto').addEventListener('click', () => {
        filteredRows.sort((a, b) => {
            const tiempoA = a.children[6].textContent.trim(); // columna "Tiempo Abierto"
            const tiempoB = b.children[6].textContent.trim();

            const segA = tiempoAbiertoASegundos(tiempoA);
            const segB = tiempoAbiertoASegundos(tiempoB);

            if (ordenAscendenteTiempo) {
                return segA - segB;
            } else {
                return segB - segA;
            }
        });

        ordenAscendenteTiempo = !ordenAscendenteTiempo; // Alternar orden
        mostrarPagina(1);
    });

    const radioDept = document.getElementById('filtro_departamento');
    const radioTec = document.getElementById('filtro_tecnico');
    const selectDept = document.getElementById('select_filtro_departamento');
    const selectTec = document.getElementById('select_filtro_tecnico');

    // Mostrar u ocultar los <select> según el radio activo
    function toggleSelects() {
        if (radioDept.checked) {
            selectDept.classList.remove('d-none');
            selectTec.classList.add('d-none');
        } else if (radioTec.checked) {
            selectTec.classList.remove('d-none');
            selectDept.classList.add('d-none');
        } else {
            selectDept.classList.add('d-none');
            selectTec.classList.add('d-none');
        }
    }

    radioDept.addEventListener('change', toggleSelects);
    radioTec.addEventListener('change', toggleSelects);
    toggleSelects(); // Inicializar visibilidad

    // Función para filtrar
    function filtrar() {
        const cliente = document.getElementById("filtrar_cliente").value.toLowerCase().trim();
        const id = document.getElementById("filtrar_id").value.toLowerCase().trim();
        const estado = document.getElementById("filtrar_estado").value.toLowerCase().trim();
        const medio = document.getElementById("filtrar_medio").value.toLowerCase().trim();

        const filtroAsignacion = document.querySelector('input[name="filtro_asignacion"]:checked').value;
        const valorDept = document.getElementById("select_filtro_departamento").value.toLowerCase().trim();
        const valorTec = document.getElementById("select_filtro_tecnico").value.toLowerCase().trim();

        filteredRows = rows.filter(fila => {
            const tdCliente = fila.children[0].textContent.toLowerCase().trim();
            const tdId = fila.children[1].textContent.toLowerCase().trim();
            const tdEstado = fila.children[7].textContent.toLowerCase().trim().replace(/\s+/g, ' ');
            const tdMedio = fila.children[2].textContent.toLowerCase().trim();

            const tdAsignacion = fila.children[3]; // Contiene técnico + departamento
            const spans = tdAsignacion.querySelectorAll('span');

            const valoresDept = [];
            const valoresTec = [];

            spans.forEach(span => {
                const texto = span.textContent.toLowerCase().trim();
                if (span.classList.contains('bg-primario')) {
                    valoresDept.push(texto);
                } else if (span.classList.contains('bg-dark')) {
                    valoresTec.push(texto);
                }
            });

            const coincideCliente = tdCliente.includes(cliente);
            const coincideId = id === "" || tdId.includes(id);
            const coincideEstado = estado === "" || tdEstado.includes(estado);
            const coincideMedio = medio === "" || tdMedio === medio;

            let coincideAsignacion = true;
            if (filtroAsignacion === "departamento") {
                coincideAsignacion = valorDept === "" || valoresDept.some(dept => dept === valorDept);
            } else if (filtroAsignacion === "tecnico") {
                coincideAsignacion = valorTec === "" || valoresTec.some(tec => tec === valorTec);
            } else if (filtroAsignacion === "ain_asignar") {
                coincideAsignacion = tdAsignacion.textContent.toLowerCase().includes("pendiente de asignar");
            }

            return coincideCliente && coincideId && coincideEstado && coincideAsignacion && coincideMedio;
        });

        currentPage = 1;
        mostrarPagina(currentPage);
    }

    // Eventos para todos los filtros
    document.querySelectorAll('input[name="filtro_asignacion"]').forEach(radio => {
        radio.addEventListener('change', () => {
            toggleSelects();
            filtrar();
        });
    });

    document.getElementById("select_filtro_departamento").addEventListener("change", filtrar);
    document.getElementById("select_filtro_tecnico").addEventListener("change", filtrar);
    document.getElementById("filtrar_cliente").addEventListener("input", filtrar);
    document.getElementById("filtrar_id").addEventListener("input", filtrar);
    document.getElementById("filtrar_estado").addEventListener("change", filtrar);
    document.getElementById("filtrar_medio").addEventListener("change", filtrar);


    // Inicializar
    mostrarPagina(currentPage);

});
