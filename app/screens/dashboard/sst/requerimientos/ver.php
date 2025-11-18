<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once __DIR__ . "/../../../../../config/conexion.php";

// ===== Verificar si llega el ID del usuario =====
if (!isset($_GET['id_usuario']) || !is_numeric($_GET['id_usuario'])) {
  die("ID de usuario no válido");
}
$id_usuario = intval($_GET['id_usuario']);

// ===== Obtener información del usuario =====
$stmt = $conn->prepare("SELECT id_usuario, CONCAT(nombres_usuario,' ',primer_apellido_usuario,' ',segundo_apellido_usuario) AS nombre_completo FROM usuario WHERE id_usuario = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if (!$usuario) {
  die("Usuario no encontrado");
}

// ===== Obtener los requerimientos del usuario =====
$sql_req = "SELECT id_requerimiento, titulo_requerimiento, estado_requerimiento, fecha_creacion_requerimiento, fecha_fin_requerimiento 
            FROM requerimiento 
            WHERE id_receptor_usuario = ?
            ORDER BY fecha_creacion_requerimiento DESC";

$stmt_req = $conn->prepare($sql_req);
$stmt_req->bind_param("i", $id_usuario);
$stmt_req->execute();
$requerimientos = $stmt_req->get_result();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Requerimientos del Usuario | SST</title>
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

    .estado {
      font-weight: 600;
      border-radius: 8px;
      padding: 3px 8px;
    }

    .pendiente {
      background-color: #fff3cd;
      color: #856404;
    }

    .completado {
      background-color: #d4edda;
      color: #155724;
    }

    .cancelado {
      background-color: #f8d7da;
      color: #721c24;
    }
  </style>
</head>

<body>
  <div class="container mt-5">
    <div class="card">
      <h3 class="mb-4">📋 Requerimientos de <?= htmlspecialchars($usuario['nombre_completo']) ?></h3>
      <a href="index.php" class="btn btn-secondary mb-3">⬅️ Volver</a>

      <?php if ($requerimientos->num_rows > 0): ?>
        <div class="table-responsive">
          <table class="table table-striped align-middle">
            <thead class="table-dark">
              <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Estado</th>
                <th>Fecha creación</th>
                <th>Fecha límite</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $requerimientos->fetch_assoc()): ?>
                <tr>
                  <td><?= htmlspecialchars($row['id_requerimiento']) ?></td>
                  <td><?= htmlspecialchars($row['titulo_requerimiento']) ?></td>
                  <td>
                    <span class="estado 
                      <?= strtolower($row['estado_requerimiento']) === 'pendiente' ? 'pendiente' : (strtolower($row['estado_requerimiento']) === 'completado' ? 'completado' : 'cancelado') ?>">
                      <?= htmlspecialchars($row['estado_requerimiento']) ?>
                    </span>
                  </td>
                  <td><?= htmlspecialchars($row['fecha_creacion_requerimiento']) ?></td>
                  <td><?= htmlspecialchars($row['fecha_fin_requerimiento'] ?: '—') ?></td>
                  <td>
                    <a href="detalle.php?id=<?= $row['id_requerimiento'] ?>" class="btn btn-info btn-sm">👁 Ver detalle</a>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="alert alert-warning text-center">⚠️ No hay requerimientos asignados a este usuario.</div>
      <?php endif; ?>
    </div>
  </div>
</body>

</html>