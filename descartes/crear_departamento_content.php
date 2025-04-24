<div class="container mt-4">
  <h2>Crear nuevo departamento</h2>

  <form method="POST" action="../controllers/DepartamentoController.php">
    <div class="mb-3">
      <label for="nombre" class="form-label">Nombre del departamento</label>
      <input type="text" class="form-control" id="nombre" name="nombre" required>
    </div>

    <button type="submit" class="btn btn-primary">Guardar</button>
  </form>
</div>
