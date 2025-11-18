<?php
include_once __DIR__ . "/../../../../../config/conexion.php";

// Consultar datos para selects
$roles = $conn->query("SELECT * FROM rol");
$cargos = $conn->query("SELECT * FROM cargo");
$areas = $conn->query("SELECT * FROM area");
$estados = $conn->query("SELECT * FROM estado_usuario");
$turnos = $conn->query("SELECT * FROM turno");
$limitaciones = $conn->query("SELECT * FROM limitacion");

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Campos del formulario, con manejo de NULL
  $tipo_doc = $_POST['tipo_documento_usuario'] ?: null;
  $num_doc = $_POST['numero_documento_usuario'] ?: null;
  $nombres = $_POST['nombres_usuario'] ?: null;
  $apellido1 = $_POST['primer_apellido_usuario'] ?: null;
  $apellido2 = $_POST['segundo_apellido_usuario'] ?: null;
  $fecha_nac = $_POST['fecha_nacimiento_usuario'] ?: null;
  $genero = $_POST['genero_usuario'] ?: null;
  $telefono = $_POST['telefono_usuario'] ?: null;
  $correo = $_POST['correo_usuario'] ?: null;
  $direccion = $_POST['direccion_usuario'] ?: null;
  $ciudad = $_POST['ciudad_usuario'] ?: null;
  $estado_civil = $_POST['estado_civil_usuario'] ?: null;
  $tipo_contrato = $_POST['tipo_contrato_usuario'] ?: null;
  $fecha_ingreso = $_POST['fecha_ingreso_usuario'] ?: null;
  $pensiones = $_POST['pensiones_usuario'] ?: null;
  $eps = $_POST['eps_usuario'] ?: null;
  $arl = $_POST['arl_usuario'] ?: null;
  $profesion = $_POST['profesion_usuario'] ?: null;
  $nivel_estudio = $_POST['nivel_estudio_usuario'] ?: null;
  $observaciones = $_POST['observaciones_usuario'] ?: null;

  $id_estado = !empty($_POST['id_estado_usuario']) ? intval($_POST['id_estado_usuario']) : null;
  $id_cargo = !empty($_POST['id_cargo']) ? intval($_POST['id_cargo']) : null;
  $id_area = !empty($_POST['id_area']) ? intval($_POST['id_area']) : null;
  $id_rol = !empty($_POST['id_rol']) ? intval($_POST['id_rol']) : null;
  $id_turno = !empty($_POST['id_turno']) ? intval($_POST['id_turno']) : null;

  $limitaciones_sel = $_POST['limitaciones'] ?? [];

  if (empty($nombres) || empty($apellido1) || empty($correo)) {
    $error = "Por favor completa los campos obligatorios.";
  } else {
    $sql = "
            INSERT INTO usuario (
                tipo_documento_usuario, numero_documento_usuario,
                nombres_usuario, primer_apellido_usuario, segundo_apellido_usuario,
                fecha_nacimiento_usuario, genero_usuario, telefono_usuario,
                correo_usuario, direccion_usuario, ciudad_usuario,
                estado_civil_usuario, tipo_contrato_usuario, fecha_ingreso_usuario,
                pensiones_usuario, eps_usuario, arl_usuario,
                profesion_usuario, nivel_estudio_usuario, observaciones_usuario,
                id_estado_usuario, id_cargo, id_area, id_rol, id_turno
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
      "ssssssssssssssssssssiiiii",
      $tipo_doc,
      $num_doc,
      $nombres,
      $apellido1,
      $apellido2,
      $fecha_nac,
      $genero,
      $telefono,
      $correo,
      $direccion,
      $ciudad,
      $estado_civil,
      $tipo_contrato,
      $fecha_ingreso,
      $pensiones,
      $eps,
      $arl,
      $profesion,
      $nivel_estudio,
      $observaciones,
      $id_estado,
      $id_cargo,
      $id_area,
      $id_rol,
      $id_turno
    );

    if ($stmt->execute()) {
      $nuevo_id = $conn->insert_id;

      // Guardar limitaciones
      if (!empty($limitaciones_sel)) {
        $stmt_lim = $conn->prepare("INSERT INTO usuario_limitacion (id_usuario, id_limitacion) VALUES (?, ?)");
        foreach ($limitaciones_sel as $id_lim) {
          $stmt_lim->bind_param("ii", $nuevo_id, $id_lim);
          $stmt_lim->execute();
        }
      }

      header("Location: index.php?msg=Usuario creado correctamente");
      exit;
    } else {
      $error = "Error al crear el usuario: " . $conn->error;
    }
  }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Crear Usuario | SST</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
  <div class="container mt-5">
    <div class="card shadow p-4">
      <h3 class="mb-4">➕ Crear Nuevo Usuario</h3>

      <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="POST">
        <div class="row mb-3">
          <div class="col-md-3">
            <label class="form-label">Tipo Documento</label>
            <select name="tipo_documento_usuario" class="form-select">
              <option value="CC">CC</option>
              <option value="TI">TI</option>
              <option value="CE">CE</option>
              <option value="NIT">NIT</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Número Documento</label>
            <input type="text" name="numero_documento_usuario" class="form-control">
          </div>
          <div class="col-md-3">
            <label class="form-label">Nombres *</label>
            <input type="text" name="nombres_usuario" class="form-control" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Primer Apellido *</label>
            <input type="text" name="primer_apellido_usuario" class="form-control" required>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-3">
            <label class="form-label">Segundo Apellido</label>
            <input type="text" name="segundo_apellido_usuario" class="form-control">
          </div>
          <div class="col-md-3">
            <label class="form-label">Fecha Nacimiento</label>
            <input type="date" name="fecha_nacimiento_usuario" class="form-control">
          </div>
          <div class="col-md-3">
            <label class="form-label">Género</label>
            <select name="genero_usuario" class="form-select">
              <option value="">Seleccionar</option>
              <option value="Masculino">Masculino</option>
              <option value="Femenino">Femenino</option>
              <option value="Otro">Otro</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono_usuario" class="form-control">
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-4">
            <label class="form-label">Correo *</label>
            <input type="email" name="correo_usuario" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Dirección</label>
            <input type="text" name="direccion_usuario" class="form-control">
          </div>
          <div class="col-md-4">
            <label class="form-label">Ciudad</label>
            <input type="text" name="ciudad_usuario" class="form-control">
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-3">
            <label class="form-label">Estado Civil</label>
            <select name="estado_civil_usuario" class="form-select">
              <option value="">Seleccionar</option>
              <option value="Soltero(a)">Soltero(a)</option>
              <option value="Casado(a)">Casado(a)</option>
              <option value="Unión Libre">Unión Libre</option>
              <option value="Divorciado(a)">Divorciado(a)</option>
              <option value="Viudo(a)">Viudo(a)</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Estado</label>
            <select name="id_estado_usuario" class="form-select">
              <?php while ($e = $estados->fetch_assoc()): ?>
                <option value="<?= $e['id_estado_usuario'] ?>"><?= htmlspecialchars($e['nombre_estado_usuario']) ?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Rol</label>
            <select name="id_rol" class="form-select">
              <?php while ($r = $roles->fetch_assoc()): ?>
                <option value="<?= $r['id_rol'] ?>"><?= htmlspecialchars($r['nombre_rol']) ?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Cargo</label>
            <select name="id_cargo" class="form-select">
              <?php while ($c = $cargos->fetch_assoc()): ?>
                <option value="<?= $c['id_cargo'] ?>"><?= htmlspecialchars($c['nombre_cargo']) ?></option>
              <?php endwhile; ?>
            </select>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-3">
            <label class="form-label">Área</label>
            <select name="id_area" class="form-select">
              <?php while ($a = $areas->fetch_assoc()): ?>
                <option value="<?= $a['id_area'] ?>"><?= htmlspecialchars($a['nombre_area']) ?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Tipo Contrato</label>
            <input type="text" name="tipo_contrato_usuario" class="form-control">
          </div>
          <div class="col-md-3">
            <label class="form-label">Fecha Ingreso</label>
            <input type="date" name="fecha_ingreso_usuario" class="form-control">
          </div>
          <div class="col-md-3">
            <label class="form-label">Turno</label>
            <select name="id_turno" class="form-select">
              <?php while ($t = $turnos->fetch_assoc()): ?>
                <option value="<?= $t['id_turno'] ?>"><?= htmlspecialchars($t['nombre_turno']) ?></option>
              <?php endwhile; ?>
            </select>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-3">
            <label class="form-label">Pensiones</label>
            <input type="text" name="pensiones_usuario" class="form-control">
          </div>
          <div class="col-md-3">
            <label class="form-label">EPS</label>
            <input type="text" name="eps_usuario" class="form-control">
          </div>
          <div class="col-md-3">
            <label class="form-label">ARL</label>
            <input type="text" name="arl_usuario" class="form-control">
          </div>
          <div class="col-md-3">
            <label class="form-label">Profesión</label>
            <input type="text" name="profesion_usuario" class="form-control">
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-4">
            <label class="form-label">Nivel de Estudio</label>
            <input type="text" name="nivel_estudio_usuario" class="form-control">
          </div>
          <div class="col-md-8">
            <label class="form-label">Observaciones</label>
            <textarea name="observaciones_usuario" class="form-control" rows="1"></textarea>
          </div>
        </div>

        <!-- Limitaciones -->
        <div class="mb-3">
          <label class="form-label fw-bold">Limitaciones (seleccione una o varias)</label>
          <div class="border rounded p-3" style="max-height: 150px; overflow-y:auto;">
            <?php while ($lim = $limitaciones->fetch_assoc()): ?>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="limitaciones[]" value="<?= $lim['id_limitacion'] ?>" id="lim_<?= $lim['id_limitacion'] ?>">
                <label class="form-check-label" for="lim_<?= $lim['id_limitacion'] ?>">
                  <?= htmlspecialchars($lim['nombre_limitacion']) ?>
                </label>
              </div>
            <?php endwhile; ?>
          </div>
        </div>

        <div class="d-flex justify-content-between">
          <a href="index.php" class="btn btn-secondary">⬅️ Atrás</a>
          <button type="submit" class="btn btn-success">💾 Guardar Usuario</button>
        </div>
      </form>
    </div>
  </div>
</body>

</html>