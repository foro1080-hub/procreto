<?php
session_start();

// Si no hay sesión activa → redirigir a login
if (!isset($_SESSION['usuario'])) {
  header("Location: ../auth/login.php");
  exit();
}

// Redirigir según el rol
switch ($_SESSION['rol']) {
  case 'Super Admin':
  case 'Jefe de Planta':
  case 'RRHH':
  case 'SST':
  case 'Empleado':
    header("Location: ../screens/dashboard.php");
    break;

  default:
    header("Location: ../auth/login.php");
}
exit();
