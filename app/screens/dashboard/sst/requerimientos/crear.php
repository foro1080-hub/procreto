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
$stmt = $conn->prepare("SELECT id_usuario, CONCAT(nombres_usuario,' ',primer_apellido_usuario,' ',segundo_apellido_usuario) AS nombre_completo, correo_usuario FROM usuario WHERE id_usuario = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if (!$usuario) {
  die("Usuario no encontrado");
}

// ===== Insertar requerimiento =====
$mensaje = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $titulo_requerimiento = trim($_POST['titulo_requerimiento'] ?? '');
  $descripcion_requerimiento = trim($_POST['descripcion_requerimiento'] ?? '');
  $fecha_limite = trim($_POST['fecha_limite'] ?? '');
  $estado_requerimiento = trim($_POST['estado_requerimiento'] ?? 'Pendiente');

  // Convertir vacíos a null
  $titulo_requerimiento = $titulo_requerimiento ?: null;
  $descripcion_requerimiento = $descripcion_requerimiento ?: null;
  $fecha_limite = $fecha_limite ?: null;
  $estado_requerimiento = $estado_requerimiento ?: 'Pendiente';

  $sql_insert = "INSERT INTO requerimiento (
      id_receptor_usuario,
      titulo_requerimiento,
      descripcion_requerimiento,
      estado_requerimiento,
      fecha_fin_requerimiento,
      fecha_creacion_requerimiento
    ) VALUES (?, ?, ?, ?, ?, NOW())";

  $stmt_insert = $conn->prepare($sql_insert);
  $stmt_insert->bind_param(
    "issss",
    $id_usuario,
    $titulo_requerimiento,
    $descripcion_requerimiento,
    $estado_requerimiento,
    $fecha_limite
  );

  if ($stmt_insert->execute()) {
    $mensaje = "<div class='alert alert-success'>✅ Requerimiento asignado correctamente.</div>";
  } else {
    $mensaje = "<div class='alert alert-danger'>❌ Error al guardar el requerimiento: " . htmlspecialchars($stmt_insert->error) . "</div>";
  }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Asignar Requerimiento | SST</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f5f7fa;
      font-family: 'Segoe UI', sans-serif;
    }

    .card {
      border-radius: 15px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      padding: 25px;
    }
  </style>
</head>

<body>
  <div class="container mt-5">
    <div class="card">
      <h3 class="mb-4">➕ Asignar Requerimiento a <?= htmlspecialchars($usuario['nombre_completo']) ?></h3>

      <a href="index.php" class="btn btn-secondary mb-3">⬅️ Volver</a>

      <?= $mensaje ?>

      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Título del requerimiento</label>
          <input type="text" name="titulo_requerimiento" class="form-control" placeholder="Ejemplo: Enviar reporte de seguridad" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Descripción</label>
          <textarea name="descripcion_requerimiento" class="form-control" rows="4" placeholder="Describe brevemente el requerimiento..."></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Fecha límite</label>
          <input type="date" name="fecha_limite" class="form-control">
        </div>

        <div class="mb-3">
          <label class="form-label">Estado</label>
          <select name="estado_requerimiento" class="form-select">
            <option value="Pendiente">Pendiente</option>
            <option value="Completado">Completado</option>
            <option value="Cancelado">Cancelado</option>
          </select>
        </div>

        <button type="submit" class="btn btn-primary w-100">💾 Guardar Requerimiento</button>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>