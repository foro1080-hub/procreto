<?php
session_start();
if (!isset($_SESSION['usuario'])) {
  header("Location: ../../auth/login.php");
  exit;
}

$usuario = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Empleados | RRHH PROCRETO</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f4f6f8;
      margin: 0;
      padding: 20px;
    }

    a {
      color: #0a3d62;
      text-decoration: none;
    }
  </style>
</head>

<body>
  <h2>👥 Gestión de Empleados</h2>
  <p>Aquí irá el CRUD completo (listar, crear, editar, eliminar).</p>
  <a href="index.php">⬅ Volver al módulo RRHH</a>
</body>

</html>