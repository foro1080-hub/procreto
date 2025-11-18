<?php
include_once __DIR__ . "/../../../../../config/conexion.php";

// Verificar si se recibió el ID por GET
if (!isset($_GET['id'])) {
  die("Error: No se proporcionó un ID de usuario válido.");
}

$id = intval($_GET['id']);

// Obtener datos del usuario
$sql = "SELECT * FROM usuario WHERE id_usuario = $id";
$result = $conn->query($sql);
$usuario = $result->fetch_assoc();

if (!$usuario) {
  die("Usuario no encontrado.");
}

// Obtener listas para selects
$roles = $conn->query("SELECT * FROM rol");
$cargos = $conn->query("SELECT * FROM cargo");
$areas = $conn->query("SELECT * FROM area");
$estados = $conn->query("SELECT * FROM estado_usuario");
$turnos = $conn->query("SELECT * FROM turno");
$limitaciones = $conn->query("SELECT * FROM limitacion");
$estados_civiles = ["Soltero/a", "Casado/a", "Unión libre", "Separado/a", "Viudo/a"];

// Obtener limitación actual (si existe)
$limitacion_actual = $conn->query("SELECT id_limitacion FROM usuario_limitacion WHERE id_usuario = $id");
$id_limitacion_actual = $limitacion_actual->num_rows > 0 ? $limitacion_actual->fetch_assoc()['id_limitacion'] : null;

// Si el formulario se envió
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombres = $_POST['nombres_usuario'];
  $apellido1 = $_POST['primer_apellido_usuario'];
  $apellido2 = $_POST['segundo_apellido_usuario'];
  $correo = $_POST['correo_usuario'];
  $estado_civil = $_POST['estado_civil_usuario'];
  $fecha_retiro = $_POST['fecha_retiro_usuario'] ?: null;
  $id_rol = $_POST['id_rol'];
  $id_cargo = $_POST['id_cargo'];
  $id_area = $_POST['id_area'];
  $id_estado = $_POST['id_estado_usuario'];
  $id_turno = $_POST['id_turno'];
  $id_limitacion = $_POST['id_limitacion'] ?? null;

  // Actualizar datos del usuario
  $update = $conn->prepare("
    UPDATE usuario SET 
      nombres_usuario = ?, 
      primer_apellido_usuario = ?, 
      segundo_apellido_usuario = ?, 
      correo_usuario = ?, 
      estado_civil_usuario = ?, 
      fecha_retiro_usuario = ?, 
      id_rol = ?, 
      id_cargo = ?, 
      id_area = ?, 
      id_estado_usuario = ?, 
      id_turno = ?
    WHERE id_usuario = ?
  ");
  $update->bind_param(
    "ssssssiiiiii",
    $nombres,
    $apellido1,
    $apellido2,
    $correo,
    $estado_civil,
    $fecha_retiro,
    $id_rol,
    $id_cargo,
    $id_area,
    $id_estado,
    $id_turno,
    $id
  );

  if ($update->execute()) {
    // Actualizar relación con limitación
    $conn->query("DELETE FROM usuario_limitacion WHERE id_usuario = $id");
    if (!empty($id_limitacion)) {
      $insertLimit = $conn->prepare("INSERT INTO usuario_limitacion (id_usuario, id_limitacion) VALUES (?, ?)");
      $insertLimit->bind_param("ii", $id, $id_limitacion);
      $insertLimit->execute();
    }

    header("Location: index.php?msg=Usuario actualizado correctamente");
    exit;
  } else {
    echo "<div class='alert alert-danger'>Error al actualizar el usuario: " . $conn->error . "</div>";
  }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Editar Usuario</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

  <div class="container mt-5">
    <div class="card shadow p-4">
      <h3 class="mb-4">✏️ Editar Usuario</h3>

      <form method="POST">
        <div class="row mb-3">
          <div class="col-md-4">
            <label class="form-label">Nombres</label>
            <input type="text" name="nombres_usuario" class="form-control" value="<?= htmlspecialchars($usuario['nombres_usuario']) ?>" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Primer Apellido</label>
            <input type="text" name="primer_apellido_usuario" class="form-control" value="<?= htmlspecialchars($usuario['primer_apellido_usuario']) ?>" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Segundo Apellido</label>
            <input type="text" name="segundo_apellido_usuario" class="form-control" value="<?= htmlspecialchars($usuario['segundo_apellido_usuario']) ?>">
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Correo</label>
          <input type="email" name="correo_usuario" class="form-control" value="<?= htmlspecialchars($usuario['correo_usuario']) ?>" required>
        </div>

        <div class="row mb-3">
          <div class="col-md-4">
            <label class="form-label">Estado Civil</label>
            <select name="estado_civil_usuario" class="form-select" required>
              <?php foreach ($estados_civiles as $ec): ?>
                <option value="<?= htmlspecialchars($ec) ?>" <?= $usuario['estado_civil_usuario'] === $ec ? 'selected' : '' ?>>
                  <?= htmlspecialchars($ec) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-4">
            <label class="form-label">Fecha de Retiro</label>
            <input type="date" name="fecha_retiro_usuario" class="form-control" value="<?= htmlspecialchars($usuario['fecha_retiro_usuario']) ?>">
          </div>

          <div class="col-md-4">
            <label class="form-label">Turno</label>
            <select name="id_turno" class="form-select">
              <?php while ($t = $turnos->fetch_assoc()): ?>
                <option value="<?= $t['id_turno'] ?>" <?= $t['id_turno'] == $usuario['id_turno'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($t['nombre_turno']) ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-3">
            <label class="form-label">Rol</label>
            <select name="id_rol" class="form-select">
              <?php while ($r = $roles->fetch_assoc()): ?>
                <option value="<?= $r['id_rol'] ?>" <?= $r['id_rol'] == $usuario['id_rol'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($r['nombre_rol']) ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="col-md-3">
            <label class="form-label">Cargo</label>
            <select name="id_cargo" class="form-select">
              <?php while ($c = $cargos->fetch_assoc()): ?>
                <option value="<?= $c['id_cargo'] ?>" <?= $c['id_cargo'] == $usuario['id_cargo'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($c['nombre_cargo']) ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="col-md-3">
            <label class="form-label">Área</label>
            <select name="id_area" class="form-select">
              <?php while ($a = $areas->fetch_assoc()): ?>
                <option value="<?= $a['id_area'] ?>" <?= $a['id_area'] == $usuario['id_area'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($a['nombre_area']) ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="col-md-3">
            <label class="form-label">Estado</label>
            <select name="id_estado_usuario" class="form-select">
              <?php while ($e = $estados->fetch_assoc()): ?>
                <option value="<?= $e['id_estado_usuario'] ?>" <?= $e['id_estado_usuario'] == $usuario['id_estado_usuario'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($e['nombre_estado_usuario']) ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>
        </div>

        <div class="mb-4">
          <label class="form-label">Limitación</label>
          <select name="id_limitacion" class="form-select">
            <option value="">Sin limitación</option>
            <?php while ($l = $limitaciones->fetch_assoc()): ?>
              <option value="<?= $l['id_limitacion'] ?>" <?= $id_limitacion_actual == $l['id_limitacion'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($l['nombre_limitacion']) ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="d-flex justify-content-between">
          <a href="index.php" class="btn btn-secondary">⬅️ Cancelar</a>
          <button type="submit" class="btn btn-primary">💾 Guardar Cambios</button>
        </div>
      </form>
    </div>
  </div>

</body>

</html>