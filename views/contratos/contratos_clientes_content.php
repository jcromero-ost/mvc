<div class="container mt-4">
  <h2 class="mb-4">Contratos de Clientes</h2>

  <div class="table-responsive">
    <table class="table">
      <thead>
        <tr>
          <th>Id Contrato</th>
          <th>Cliente</th>
          <th>Fecha de Alta</th>
          <th>Servicios</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($contratos as $contrato): ?>
          <tr>
            <td><?= $contrato['id'] ?></td>
            <td><?= htmlspecialchars($contrato['cliente_nombre']) ?></td>
            <td><?= $contrato['fecha_alta'] ?></td>
            <td>
              <button class="btn btn-link toggle-servicios" data-target="#servicios-<?= $contrato['id'] ?>">
                Ver servicios
              </button>
            </td>
          </tr>
          <tr id="servicios-<?= $contrato['id'] ?>" class="collapse-row" style="display: none;">
            <td colspan="4">
              <ul class="mb-0">
                <?php foreach ($contrato['servicios'] as $servicio): ?>
                  <li><?= htmlspecialchars($servicio['nombre']) ?></li>
                <?php endforeach; ?>
              </ul>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

  </div>
</div>
<script src="/public/js/contratosCli/contratos_clientes.js" defer></script>
