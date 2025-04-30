import { cargarRegistros, setPaginaActual } from './reg_hor_list_core.js';

document.addEventListener('DOMContentLoaded', () => {
    cargarRegistros();

    document.getElementById('btn-filtrar').addEventListener('click', () => {
        setPaginaActual(1);
        cargarRegistros();
    });

    document.getElementById('cantidad').addEventListener('change', () => {
        setPaginaActual(1);
        cargarRegistros();
    });

    // Buscador de usuarios con sugerencias
    const inputUsuario = document.getElementById('usuario-buscador');
    const campoOculto = document.getElementById('usuario');
    const sugerencias = document.getElementById('sugerencias-usuarios');

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
                        }
                    });
            }, 300);
        } else {
            sugerencias.innerHTML = '';
        }
    });
});
