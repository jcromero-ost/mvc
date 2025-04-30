let paginaActual = 1;
let jornadasGlobal = [];

export function setPaginaActual(valor) {
    paginaActual = valor;
}

export function getPaginaActual() {
    return paginaActual;
}

function formatearFechaInput(fecha) {
    if (!fecha) return '';
    const partes = fecha.split('/');
    if (partes.length === 3) {
        return `${partes[2]}-${partes[1].padStart(2, '0')}-${partes[0].padStart(2, '0')}`;
    }
    return fecha;
}


export async function cargarRegistros() {
    const usuario = document.getElementById('usuario').value;
    const fechaDesde = document.getElementById('fecha_desde').value;
    const fechaHasta = document.getElementById('fecha_hasta').value;
    const cantidad = parseInt(document.getElementById('cantidad').value, 10);
    

    const formData = new FormData();
    formData.append('usuario', usuario);
    formData.append('fecha_desde', formatearFechaInput(fechaDesde));
    formData.append('fecha_hasta', formatearFechaInput(fechaHasta));
    formData.append('page', paginaActual);
    formData.append('limit', cantidad);

    try {
        const response = await fetch('/registro-horario/buscar-jornadas', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            const registros = data.data.jornadas;
            const total = data.data.total;

            jornadasGlobal = registros;
            renderizarTabla(jornadasGlobal);
            renderizarPaginacion(paginaActual, total, cantidad);
        } else {
            alert(data.message || 'Error al cargar registros.');
        }
    } catch (error) {
        console.error('Error cargando registros:', error);
        alert('Error en la conexión.');
    }
}

export function renderizarTabla(jornadas) {
    const tabla = document.getElementById('tabla-registros').querySelector('tbody');
    tabla.innerHTML = '';

    if (jornadas.length === 0) {
        tabla.innerHTML = '<tr><td colspan="5" class="text-center">No se encontraron jornadas.</td></tr>';
        return;
    }

    jornadas.forEach(jornada => {
        const esBase64 = jornada.foto && jornada.foto.startsWith('data:image');
        const rutaImagen = esBase64
            ? jornada.foto
            : `/public/images/${jornada.foto || 'default.jpeg'}`;

        const fila = document.createElement('tr');
        fila.innerHTML = `
            <td>
                <img src="${rutaImagen}" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                ${jornada.usuario}
            </td>
            <td class="text-center align-middle">${jornada.fecha}</td>
            <td class="text-center align-middle">${jornada.hora_inicio || '--:--'}</td>
            <td class="text-center align-middle">${jornada.hora_fin || '--:--'}</td>
            <td class="text-center align-middle">
                ${jornada.descansos && jornada.descansos.length > 0 ? `
                    <button class="btn btn-primary btn-sm rounded-circle border btn-ver-descansos px-2 py-1"
                        title="Ver descansos"
                        data-descansos="${encodeURIComponent(JSON.stringify(jornada.descansos))}">
                        <i class="bi bi-eye text-white"></i>
                    </button>` : '-'}
            </td>
        `;
        tabla.appendChild(fila);
    });
}

export function renderizarPaginacion(pagina, total, cantidad) {
    const paginacion = document.getElementById('paginacion');
    paginacion.innerHTML = '';

    const totalPaginas = Math.ceil(total / cantidad);
    const actual = getPaginaActual();

    const crearItem = (texto, paginaDestino, activa = false, deshabilitada = false) => {
        const li = document.createElement('li');
        li.className = `page-item${activa ? ' active' : ''}${deshabilitada ? ' disabled' : ''}`;

        const a = document.createElement('a');
        a.className = 'page-link btn btn-sm btn-primary';
        a.href = '#';
        a.textContent = texto;

        if (!deshabilitada && paginaDestino) {
            a.addEventListener('click', (e) => {
                e.preventDefault();
                setPaginaActual(paginaDestino);
                cargarRegistros();
            });
        }

        li.appendChild(a);
        return li;
    };

    // Anterior
    paginacion.appendChild(crearItem('Anterior', actual - 1, false, actual === 1));

    // Números con truncamiento
    const maxPaginas = 5;
    let start = Math.max(1, actual - 2);
    let end = Math.min(totalPaginas, start + maxPaginas - 1);
    if (end - start < maxPaginas - 1 && start > 1) {
        start = Math.max(1, end - maxPaginas + 1);
    }

    // Botón para ir al inicio si hay más páginas antes
if (start > 1) {
    paginacion.appendChild(crearItem('...', 1, false));
}

// Páginas del rango visible
for (let i = start; i <= end; i++) {
    paginacion.appendChild(crearItem(i, i, i === actual));
}

// Botón para ir al final si hay más páginas después
if (end < totalPaginas) {
    paginacion.appendChild(crearItem('...', totalPaginas, false));
}


    // Siguiente
    paginacion.appendChild(crearItem('Siguiente', actual + 1, false, actual >= totalPaginas));
}
