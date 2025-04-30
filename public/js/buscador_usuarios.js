const inputUsuario = document.getElementById('usuario-buscador');
const campoOculto = document.getElementById('usuario');
const sugerencias = document.getElementById('sugerencias-usuarios');

let timeout = null;
let resultados = [];
let indiceSeleccionado = -1;

inputUsuario.addEventListener('input', () => {
    const valor = inputUsuario.value.trim();
    campoOculto.value = ''; // Resetear el ID
    sugerencias.innerHTML = '';
    resultados = [];
    indiceSeleccionado = -1;

    clearTimeout(timeout);

    if (valor.length >= 2) {
        timeout = setTimeout(() => {
            fetch('/usuarios/activos')
                .then(res => res.json())
                .then(data => {
                    if (data.success && Array.isArray(data.data)) {
                        resultados = data.data.filter(u => u.nombre.toLowerCase().includes(valor.toLowerCase()));
                        sugerencias.innerHTML = '';
                        resultados.forEach((u, index) => {
                            const item = document.createElement('button');
                            item.type = 'button';
                            item.className = 'list-group-item list-group-item-action';
                            item.textContent = u.nombre;
                            item.setAttribute('data-id', u.id);
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
    }
});

inputUsuario.addEventListener('keydown', e => {
    const items = sugerencias.querySelectorAll('.list-group-item');
    if (!items.length) return;

    if (e.key === 'ArrowDown') {
        e.preventDefault();
        if (indiceSeleccionado < items.length - 1) indiceSeleccionado++;
    } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        if (indiceSeleccionado > 0) indiceSeleccionado--;
    } else if (e.key === 'Enter') {
        e.preventDefault();
        if (indiceSeleccionado >= 0) {
            items[indiceSeleccionado].click();
        }
    }

    items.forEach((item, i) => {
        item.classList.toggle('active', i === indiceSeleccionado);
    });
});
