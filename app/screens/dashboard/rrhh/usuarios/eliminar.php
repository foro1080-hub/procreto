<?php
include_once __DIR__ . "/../../../../../config/conexion.php";

if (!isset($_GET['id'])) {
  die("Error: ID no especificado.");
}

$id = intval($_GET['id']);

// 1️⃣ Verificar si el usuario existe
$verificar = $conn->prepare("SELECT id_usuario FROM usuario WHERE id_usuario = ?");
$verificar->bind_param("i", $id);
$verificar->execute();
$result = $verificar->get_result();

if ($result->num_rows === 0) {
  die("Usuario no encontrado.");
}

// 🔹 Desactivar temporalmente las restricciones de clave foránea (opcional y seguro si se reactiva luego)
$conn->query("SET FOREIGN_KEY_CHECKS = 0");

// 2️⃣ Eliminar registros relacionados en `requerimiento`
$delete_reqs = $conn->prepare("DELETE FROM requerimiento WHERE id_emisor_usuario = ? OR id_receptor_usuario = ?");
$delete_reqs->bind_param("ii", $id, $id);
$delete_reqs->execute();

// 3️⃣ Eliminar registros relacionados en `usuario_limitacion`
$delete_limit = $conn->prepare("DELETE FROM usuario_limitacion WHERE id_usuario = ?");
$delete_limit->bind_param("i", $id);
$delete_limit->execute();

// 🔹 Si existen más tablas relacionadas, agrégalas aquí con el mismo formato
// Ejemplo:
// $delete_docs = $conn->prepare("DELETE FROM documento WHERE id_usuario = ?");
// $delete_docs->bind_param("i", $id);
// $delete_docs->execute();

// 4️⃣ Finalmente eliminar el usuario
$delete_user = $conn->prepare("DELETE FROM usuario WHERE id_usuario = ?");
$delete_user->bind_param("i", $id);

if ($delete_user->execute()) {
  // Reactivar las restricciones
  $conn->query("SET FOREIGN_KEY_CHECKS = 1");

  header("Location: index.php?msg=Usuario eliminado correctamente");
  exit;
} else {
  $conn->query("SET FOREIGN_KEY_CHECKS = 1");
  echo "<div class='alert alert-danger'>Error al eliminar el usuario: " . $conn->error . "</div>";
}
