<?php
include_once __DIR__ . "/../../../../../config/conexion.php";

if (!isset($_GET['id'])) die("Error: ID no proporcionado.");

$id = intval($_GET['id']);
$sql = "SELECT u.*, r.nombre_rol, c.nombre_cargo, a.nombre_area, e.nombre_estado_usuario, t.nombre_turno
        FROM usuario u
        LEFT JOIN rol r ON u.id_rol = r.id_rol
        LEFT JOIN cargo c ON u.id_cargo = c.id_cargo
        LEFT JOIN area a ON u.id_area = a.id_area
        LEFT JOIN estado_usuario e ON u.id_estado_usuario = e.id_estado_usuario
        LEFT JOIN turno t ON u.id_turno = t.id_turno
        WHERE u.id_usuario = $id";

$result = $conn->query($sql);
$usuario = $result->fetch_assoc();

if (!$usuario) die("Usuario no encontrado.");
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Ver Usuario</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

  <div class="container mt-5">
    <div class="card shadow p-4">
      <h3 class="mb-4">👁️ Detalles del Usuario</h3>

      <table class="table table-bordered">
        <tr>
          <th>Nombres</th>
          <td><?= htmlspecialchars($usuario['nombres_usuario']) ?></td>
        </tr>
        <tr>
          <th>Primer Apellido</th>
          <td><?= htmlspecialchars($usuario['primer_apellido_usuario']) ?></td>
        </tr>
        <tr>
          <th>Segundo Apellido</th>
          <td><?= htmlspecialchars($usuario['segundo_apellido_usuario']) ?></td>
        </tr>
        <tr>
          <th>Correo</th>
          <td><?= htmlspecialchars($usuario['correo_usuario']) ?></td>
        </tr>
        <tr>
          <th>Rol</th>
          <td><?= htmlspecialchars($usuario['nombre_rol']) ?></td>
        </tr>
        <tr>
          <th>Cargo</th>
          <td><?= htmlspecialchars($usuario['nombre_cargo']) ?></td>
        </tr>
        <tr>
          <th>Área</th>
          <td><?= htmlspecialchars($usuario['nombre_area']) ?></td>
        </tr>
        <tr>
          <th>Estado</th>
          <td><?= htmlspecialchars($usuario['nombre_estado_usuario']) ?></td>
        </tr>
        <tr>
          <th>Turno</th>
          <td><?= htmlspecialchars($usuario['nombre_turno']) ?></td>
        </tr>
        <tr>
          <th>Fecha de ingreso</th>
          <td><?= htmlspecialchars($usuario['fecha_ingreso'] ?? '-') ?></td>
        </tr>
        <tr>
          <th>Fecha de retiro</th>
          <td><?= htmlspecialchars($usuario['fecha_retiro'] ?? '-') ?></td>
        </tr>
      </table>

      <div class="text-end">
        <a href="index.php" class="btn btn-secondary">⬅️ Volver</a>
        <a href="descargar_excel.php?id=<?= $usuario['id_usuario'] ?>" class="btn btn-success">📥 Descargar Excel</a>
      </div>
    </div>
  </div>

</body>

</html>