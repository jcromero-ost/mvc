<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Impresión de Registros Horarios</title>
  <link rel="stylesheet" href="/public/css/print.css" media="print">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      font-size: 14px;
      color: #333;
      padding: 2rem;
    }
    h2 {
      text-align: center;
      margin-bottom: 2rem;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 0.9rem;
    }
    th, td {
      border: 1px solid #ccc;
      padding: 0.5rem;
      vertical-align: middle;
    }
    th {
      background-color: #009688;
      color: white;
      text-align: center;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    .usuario {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    .usuario img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
    }
    ul {
      margin: 0;
      padding-left: 1.2rem;
    }
  </style>
</head>
<body>

  <h2>Listado de Registros Horarios</h2>

  <table>
    <thead>
      <tr>
        <th>Usuario</th>
        <th>Fecha</th>
        <th>Hora Inicio</th>
        <th>Hora Fin</th>
        <th>Descansos</th>
      </tr>
    </thead>
<tbody>
  <?php foreach ($jornadas as $j): ?>
    <tr>
      <td>
        <div class="usuario">
          <img src="<?= htmlspecialchars($j['foto']) ?>" alt="Foto">
          <span><?= htmlspecialchars($j['usuario']) ?></span>
        </div>
      </td>
      <td><?= htmlspecialchars($j['fecha']) ?></td>

      <?php if (!empty($j['es_vacacion'])): ?>
        <td colspan="3" style="text-align: center;">
          <span>Vacaciones</span>
        </td>
      <?php else: ?>
        <td><?= htmlspecialchars($j['hora_inicio']) ?></td>
        <td><?= htmlspecialchars($j['hora_fin']) ?></td>
        <td>
          <?php if (!empty($j['descansos'])): ?>
            <ul>
              <?php foreach ($j['descansos'] as $d): ?>
                <li><?= htmlspecialchars($d['inicio']) ?> - <?= htmlspecialchars($d['fin']) ?></li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </td>
      <?php endif; ?>
    </tr>
  <?php endforeach; ?>
</tbody>

  </table>

  <script>
    window.onload = () => window.print();
  </script>
</body>
</html>
