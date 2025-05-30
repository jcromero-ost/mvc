<?php if (session_status() === PHP_SESSION_NONE) {
    session_start();
} ?>
<?php include_once 'components/head.php'; ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
  body {
    background: url('https://img.freepik.com/foto-gratis/fondo-cuadricula-digital-abstracto-negro_53876-97647.jpg?t=st=1745503928~exp=1745507528~hmac=0e87f35f234704e5108c8c58eea155e3d3fc75a364f977da092656af641d872b&w=996') no-repeat center center fixed;
    background-size: cover;
    margin: 0;
  }

  .login-box {
    background-color: rgba(255, 255, 255, 0.94);
    backdrop-filter: blur(8px);
    border-radius: 1rem;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
    animation: fadeIn 1s ease;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .input-group-text {
    background-color: #e9ecef;
    border-right: none;
  }

  .form-control {
    border-left: none;
  }
</style>

<body>
  <div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="login-box p-5" style="width: 100%; max-width: 420px;">
      <div class="text-center mb-4">
        <img src="/public/images/osttech.png" alt="Logo OSTTECH" style="max-height: 80px;" class="mb-2">
        <p class="text-muted small">Gestión y control interno</p>
      </div>

      <?php include_once 'components/alerts.php'; ?>

      <form action="/login" method="post">
        <div class="mb-3 input-group">
          <span class="input-group-text"><i class="bi bi-envelope"></i></span>
          <input type="email" name="user" class="form-control" placeholder="usuario@dominio.com" required>
        </div>

        <div class="mb-4 input-group">
          <span class="input-group-text"><i class="bi bi-lock"></i></span>
          <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Iniciar sesión</button>
      </form>
    </div>
  </div>
<script>
  window.addEventListener('DOMContentLoaded', () => {
    const isTablet = window.innerWidth <= 820 && window.innerHeight > window.innerWidth;
    if (isTablet) {
      window.location.href = '/login_tablet';
    }
  });
</script>
</body>

</html>
