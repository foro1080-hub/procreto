<?php
session_start();

// Validar sesión
if (!isset($_SESSION['usuario'])) {
  header("Location: ../auth/login.php");
  exit;
}

$usuario = $_SESSION['usuario'];
$rol = strtolower($usuario['rol']);
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Dashboard | PROCRETO</title>
  <link rel="stylesheet" href="../../assets/css/style.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f9fafc;
      margin: 0;
    }

    .main-header,
    .main-footer {
      background: #0a3d62;
      color: #fff;
      padding: 10px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .logo img {
      height: 40px;
      border-radius: 5px;
    }

    .container {
      padding: 20px;
    }

    .modules {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      margin-top: 30px;
    }

    .module-card {
      background: #fff;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
      transition: 0.2s;
    }

    .module-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 12px rgba(0, 0, 0, 0.15);
    }

    .module-card img {
      width: 100%;
      height: 140px;
      object-fit: cover;
      border-radius: 10px;
    }

    .module-card h3 {
      margin: 10px 0 5px;
      color: #0a3d62;
    }

    .module-card p {
      font-size: 0.95rem;
      color: #555;
    }
  </style>
</head>

<body>
  <header class="main-header">
    <div class="logo">
      <img src="https://fise.co/wp-content/uploads/2020/06/pretecor-1.jpg" alt="Procreto" />
      <h2>PROCRETO</h2>
    </div>
    <nav>
      <span>👤 <?= htmlspecialchars($usuario['nombre']) ?> (<?= ucfirst($rol) ?>)</span>
      <a href="../../auth/logout.php" style="color:#fff;text-decoration:none;">Cerrar sesión</a>
    </nav>
  </header>

  <main class="container">
    <h2>Panel de Control</h2>
    <p>Bienvenido al sistema interno de PROCRETO</p>

    <div class="modules">

      <?php if ($rol === 'super admin' || $rol === 'administración' || $rol === 'administracion'): ?>
        <a href="./rrhh/index.php" class="module-card">
          <img src="https://images.pexels.com/photos/3747455/pexels-photo-3747455.jpeg" alt="RRHH">
          <h3>Talento Humano</h3>
          <p>Gestión de personal, nómina y bienestar.</p>
        </a>

        <a href="./sst/index.php" class="module-card">
          <img src="https://images.pexels.com/photos/1108101/pexels-photo-1108101.jpeg" alt="SST">
          <h3>Seguridad y Salud en el Trabajo</h3>
          <p>Control de seguridad, capacitaciones e incidentes.</p>
        </a>

        <a href="./administracion/index.php" class="module-card">
          <img src="https://images.pexels.com/photos/53621/calculator-calculation-insurance-finance-53621.jpeg" alt="Admin">
          <h3>Administración General</h3>
          <p>Control contable, financiero y documental.</p>
        </a>
      <?php endif; ?>

      <?php if ($rol === 'sst'): ?>
        <a href="./sst/index.php" class="module-card">
          <img src="https://images.pexels.com/photos/1108101/pexels-photo-1108101.jpeg" alt="SST">
          <h3>Seguridad y Salud en el Trabajo</h3>
          <p>Control de seguridad, capacitaciones e incidentes.</p>
        </a>
      <?php endif; ?>

      <?php if ($rol === 'rrhh'): ?>
        <a href="./rrhh/index.php" class="module-card">
          <img src="https://images.pexels.com/photos/3747455/pexels-photo-3747455.jpeg" alt="RRHH">
          <h3>Talento Humano</h3>
          <p>Gestión de personal, nómina y bienestar.</p>
        </a>
      <?php endif; ?>

      <?php if ($rol === 'ingeniero'): ?>
        <a href="./ingenieria/index.php" class="module-card">
          <img src="https://images.pexels.com/photos/256381/pexels-photo-256381.jpeg" alt="Ingeniería">
          <h3>Panel de Ingeniería</h3>
          <p>Monitoreo de procesos y proyectos técnicos.</p>
        </a>
      <?php endif; ?>

      <?php if ($rol === 'jefe de planta' || $rol === 'empleado'): ?>
        <a href="./produccion/index.php" class="module-card">
          <img src="https://images.pexels.com/photos/257700/pexels-photo-257700.jpeg" alt="Producción">
          <h3>Producción</h3>
          <p>Control diario y seguimiento de operarios.</p>
        </a>
      <?php endif; ?>

    </div>
  </main>

  <footer class="main-footer">
    <p>© PROCRETO 2025 | Sistema Interno</p>
  </footer>
</body>

</html>