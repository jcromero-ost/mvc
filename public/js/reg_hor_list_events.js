import { cargarRegistros, setPaginaActual, formatearFechaInput } from './reg_hor_list_core.js';

document.addEventListener('DOMContentLoaded', () => {
    cargarRegistros();

    document.getElementById('btn-filtrar').addEventListener('click', () => {
        setPaginaActual(1);
        cargarRegistros();
    });

    document.getElementById('btn-imprimir').addEventListener('click', () => {
        const usuario = document.getElementById('usuario').value;
        const fechaDesde = document.getElementById('fecha_desde').value;
        const fechaHasta = document.getElementById('fecha_hasta').value;
    
        const desdeFormateada = formatearFechaInput(fechaDesde);  // usa tu función si es necesario
        const hastaFormateada = formatearFechaInput(fechaHasta);
    
        const url = `/registro-horario/imprimir?usuario=${encodeURIComponent(usuario)}&desde=${encodeURIComponent(desdeFormateada)}&hasta=${encodeURIComponent(hastaFormateada)}`;
    
        window.open(url, '_blank');
    });
    
    

    document.getElementById('cantidad').addEventListener('change', () => {
        setPaginaActual(1);
        cargarRegistros();
    });

    // Buscador de usuarios con sugerencias
    const inputUsuario = document.getElementById('usuario-buscador');
    const campoOculto = document.getElementById('usuario');
    const sugerencias = document.getElementById('sugerencias-usuarios');

    if (inputUsuario && campoOculto && sugerencias) {
        let timeout = null;

        inputUsuario.addEventListener('input', () => {
            const valor = inputUsuario.value.trim();
            clearTimeout(timeout);

            if (valor.length >= 3) {
                timeout = setTimeout(() => {
                    fetch('/usuarios/activos')
                        .then(res => res.json())
                        .then(data => {
                            if (data.success && Array.isArray(data.data)) {
                                sugerencias.innerHTML = '';
                                data.data
                                    .filter(u => u.nombre.toLowerCase().includes(valor.toLowerCase()))
                                    .forEach(u => {
                                        const item = document.createElement('button');
                                        item.type = 'button';
                                        item.className = 'list-group-item list-group-item-action';
                                        item.textContent = u.nombre;
                                        item.addEventListener('click', () => {
                                            inputUsuario.value = u.nombre;
                                            campoOculto.value = u.id;
                                            sugerencias.innerHTML = '';
                                        });
                                        sugerencias.appendChild(item);
                                    });
                                    sugerencias.style.width = inputUsuario.offsetWidth + "px";

                            }
                        });
                }, 300);
            } else {
                sugerencias.innerHTML = '';
            }
        });
        inputUsuario.addEventListener('blur', () => {
            setTimeout(() => {
                sugerencias.innerHTML = '';
            }, 200); // espera para permitir hacer clic
        });
    }
});
