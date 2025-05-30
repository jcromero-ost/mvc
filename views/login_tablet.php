<?php 
require_once __DIR__ . '/../models/Usuario.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usuarioModel = new Usuario();
$usuarios = $usuarioModel->getAllUsuarios();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Tablet - Intranet OSTTECH</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="/public/css/styles.css">
  <link rel="stylesheet" href="/public/css/styles_osttech.css?v=3">
  <style>
    .pin-key {
      width: 70px;
      height: 70px;
      font-size: 1.5rem;
      transition: transform 0.1s ease;
    }
    .pin-key:active {
      transform: scale(0.95);
      opacity: 0.9;
    }

    #pin-input {
      -webkit-text-security: disc;
      text-security: disc;
    }

    .user-card {
      transition: transform 0.2s;
      cursor: pointer;
    }

    .user-card:hover {
      transform: scale(1.02);
    }

    .user-card img {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 50%;
    }

    .card-title {
      font-size: 1.2rem;
      font-weight: 600;
    }

    .initial {
      font-size: 1.5rem;
      font-weight: 400;
    }

    body {
    background: url('https://img.freepik.com/foto-gratis/fondo-cuadricula-digital-abstracto-negro_53876-97647.jpg?t=st=1745503928~exp=1745507528~hmac=0e87f35f234704e5108c8c58eea155e3d3fc75a364f977da092656af641d872b&w=996') no-repeat center center fixed;
    background-size: cover;
    margin: 0;
  }
  </style>
</head>
<body class="bg-dark text-light">

<header class="text-center py-3 bg-white border-bottom border-secondary text-dark">
  <img src="/public/images/osttech.png" alt="OSTTECH" height="60">
</header>

<main class="container my-4">
  <h3 class="text-center mb-4">Selecciona tu usuario</h3>
  <div class="row row-cols-1 row-cols-sm-2 g-4">
    <?php foreach ($usuarios as $usuario): ?>
      <?php if ($usuario['rol'] !== 'webmaster'): ?>
        <?php
  $estado = Usuario::estadoJornada($usuario['id']);

  switch ($estado) {
    case 'iniciada':
      $clase = 'success';
      $texto = 'Jornada iniciada';
      break;
    case 'descanso':
      $clase = 'warning';
      $texto = 'En descanso';
      break;
    case 'finalizada':
      $clase = 'secondary';
      $texto = 'Finalizada';
      break;
    default:
      $clase = 'light text-dark';
      $texto = 'Sin actividad';
  }
?>

        <div class="col">
          <div class="card text-center h-100 shadow-sm user-card p-3" data-id="<?= $usuario['id'] ?>" data-nombre="<?= htmlspecialchars($usuario['nombre']) ?>">
<?php
  $foto = trim($usuario['foto']);
  $usarDefault = empty($foto) || strtolower($foto) === 'default.jpeg';
  $rutaImagen = $usarDefault ? '/public/images/default.jpeg' : htmlspecialchars($foto);
?>
<img src="<?= $rutaImagen ?>" alt="Foto de <?= htmlspecialchars($usuario['nombre']) ?>" class="mx-auto mb-3 rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
            <h5 class="card-title mb-1"><?= htmlspecialchars($usuario['nombre']) ?></h5>
            
            <span class="badge bg-<?= $clase ?>"><?= $texto ?></span>
          </div>
        </div>
      <?php endif; ?>
    <?php endforeach; ?>
  </div>
</main>

<!-- Modal de PIN -->
<div class="modal fade" id="pinModal" tabindex="-1" aria-labelledby="pinModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-light text-dark shadow">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="pinModalLabel">Introduce tu PIN</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body text-center">
        <input type="hidden" id="modal-user-id">

        <!-- Input de PIN -->
        <div class="mb-3">
          <input type="password" id="pin-input" class="form-control form-control-lg text-center" placeholder="PIN" maxlength="4" readonly>
        </div>

        <!-- Teclado numérico -->
        <div class="d-flex flex-wrap justify-content-center gap-2" style="max-width: 270px; margin: 0 auto;">
          <?php foreach ([1,2,3,4,5,6,7,8,9] as $num): ?>
            <button type="button" class="btn btn-outline-dark rounded-circle pin-key"><?= $num ?></button>
          <?php endforeach; ?>
          <button type="button" class="btn btn-outline-warning rounded-circle pin-key" data-action="clear">C</button>
          <button type="button" class="btn btn-outline-dark rounded-circle pin-key">0</button>
          <button type="button" class="btn btn-outline-danger rounded-circle pin-key" data-action="backspace">←</button>
        </div>

        <div id="pin-error" class="text-danger d-none mt-3">PIN incorrecto. Intenta de nuevo.</div>
      </div>
    </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const pinModal = new bootstrap.Modal(document.getElementById('pinModal'));
  const modalUserId = document.getElementById('modal-user-id');
  const pinInput = document.getElementById('pin-input');
  const pinError = document.getElementById('pin-error');

  document.querySelectorAll('.user-card').forEach(card => {
    card.addEventListener('click', () => {
      modalUserId.value = card.dataset.id;
      document.getElementById('pinModalLabel').textContent = `PIN para ${card.dataset.nombre}`;
      pinInput.value = '';
      pinError.classList.add('d-none');
      pinModal.show();
    });
  });

  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.pin-key').forEach(btn => {
      btn.addEventListener('click', () => {
        const val = btn.textContent.trim();
        const action = btn.dataset.action;

        if (action === 'clear') {
          pinInput.value = '';
          pinError.classList.add('d-none');
        } else if (action === 'backspace') {
          pinInput.value = pinInput.value.slice(0, -1);
        } else if (pinInput.value.length < 4) {
          pinInput.value += val;
        }

        if (pinInput.value.length === 4) {
          validarPin();
        }
      });
    });

    function validarPin() {
      const pin = pinInput.value;
      const usuario_id = modalUserId.value;

      fetch('/login_tablet/pin', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ usuario_id, pin }),
        credentials: 'same-origin'
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          window.location.href = data.redirect;
        } else {
          pinError.textContent = data.error || 'PIN incorrecto.';
          pinError.classList.remove('d-none');
          pinInput.value = '';
        }
      })
      .catch(() => {
        pinError.textContent = 'Error de conexión.';
        pinError.classList.remove('d-none');
      });
    }
  });
</script>

</body>
</html>
