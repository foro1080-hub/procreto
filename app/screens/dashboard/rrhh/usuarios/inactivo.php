<?php
include_once __DIR__ . "/../../../../../config/conexion.php";

if (!isset($_GET['id'])) {
  die("Error: ID no especificado.");
}

$id = intval($_GET['id']);

// Verificar si el usuario existe
$verificar = $conn->prepare("SELECT id_usuario FROM usuario WHERE id_usuario = ?");
$verificar->bind_param("i", $id);
$verificar->execute();
$result = $verificar->get_result();

if ($result->num_rows === 0) {
  die("Usuario no encontrado.");
}

// Marcar usuario como inactivo en lugar de eliminarlo
// ⚠️ Cambia el valor 2 por el ID real de "Inactivo" en tu tabla estado_usuario
$id_estado_inactivo = 2;

$update = $conn->prepare("UPDATE usuario SET id_estado_usuario = ? WHERE id_usuario = ?");
$update->bind_param("ii", $id_estado_inactivo, $id);

if ($update->execute()) {
  header("Location: index.php?msg=Usuario marcado como inactivo");
  exit;
} else {
  echo "<div class='alert alert-danger'>Error al cambiar el estado del usuario.</div>";
}
