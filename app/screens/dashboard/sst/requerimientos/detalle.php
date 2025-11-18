<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once __DIR__ . "/../../../../../config/conexion.php";

// ===== Verificar ID del requerimiento =====
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  die("ID de requerimiento no válido");
}
$id_requerimiento = intval($_GET['id']);

// ===== Obtener información del requerimiento =====
$sql = "SELECT 
          rq.id_requerimiento,
          rq.titulo_requerimiento,
          rq.descripcion_requerimiento,
          rq.estado_requerimiento,
          rq.fecha_creacion_requerimiento,
          rq.fecha_fin_requerimiento,
          u.id_usuario,
          CONCAT(u.nombres_usuario,' ',u.primer_apellido_usuario,' ',u.segundo_apellido_usuario) AS nombre_usuario,
          u.correo_usuario
        FROM requerimiento rq
        INNER JOIN usuario u ON rq.id_receptor_usuario = u.id_usuario
        WHERE rq.id_requerimiento = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_requerimiento);
$stmt->execute();
$result = $stmt->get_result();
$req = $result->fetch_assoc();

if (!$req) {
  die("Requerimiento no encontrado");
}

// ===== Eliminar requerimiento =====
if (isset($_POST['eliminar'])) {
  $delete_sql = "DELETE FROM requerimiento WHERE id_requerimiento = ?";
  $stmt_del = $conn->prepare($delete_sql);
  $stmt_del->bind_param("i", $id_requerimiento);
  if ($stmt_del->execute()) {
    header("Location: ver.php?id_usuario=" . $req['id_usuario'] . "&msg=eliminado");
    exit;
  } else {
    echo "<div class='alert alert-danger'>❌ Error al eliminar el requerimiento.</div>";
  }
}

// ===== Exportar a Excel =====
if (isset($_GET['export']) && $_GET['export'] === 'excel') {
  header("Content-Type: application/vnd.ms-excel");
  header("Content-Disposition: attachment; filename=requerimiento_" . $id_requerimiento . ".xls");
  echo "<table border='1'>
          <tr><th colspan='2'>Detalle del Requerimiento</th></tr>
          <tr><td><b>ID:</b></td><td>{$req['id_requerimiento']}</td></tr>
          <tr><td><b>Titulo:</b></td><td>{$req['titulo_requerimiento']}</td></tr>
          <tr><td><b>Descripcinn:</b></td><td>{$req['descripcion_requerimiento']}</td></tr>
          <tr><td><b>Estado:</b></td><td>{$req['estado_requerimiento']}</td></tr>
          <tr><td><b>Fecha creacion:</b></td><td>{$req['fecha_creacion_requerimiento']}</td></tr>
          <tr><td><b>Fecha limite:</b></td><td>{$req['fecha_fin_requerimiento']}</td></tr>
          <tr><td><b>Usuario asignado:</b></td><td>{$req['nombre_usuario']} ({$req['correo_usuario']})</td></tr>
        </table>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Detalle del Requerimiento | SST</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8fafc;
      font-family: 'Segoe UI', sans-serif;
    }

    .card {
      border-radius: 15px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      padding: 25px;
    }

    .badge {
      font-size: 1rem;
    }

    .estado-pendiente {
      background-color: #fff3cd;
      color: #856404;
    }

    .estado-completado {
      background-color: #d4edda;
      color: #155724;
    }

    .estado-cancelado {
      background-color: #f8d7da;
      color: #721c24;
    }
  </style>
</head>

<body>
  <div class="container mt-5">
    <div class="card">
      <h3 class="mb-4">📄 Detalle del Requerimiento #<?= htmlspecialchars($req['id_requerimiento']) ?></h3>

      <div class="mb-3">
        <a href="ver.php?id_usuario=<?= $req['id_usuario'] ?>" class="btn btn-secondary">⬅️ Volver</a>
        <a href="?id=<?= $req['id_requerimiento'] ?>&export=excel" class="btn btn-success">📤 Exportar a Excel</a>
        <a href="editar.php?id=<?= $req['id_requerimiento'] ?>" class="btn btn-primary">✏️ Editar</a>
        <form method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar este requerimiento?');">
          <button type="submit" name="eliminar" class="btn btn-danger">🗑 Eliminar</button>
        </form>
      </div>

      <table class="table table-bordered">
        <tr>
          <th>Título</th>
          <td><?= htmlspecialchars($req['titulo_requerimiento']) ?></td>
        </tr>
        <tr>
          <th>Descripción</th>
          <td><?= nl2br(htmlspecialchars($req['descripcion_requerimiento'])) ?></td>
        </tr>
        <tr>
          <th>Estado</th>
          <td>
            <span class="badge 
              <?= strtolower($req['estado_requerimiento']) === 'pendiente' ? 'estado-pendiente' : (strtolower($req['estado_requerimiento']) === 'completado' ? 'estado-completado' : 'estado-cancelado') ?>">
              <?= htmlspecialchars($req['estado_requerimiento']) ?>
            </span>
          </td>
        </tr>
        <tr>
          <th>Fecha creación</th>
          <td><?= htmlspecialchars($req['fecha_creacion_requerimiento']) ?></td>
        </tr>
        <tr>
          <th>Fecha límite</th>
          <td><?= htmlspecialchars($req['fecha_fin_requerimiento'] ?: '—') ?></td>
        </tr>
        <tr>
          <th>Asignado a</th>
          <td><?= htmlspecialchars($req['nombre_usuario']) ?> (<?= htmlspecialchars($req['correo_usuario']) ?>)</td>
        </tr>
      </table>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>