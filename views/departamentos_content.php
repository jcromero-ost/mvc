<div class="container mt-4">
  <h2 class="mb-4">Gestión de Departamentos</h2>

  <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#crearDepartamentoModal">
    <i class="bi bi-plus-lg me-1"></i> Crear nuevo departamento
  </button>

  <table class="table table-bordered align-middle">
    <thead class="table-light">
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($departamentos as $dep): ?>
        <tr>
          <td><?= htmlspecialchars($dep['id']) ?></td>
          <td><?= htmlspecialchars($dep['nombre']) ?></td>
          <td>
            <button class="btn btn-sm btn-outline-primary btn-editar"
              data-id="<?= $dep['id'] ?>"
              data-nombre="<?= htmlspecialchars($dep['nombre']) ?>"
              data-bs-toggle="modal"
              data-bs-target="#editarDepartamentoModal">
              <i class="bi bi-pencil"></i> Editar
            </button>

            <button type="button"
              class="btn btn-sm btn-outline-danger btn-confirmar-eliminar"
              data-id="<?= $dep['id'] ?>"
              data-nombre="<?= htmlspecialchars($dep['nombre']) ?>"
              data-bs-toggle="modal"
              data-bs-target="#confirmarEliminarModal">
              <i class="bi bi-trash"></i> Eliminar
            </button>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- Modal Crear -->
<div class="modal fade" id="crearDepartamentoModal" tabindex="-1" aria-labelledby="crearDepartamentoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="/crear_departamento" method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="crearDepartamentoLabel">Crear Departamento</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="nombre" class="form-label">Nombre del departamento</label>
          <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Guardar</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Editar -->
<div class="modal fade" id="editarDepartamentoModal" tabindex="-1" aria-labelledby="editarDepartamentoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="/editar_departamento" method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editarDepartamentoLabel">Editar Departamento</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="editar-id">
        <div class="mb-3">
          <label for="editar-nombre" class="form-label">Nombre del departamento</label>
          <input type="text" class="form-control" id="editar-nombre" name="nombre" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Actualizar</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Confirmar Eliminar -->
<div class="modal fade" id="confirmarEliminarModal" tabindex="-1" aria-labelledby="confirmarEliminarLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="/eliminar_departamento" method="POST" class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="confirmarEliminarLabel">Confirmar eliminación</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <p>¿Estás seguro de que deseas eliminar el departamento <strong id="nombreDepartamentoEliminar"></strong>?</p>
        <input type="hidden" name="id" id="idDepartamentoEliminar">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-danger">Eliminar</button>
      </div>
    </form>
  </div>
</div>

<!-- JS externo para manejar modales -->
<script src="/public/js/departamentos.js" defer></script>
