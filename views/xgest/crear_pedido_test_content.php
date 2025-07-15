<div class="container mt-4">
    <h2>Crear Pedido en XGEST</h2>

    <form id="pedidoForm" class="border p-3 rounded bg-light">
        <div class="mb-3">
            <label for="cliente_id">ID Cliente</label>
            <input type="hidden" name="cliente_id" id="cliente_id">
            <input type="text" id="cliente_busqueda" class="form-control" placeholder="Buscar por nombre, teléfono o DNI" autocomplete="off">
            <div id="sugerencias" class="list-group position-absolute" style="z-index:1000;"></div>

        </div>

        <div class="mb-3">
            <label for="texto">Texto del pedido (bpedid)</label>
            <input type="text" name="texto" class="form-control">
        </div>

        <div class="mb-3">
            <label for="almacen">Almacén</label>
            <input type="number" name="almacen" class="form-control" value="1" required>
        </div>

        <hr>
        <h4>Líneas del pedido</h4>
        <div id="lineasContainer">
            <div class="linea row g-2 mb-2">
                <div class="col-md-3">
                    <input type="text" name="codigo[]" placeholder="Código artículo" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" name="precio[]" placeholder="Precio" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" name="cantidad[]" placeholder="Cantidad" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" name="descuento[]" placeholder="Descuento (%)" class="form-control">
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" name="importe[]" placeholder="Importe (opcional)" class="form-control">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-sm remove-linea">×</button>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-secondary btn-sm mb-3" id="addLinea">+ Añadir línea</button>

        <button type="submit" class="btn btn-primary">Crear Pedido</button>
    </form>

    <div id="resultado" class="mt-3"></div>
</div>

<script>
document.getElementById('addLinea').addEventListener('click', function () {
    const container = document.getElementById('lineasContainer');
    const lineas = container.querySelectorAll('.linea');
    const nueva = lineas[0].cloneNode(true);
    nueva.querySelectorAll('input').forEach(input => input.value = '');
    container.appendChild(nueva);
});

document.getElementById('lineasContainer').addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-linea')) {
        const lineas = document.querySelectorAll('.linea');
        if (lineas.length > 1) {
            e.target.closest('.linea').remove();
        }
    }
});

document.getElementById('pedidoForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const form = new FormData(this);
    const payload = {
        cliente_id: form.get('cliente_id'),
        texto: form.get('texto'),
        almacen: form.get('almacen'),
        lineas: []
    };

    const codigos = form.getAll('codigo[]');
    const precios = form.getAll('precio[]');
    const cantidades = form.getAll('cantidad[]');
    const descuentos = form.getAll('descuento[]');
    const importes = form.getAll('importe[]');

    for (let i = 0; i < codigos.length; i++) {
        if (!codigos[i] || !precios[i] || !cantidades[i]) continue;

        const linea = {
            codigo: codigos[i],
            precio: parseFloat(precios[i]),
            cantidad: parseFloat(cantidades[i])
        };
        if (descuentos[i]) linea.descuento = parseFloat(descuentos[i]);
        if (importes[i]) linea.importe = parseFloat(importes[i]);

        payload.lineas.push(linea);
    }

    const res = await fetch('/xgest/crear-pedido', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    });

    const json = await res.json();
    const div = document.getElementById('resultado');
    if (json.success) {
        div.innerHTML = `<div class="alert alert-success">✅ Pedido creado con número: <strong>${json.pedido}</strong></div>`;
    } else {
        div.innerHTML = `<div class="alert alert-danger">❌ Error: ${json.error}</div>`;
    }
});

// --- ESTE BLOQUE DEBE ESTAR FUERA DEL SUBMIT ---
const inputBusqueda = document.getElementById('cliente_busqueda');
const sugerencias = document.getElementById('sugerencias');
const clienteIdInput = document.getElementById('cliente_id');

inputBusqueda.addEventListener('input', async function () {
    const query = this.value.trim();
    sugerencias.innerHTML = '';

    if (query.length < 2) return;

    try {
        const res = await fetch(`/xgest/buscar-clientes?q=${encodeURIComponent(query)}`);
        const data = await res.json();

        if (!data.length) {
            sugerencias.innerHTML = '<div class="list-group-item disabled">Sin resultados</div>';
            return;
        }

        data.forEach(cliente => {
            const item = document.createElement('button');
            item.type = 'button';
            item.className = 'list-group-item list-group-item-action';
            item.textContent = `${cliente.nombre} (${cliente.dni}) - ${cliente.telefono}`;
            item.onclick = () => {
                inputBusqueda.value = cliente.nombre;
                clienteIdInput.value = cliente.id;
                sugerencias.innerHTML = '';
            };
            sugerencias.appendChild(item);
        });
    } catch (err) {
        sugerencias.innerHTML = '<div class="list-group-item text-danger">⚠️ Error al buscar</div>';
    }
});

document.addEventListener('click', function (e) {
    if (!sugerencias.contains(e.target) && e.target !== inputBusqueda) {
        sugerencias.innerHTML = '';
    }
});
</script>

