<main class="container mt-4">
    <h2 class="mb-4">Resumen Mensual de Horas</h2>

    <form id="formResumenHoras" class="row g-3 align-items-end mb-4">
        <?php if ($_SESSION['dept'] == '2' || $_SESSION['dept'] == '3'): ?>
            <div class="col-md-5">
                <div class="position-relative">
                    <label for="usuario-buscador" class="form-label">Usuario</label>
                    <input type="text" id="usuario-buscador" class="form-control" placeholder="Escriba el nombre del usuario..." autocomplete="off">
                    <input type="hidden" id="usuario" name="usuario_id">
                    <div id="sugerencias-usuarios" class="list-group position-absolute w-100" style="z-index: 1000;"></div>
                </div>
            </div>

            <div class="col-md-3">
                <label for="anio" class="form-label">Año</label>
                <select id="anio" name="anio" class="form-select">
                    <?php
                    $anioActual = date('Y');
                    for ($a = $anioActual; $a >= $anioActual - 5; $a--) {
                        echo "<option value=\"$a\">$a</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-success w-100">Generar</button>
            </div>
            <div class="col-md-2 text-end">
                <button type="button" onclick="window.print()" class="btn btn-primary w-100">Imprimir</button>
            </div>
        <?php else: ?>
                <div class="position-relative d-none">
                    <label for="usuario-buscador" class="form-label">Usuario</label>
                    <input type="text" id="usuario-buscador" class="form-control" placeholder="Escriba el nombre del usuario..." autocomplete="off">
                    <input type="hidden" id="usuario" name="usuario_id">
                    <div id="sugerencias-usuarios" class="list-group position-absolute w-100" style="z-index: 1000;"></div>
                </div>
            
            <div class="col-md-8">
                <label for="anio" class="form-label">Año</label>
                <select id="anio" name="anio" class="form-select">
                    <?php
                    $anioActual = date('Y');
                    for ($a = $anioActual; $a >= $anioActual - 5; $a--) {
                        echo "<option value=\"$a\">$a</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-success w-100">Generar</button>
            </div>
            <div class="col-md-2 text-end">
                <button type="button" onclick="window.print()" class="btn btn-primary w-100">Imprimir</button>
            </div>
        <?php endif; ?>
    </form>


    <div id="tablaResumenHoras">
        <!-- Aquí se insertará la tabla con resumen mensual -->
    </div>
</main>

<script src="public/js/buscador_usuarios.js"></script>
<script src="public/js/resumenhoras/resumen_horas.js"></script>

<script>
    const dept_usuario = "<?= $_SESSION['dept'] ?>";
    const id_usuario_logueado = "<?= $_SESSION['id'] ?>";
</script>

