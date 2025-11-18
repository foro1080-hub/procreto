<?php
session_start();

// Validar sesión
if (!isset($_SESSION['usuario'])) {
  header("Location: ../../auth/login.php");
  exit;
}

$usuario = $_SESSION['usuario'];
$rol = strtolower(trim($usuario['rol']));

if ($rol !== 'rrhh' && $rol !== 'super admin' && $rol !== 'administración' && $rol !== 'administracion') {
  header("Location: ../index.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Talento Humano | PROCRETO</title>
  <link rel="stylesheet" href="../../assets/css/style.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f4f6f8;
      margin: 0;
    }

    header {
      background: #0a3d62;
      color: #fff;
      padding: 15px 25px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    header h2 {
      margin: 0;
    }

    .container {
      padding: 30px;
    }

    .module-title {
      text-align: center;
      margin-bottom: 30px;
    }

    .cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
    }

    .card {
      background: #fff;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
      transition: 0.2s;
      text-align: center;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card h3 {
      color: #0a3d62;
    }

    .card p {
      color: #555;
      font-size: 0.95rem;
      margin: 10px 0 15px;
    }

    .card a {
      display: inline-block;
      background: #0a3d62;
      color: #fff;
      padding: 8px 15px;
      border-radius: 8px;
      text-decoration: none;
      transition: background 0.2s;
    }

    .card a:hover {
      background: #1e6ba1;
    }

    footer {
      background: #0a3d62;
      color: #fff;
      text-align: center;
      padding: 10px;
      margin-top: 40px;
    }
  </style>
</head>

<body>

  <header>
    <div style="display: flex; align-items: center; gap: 15px;">
      <a href="../index.php" style="
      background: #fff;
      color: #0a3d62;
      padding: 6px 12px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: bold;
      transition: 0.2s;
    ">&larr; Volver</a>
      <h2>Talento Humano | PROCRETO</h2>
    </div>
    <nav>
      <span>👤 <?= htmlspecialchars($usuario['nombre']) ?> (<?= ucfirst($rol) ?>)</span> |
      <a href="../../../auth/logout.php" style="color:#fff;text-decoration:none;">Cerrar sesión</a>
    </nav>
  </header>


  <main class="container">
    <h1 class="module-title">Módulo de Talento Humano</h1>

    <div class="cards">
      <div class="card">
        <h3>👥 Gestión de empleados</h3>
        <p>Consulta, registra y actualiza la información del personal.</p>
        <a href="usuarios/index.php">Ir al módulo</a>
      </div>

      <div class="card">
        <h3>📆 Control de asistencia</h3>
        <p>Revisa los registros de entrada, salida y ausencias.</p>
        <a href="#">Próximamente</a>
      </div>

      <div class="card">
        <h3>💰 Nómina y vacaciones</h3>
        <p>Gestiona vacaciones, licencias y pagos del personal.</p>
        <a href="#">Próximamente</a>
      </div>
    </div>
  </main>

  <footer>
    <p>© PROCRETO 2025 | Módulo RRHH</p>
  </footer>

</body>

</html>