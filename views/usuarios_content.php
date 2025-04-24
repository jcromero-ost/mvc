<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Listado de usuarios</h2>
    <a href="/crear_usuario" class="btn btn-success">
      <i class="bi bi-person-plus-fill"></i> Crear nuevo usuario
    </a>
  </div>

  <div class="table-responsive">
    <table class="table table-hover align-middle">
      <thead class="table-dark">
        <tr>
          <th>Usuario</th>
          <th>Email</th>
          <th>Departamento</th>
          <th>Estado</th>
          <th class="text-center">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($usuarios as $usuario): ?>
          <tr>
            <td>
              <div class="d-flex align-items-center">
                <img src="/public/images/<?= htmlspecialchars($usuario['foto'] ?? 'default.jpeg') ?>"
                     alt="Foto"
                     class="rounded-circle me-2"
                     style="width: 45px; height: 45px; object-fit: cover;">
                <div>
                  <strong><?= htmlspecialchars($usuario['nombre']) ?></strong><br>
                  <small class="text-muted"><?= htmlspecialchars($usuario['alias'] ?? '') ?></small>
                </div>
              </div>
            </td>
            <td><?= htmlspecialchars($usuario['email']) ?></td>
            <td><?= htmlspecialchars($usuario['nombre_departamento'] ?? 'Sin asignar') ?></td>
            <td>
              <span class="badge bg-<?= $usuario['activo'] ? 'success' : 'secondary' ?>">
                <?= $usuario['activo'] ? 'Activo' : 'Inactivo' ?>
              </span>
            </td>
            <td class="text-center">

              <button type="button" class="btn btn-sm btn-primary me-1 btn-editar"
                data-usuario='<?= json_encode($usuario, JSON_HEX_APOS | JSON_UNESCAPED_UNICODE) ?>'
                title="Editar">
                <i class="bi bi-pencil-square"></i>
              </button>

              <button type="button" class="btn btn-sm btn-danger btn-eliminar"
                data-id="<?= $usuario['id'] ?>"
                data-nombre="<?= htmlspecialchars($usuario['nombre']) ?>"
                title="Eliminar">
                <i class="bi bi-trash"></i>
              </button>

            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <!-- Modal editar usuario -->
<div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-labelledby="modalEditarUsuarioLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="formEditarUsuario" method="POST" action="/controllers/UsuarioController.php">
        <div class="modal-header">
          <h5 class="modal-title" id="modalEditarUsuarioLabel">Editar usuario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="accion" value="editar">
          <input type="hidden" name="id" id="edit_id">

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Nombre</label>
              <input type="text" class="form-control" name="nombre" id="edit_nombre" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Alias</label>
              <input type="text" class="form-control" name="alias" id="edit_alias">
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Correo</label>
              <input type="email" class="form-control" name="email" id="edit_email" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Teléfono</label>
              <input type="text" class="form-control" name="telefono" id="edit_telefono">
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Fecha de ingreso</label>
              <input type="date" class="form-control" name="fecha_ingreso" id="edit_fecha_ingreso">
            </div>
            <div class="col-md-6">
              <label class="form-label">Estado</label>
              <select class="form-select" name="activo" id="edit_activo">
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Guardar cambios</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Modal confirmación de eliminación -->
<div class="modal fade" id="modalEliminarUsuario" tabindex="-1" aria-labelledby="modalEliminarUsuarioLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="/controllers/UsuarioController.php">
        <input type="hidden" name="accion" value="eliminar">
        <input type="hidden" name="id" id="delete_id">

        <div class="modal-header">
          <h5 class="modal-title" id="modalEliminarUsuarioLabel">Eliminar usuario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          <p>¿Estás seguro que deseas eliminar al usuario <strong id="delete_nombre"></strong>?</p>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-danger">Eliminar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>

</div>
<script src="/public/js/usuarios.js"></script>
<script src="/public/js/usuarios_eliminar.js"></script>
