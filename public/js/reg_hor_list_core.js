const mensaje = document.getElementById('mensaje');

let paginaActual = 1;
let jornadasGlobal = [];

export function setPaginaActual(valor) {
    paginaActual = valor;
}

export function getPaginaActual() {
    return paginaActual;
}

export function formatearFechaInput(fecha) {
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
            <td data-editable class="text-center align-middle">${jornada.fecha}</td>
            <td data-editable class="editable-hora text-center align-middle">${jornada.hora_inicio || '--:--'}</td>
            <td data-editable class="editable-hora text-center align-middle">${jornada.hora_fin || '--:--'}</td>
            <td class="text-center align-middle">
                ${jornada.descansos && jornada.descansos.length > 0 ? `
                    <button class="btn btn-primary btn-sm rounded-circle border btn-ver-descansos px-2 py-1"
                        title="Ver descansos"
                        data-descansos="${encodeURIComponent(JSON.stringify(jornada.descansos))}">
                        <i class="bi bi-eye text-white"></i>
                    </button>` : '-'}
            </td>
        `;
        //Asignar boton de editar
        const tdBoton = document.createElement('td');
        tdBoton.className = 'text-center align-middle';

        const botonEditar = document.createElement('button');
        botonEditar.type = 'button';
        botonEditar.className = 'btn btn-sm btn-primary me-1 btn-editar';
        botonEditar.title = 'Editar';
        botonEditar.innerHTML = '<i class="bi bi-pencil-square"></i>';

        const botonCancelar = document.createElement('button');
        botonCancelar.type = 'button';
        botonCancelar.className = 'btn btn-sm btn-danger me-1 btn-cancelar d-none';
        botonCancelar.title = 'Cncelar';
        botonCancelar.innerHTML = '<i class="bi bi-x-square"></i>';

        // Enlazar el evento manualmente
        botonEditar.addEventListener('click', function () {
            habilitarEdicion(this, botonCancelar);
        });

        // Evento de cancelar
        botonCancelar.addEventListener('click', function () {
            cancelarEdicion(this, botonEditar);
            botonCancelar.classList.add('d-none');
            botonEditar.classList.remove('d-none');
        });

        tdBoton.appendChild(botonEditar);
        tdBoton.appendChild(botonCancelar);
        fila.appendChild(tdBoton);

        tabla.appendChild(fila);
    });
}

//Definimos el modal
const modalEditarRegistroHorario = new bootstrap.Modal(document.getElementById('modalEditarRegistroHorario'));
const botonConfirmarEditarRegistro = document.getElementById('botonConfirmarEditarRegistro');

// Guarda los valores originales antes de editar
const valoresOriginalesPorFila = new WeakMap();

export function habilitarEdicion(boton, botonCancelar) {
    const fila = boton.closest('tr');
    const celdasEditables = fila.querySelectorAll('td.editable-hora');
    const valoresOriginales = [];

    celdasEditables.forEach(celda => {
        const valor = celda.innerText.trim();
        console.log('valor para input:', valor);  // DEBUG
        valoresOriginales.push(valor);

        const input = document.createElement('input');
        input.type = 'text';
        input.value = valor;
        input.className = 'form-control form-control-sm';
        celda.innerHTML = '';
        celda.appendChild(input);
    });

    valoresOriginalesPorFila.set(fila, valoresOriginales);

    if (botonCancelar) {
        botonCancelar.classList.remove('d-none');
        botonCancelar.onclick = () => cancelarEdicion(fila, boton, botonCancelar);
    }

    // Remueve cualquier listener previo
    const nuevoBotonGuardar = boton.cloneNode(true);
    nuevoBotonGuardar.innerHTML = '<i class="bi bi-save"></i>';
    nuevoBotonGuardar.title = 'Guardar';
    nuevoBotonGuardar.classList.remove('btn-editar');
    nuevoBotonGuardar.classList.add('btn-guardar');

    // Reemplaza el botón en el DOM
    boton.replaceWith(nuevoBotonGuardar);

    // Asigna evento al nuevo botón
    nuevoBotonGuardar.addEventListener('click', () => guardarEdicion(nuevoBotonGuardar));
}

let datosParaActualizar = {
  idInicio: null,
  idFin: null,
  motivoInicio : null,
  motivoFin: null,
  horaInicio: null,
  horaFin: null
};

//Guardamos el valor del input
const motivo_edicion_input = document.getElementById('motivo_edicion_input');

export function guardarEdicion(botonEditar) {
    const fila = botonEditar.closest('tr');
    const celdasEditables = fila.querySelectorAll('td.editable-hora');
    const inputs = Array.from(celdasEditables).map(celda => celda.querySelector('input')).filter(Boolean);

    console.log('Inputs detectados:', inputs.length);  // DEBUG

    if (inputs.length !== 2) {
        alert('Error: se esperaban 2 campos editables.');
        return;
    }

    const nuevaHoraInicio = inputs[0].value.trim();
    const nuevaHoraFin = inputs[1].value.trim();

    if (nuevaHoraInicio > nuevaHoraFin){
        mensaje.textContent = 'La hora de inicio no puede ser posterior a la hora fin';
        mensaje.classList.remove('d-none');
        mensaje.classList.add('text-black', 'bg-opacity-25', 'bg-danger');
        mensaje.classList.remove('bg-success');

        // Mostrar 3 segundos y luego ocultar
        setTimeout(() => {
            mensaje.classList.add('d-none');
        }, 3000);
        return;
    }

    const usuario = fila.querySelector('td').innerText.trim();
    const fecha = fila.querySelector('td[data-editable]').textContent.trim();

    const jornada = jornadasGlobal.find(j => j.usuario === usuario && j.fecha === fecha);

    if (!jornada) {
        alert('Error: no se encontró la jornada original.');
        return;
    }

    // Combinar fecha + hora
    const nuevaFechaHoraInicio = `${fecha} ${nuevaHoraInicio}`;
    const nuevaFechaHoraFin = `${fecha} ${nuevaHoraFin}`;

    datosParaActualizar = {
        idInicio: jornada.id_inicio,
        idFin: jornada.id_fin,  
        horaInicio: nuevaFechaHoraInicio,
        horaFin: nuevaFechaHoraFin
    };

    modalEditarRegistroHorario.show();
}

botonConfirmarEditarRegistro.addEventListener('click', function () {
    const motivo = motivo_edicion_input.value.trim();

    const mensaje_modal = document.getElementById('mensaje_modal');

    if (!motivo) {
        mensaje_modal.textContent = 'Debes ingresar un motivo para la edición';
        mensaje_modal.classList.remove('d-none');
        mensaje_modal.classList.add('text-black', 'bg-opacity-25', 'bg-danger');
        mensaje_modal.classList.remove('bg-success');
        setTimeout(() => mensaje_modal.classList.add('d-none'), 3000);
        return;
    }

    fetch('/registros_horarios/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            id_inicio: datosParaActualizar.idInicio,
            id_fin: datosParaActualizar.idFin,
            motivo_inicio: motivo,
            motivo_fin: motivo,
            hora_inicio: datosParaActualizar.horaInicio,
            hora_fin: datosParaActualizar.horaFin
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mensaje.textContent = 'Registro actualizado correctamente';
            mensaje.classList.remove('d-none');
            mensaje.classList.add('text-black', 'bg-opacity-25', 'bg-success');
            mensaje.classList.remove('bg-danger');

            setTimeout(() => mensaje.classList.add('d-none'), 3000);
            modalEditarRegistroHorario.hide();
            cargarRegistros();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error en la solicitud:', error);
        alert('Error al procesar la solicitud');
    });
});

export function cancelarEdicion(botonCancelar, botonEditar) {
    botonCancelar.classList.add('d-none');
    botonEditar.classList.remove('d-none');

    cargarRegistros();
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
