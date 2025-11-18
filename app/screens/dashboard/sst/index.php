<?php
session_start();

// Validar sesión
if (!isset($_SESSION['usuario'])) {
  header("Location: ../../auth/login.php");
  exit;
}

$usuario = $_SESSION['usuario'];
$rol = strtolower(trim($usuario['rol']));

// Roles permitidos: SST, Super Admin, Jefe de Planta
$roles_permitidos = ['sst', 'super admin', 'jefe de planta'];

if (!in_array($rol, $roles_permitidos)) {
  header("Location: ../index.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Módulo SST | PROCRETO</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
      <a class="navbar-brand" href="../index.php">&larr; PROCRETO</a>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <span class="navbar-text text-white">
              👤 <?= htmlspecialchars($usuario['nombre']) ?> (<?= ucfirst($rol) ?>)
            </span>
          </li>
          <li class="nav-item ms-3">
            <a class="nav-link text-white" href="../../../auth/logout.php">Cerrar sesión</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container my-5">
    <h1 class="text-center mb-4">Módulo de Seguridad y Salud en el Trabajo</h1>

    <div class="row g-4">
      <!-- Gestión de empleados -->
      <div class="col-md-4">
        <div class="card h-100 shadow-sm">
          <div class="card-body text-center">
            <h5 class="card-title">👥 Gestión de empleados</h5>
            <p class="card-text">Consulta la información del personal.</p>
            <a href="usuarios/index.php" class="btn btn-primary">Ir al módulo</a>
          </div>
        </div>
      </div>

      <!-- Asignación de requerimientos -->
      <div class="col-md-4">
        <div class="card h-100 shadow-sm">
          <div class="card-body text-center">
            <h5 class="card-title">📝 Asignar requerimientos</h5>
            <p class="card-text">Asigna requerimientos o seguimientos a empleados.</p>
            <a href="requerimientos/index.php" class="btn btn-primary">Ir al módulo</a>
          </div>
        </div>
      </div>

      <!-- Reportes e incidencias -->
      <div class="col-md-4">
        <div class="card h-100 shadow-sm">
          <div class="card-body text-center">
            <h5 class="card-title">📋 Reportes de incidentes</h5>
            <p class="card-text">Registra y revisa incidentes de seguridad o salud laboral.</p>
            <a href="empleados.php" class="btn btn-primary">Ir al módulo</a>
          </div>
        </div>
      </div>

      <!-- Capacitaciones -->
      <div class="col-md-4">
        <div class="card h-100 shadow-sm">
          <div class="card-body text-center">
            <h5 class="card-title">🧠 Capacitaciones</h5>
            <p class="card-text">Registra y gestiona capacitaciones de seguridad y salud.</p>
            <a href="empleados.php" class="btn btn-primary">Ir al módulo</a>
          </div>
        </div>
      </div>

      <!-- Seguimiento condiciones laborales -->
      <div class="col-md-4">
        <div class="card h-100 shadow-sm">
          <div class="card-body text-center">
            <h5 class="card-title">🛡 Seguimiento laboral</h5>
            <p class="card-text">Revisa condiciones de trabajo y medidas de prevención.</p>
            <a href="empleados.php" class="btn btn-primary">Ir al módulo</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <footer class="bg-primary text-white text-center py-3 mt-5">
    © PROCRETO 2025 | Módulo SST
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>