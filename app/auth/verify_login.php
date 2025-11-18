<?php
session_start();
require_once __DIR__ . '/../../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $documento = trim($_POST['documento']);
  $clave = trim($_POST['clave']);

  $sql = "SELECT * FROM usuario WHERE numero_documento_usuario = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "s", $documento);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $usuario = mysqli_fetch_assoc($result);

  if ($usuario) {
    $ultimos4 = substr($usuario['numero_documento_usuario'], -4);

    if ($clave === $ultimos4) {
      // Traer rol
      $rol_id = $usuario['id_rol'];
      $rol_query = "SELECT nombre_rol FROM rol WHERE id_rol = ?";
      $rol_stmt = mysqli_prepare($conn, $rol_query);
      mysqli_stmt_bind_param($rol_stmt, "i", $rol_id);
      mysqli_stmt_execute($rol_stmt);
      $rol_result = mysqli_stmt_get_result($rol_stmt);
      $rol = mysqli_fetch_assoc($rol_result);

      // Guardar sesión
      $_SESSION['usuario'] = [
        'id_usuario' => $usuario['id_usuario'],
        'nombre' => $usuario['nombres_usuario'],
        'rol' => $rol['nombre_rol']
      ];

      header("Location: ../screens/dashboard/index.php");
      exit;
    } else {
      $_SESSION['error'] = "Contraseña incorrecta.";
      header("Location: login.php");
      exit;
    }
  } else {
    $_SESSION['error'] = "Usuario no encontrado.";
    header("Location: login.php");
    exit;
  }
} else {
  header("Location: login.php");
  exit;
}
