<main class="container-fluid m-2">
  <div class="d-flex align-items-center mb-3 position-relative">
    <div class="position-relative" style="height: 80px; width: 80px;">
      <?php if (!empty($_SESSION['foto']) && strpos($_SESSION['foto'], 'data:image') === 0): ?>
        <img src="<?= htmlspecialchars($_SESSION['foto']) ?>" 
            alt="Foto de perfil" 
            class="rounded-circle border border-primary"
            style="height: 100%; width: 100%; object-fit: cover;">
      <?php else: ?>
        <img src="/public/images/default.jpeg" 
            alt="Foto por defecto" 
            class="rounded-circle border border-primary"
            style="height: 100%; width: 100%; object-fit: cover;">
      <?php endif; ?>

      <!-- Botón con ícono de cámara -->
      <button id="boton_editar_foto" class="btn btn-sm btn-primary position-absolute bottom-0 end-0 rounded-circle" 
              style="transform: translate(30%, 30%);"
              title="Cambiar foto de perfil">
        <i class="bi bi-camera-fill" style="font-size: 0.9rem;"></i>
      </button>
    </div>

    <div class="ms-4">
      <h2 class="mb-0">Mi Perfil</h2>
    </div>
  </div>

  <!-- Información Personal -->
  <div class="row mb-4">
    <form action="/controllers/UsuarioController.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="accion" value="editar_perfil">
      <?php $usuarios = $usuario; ?>
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="edit_nombre" class="form-label">Nombre completo</label>
            <input type="text" class="form-control" id="edit_nombre" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
            <input type="hidden" name="id" value="<?= htmlspecialchars($usuario['id']) ?>">
          </div>
          <div class="col-md-6">
            <label for="edit_alias" class="form-label">Alias</label>
            <input type="text" class="form-control" id="edit_alias" name="alias" value="<?= htmlspecialchars($usuario['alias']) ?>">
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label for="edit_email" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" id="edit_email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>
          </div>
          <div class="col-md-6">
            <label for="edit_telefono" class="form-label">Teléfono / Extensión</label>
            <input type="text" class="form-control" id="edit_telefono" value="<?= htmlspecialchars($usuario['telefono']) ?>" name="telefono">
          </div>
        </div>
      <button type="submit" class="btn btn-primary">Guardar cambios</button>
    </form>
  </div>

  <!-- Cambiar contraseña y PIN -->
  <div class="row mb-4">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            Cambiar contraseña
          </div>
          <div class="card-body">
            <form action="/controllers/UsuarioController.php" method="POST" enctype="multipart/form-data">
              <input type="hidden" name="accion" value="editar_password">
              <input type="hidden" name="id" value="<?= htmlspecialchars($usuario['id']) ?>">
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="new_password" class="form-label">Nueva Contraseña</label>
                  <div class="input-group">
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                    <button class="btn btn-outline-secondary" type="button" id="toggleNewPassword" aria-label="Mostrar contraseña">
                      <i class="bi bi-eye-slash" id="iconPassword"></i>
                    </button>
                  </div>
                </div>
                <div class="col-md-6">
                  <label for="new_password_confirm" class="form-label">Repetir Contraseña</label>
                  <div class="input-group">
                    <input type="password" class="form-control" id="new_password_confirm" name="new_password_confirm" required>
                    <button class="btn btn-outline-secondary" type="button" id="toggleNewPassword_confirm" aria-label="Mostrar contraseña">
                      <i class="bi bi-eye-slash" id="iconConfirm"></i>
                    </button>
                  </div>
                  <div id="passwordHelp" class="form-text text-danger d-none">Las contraseñas no coinciden.</div>
                </div>
              </div>

              <button type="submit" class="btn btn-primary">Cambiar contraseña</button>
            </form>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            Cambiar PIN
          </div>
          <div class="card-body">
            <form action="/controllers/UsuarioController.php" method="POST" enctype="multipart/form-data">
              <input type="hidden" name="accion" value="editar_pin">
              <input type="hidden" name="id" value="<?= htmlspecialchars($usuario['id']) ?>">
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="new_pin" class="form-label">Nuevo PIN</label>
                  <div class="input-group">
                    <input type="password" class="form-control" id="new_pin" name="new_pin" required>
                    <button class="btn btn-outline-secondary" type="button" id="toggleNewPin" aria-label="Mostrar contraseña">
                      <i class="bi bi-eye-slash" id="iconPin"></i>
                    </button>
                  </div>
                </div>
                <div class="col-md-6">
                  <label for="new_pin_confirm" class="form-label">Repetir PIN</label>
                  <div class="input-group">
                    <input type="password" class="form-control" id="new_pin_confirm" name="new_pin_confirm" required>
                    <button class="btn btn-outline-secondary" type="button" id="toggleNewPin_confirm" aria-label="Mostrar contraseña">
                      <i class="bi bi-eye-slash" id="iconPinConfirm"></i>
                    </button>
                  </div>
                  <div id="PinHelp" class="form-text text-danger d-none">Los PIN no coinciden.</div>
                </div>
              </div>
              <button type="submit" class="btn btn-primary">Cambiar PIN</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Resumen de horas -->
    <div class="row mb-3">
      <div class="col-md-3">
        <div class="card border-primary mb-3">
          <div class="card-body">
            <h5 class="card-title">Horas trabajadas en <?= date('Y') ?></h5>
            <p class="card-text fs-4"><?= formatoResumen($resumen['trabajadas'] ?? '00:00:00') ?></p>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card border-success mb-3">
          <div class="card-body">
            <h5 class="card-title">Horas extra en <?= date('Y') ?></h5>
            <p class="card-text fs-4"><?= formatoResumen($resumen['extras'] ?? '00:00:00') ?></p>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card border-danger mb-3">
          <div class="card-body">
            <h5 class="card-title">Horas Pendientes <?= date('Y') ?></h5>
            <p class="card-text fs-4"><?= formatoResumen($resumen['pendientes'] ?? '00:00:00') ?></p>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card border-warning mb-3">
          <div class="card-body">
            <h5 class="card-title">Vacaciones en <?= date('Y') ?></h5>
            <p class="card-text fs-4"><?= $resumen['total_horas'] ?? 0 ?> días</p>
          </div>
        </div>
      </div>
    </div>

  <!-- Tipo de contrato -->
  <div class="row mb-4">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          Información del Contrato
        </div>
        <div class="card-body">
          <p class="fs-5 mb-2"><strong>Tipo de contrato:</strong> <?= htmlspecialchars($usuario['tipo_contrato'] ?? '') ?></p>
          <p class="fs-5 mb-2"><strong>Horas contratadas:</strong> <?= htmlspecialchars($usuario['horas_contrato'] ?? '') ?></p>
          <p class="fs-5 mb-0"><strong>Fecha de inicio:</strong> <?= !empty($usuario['fecha_inicio']) ? date('d/m/Y', strtotime($usuario['fecha_inicio'])) : '' ?></p>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Editar Foto -->
  <?php include_once __DIR__ . '../../components/modal_perfil/modal_editar_foto.php'; ?>
</main>
<script src="/public/js/usuarios/perfil_user.js" defer></script>
<script src="/public/js/cropper_util.js"></script>

