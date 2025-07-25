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
                <?php if (!empty($usuario['foto']) && str_starts_with($usuario['foto'], 'data:image')): ?>
                  <img src="<?= $usuario['foto'] ?>" class="rounded-circle" style="height: 40px;">
                <?php else: ?>
                  <img src="/public/images/<?= $usuario['foto'] ?? 'default.jpeg' ?>" class="rounded-circle" style="height: 40px;">
                <?php endif; ?>

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

              <button type="button" class="btn btn-sm btn-warning btn-password"
                data-usuario='<?= json_encode($usuario, JSON_HEX_APOS | JSON_UNESCAPED_UNICODE) ?>'
                title="Cambiar contraseña">
                <i class="bi bi-pencil-fill"></i>
              </button>

            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <!-- Modal editar usuario -->
  <?php include_once __DIR__ . '/components/modal_editar_usuario.php'; ?>

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

  <?php include_once __DIR__ . '/components/modal_usuarios/modal_cambiar_password.php'; ?>


</div>
<script src="/public/js/cropper_util.js"></script>
<script src="/public/js/usuarios_editar.js"></script>
<script src="/public/js/usuarios_eliminar.js"></script>
<script src="/public/js/usuarios/usuarios_cambiar_password.js"></script>

