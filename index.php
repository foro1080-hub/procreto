<?php
session_start();

// Intentar incluir la conexión
$estado_conexion = false;
$mensaje_error = "";

try {
  include __DIR__ . "/config/conexion.php";
  if ($conn && $conn->ping()) {
    $estado_conexion = true;
  } else {
    $mensaje_error = "No se pudo establecer la conexión con la base de datos.";
  }
} catch (Exception $e) {
  $mensaje_error = "Error al conectar: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PRETECOR | Cargando...</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: "Segoe UI", sans-serif;
      background: linear-gradient(135deg, #007bff, #00c6ff);
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      color: #fff;
    }

    .splash-container {
      text-align: center;
      animation: fadeIn 1s ease-in-out;
    }

    .loader {
      border: 6px solid rgba(255, 255, 255, 0.3);
      border-top: 6px solid #fff;
      border-radius: 50%;
      width: 60px;
      height: 60px;
      margin: 20px auto;
      animation: spin 1s linear infinite;
    }

    h1 {
      font-size: 2rem;
      letter-spacing: 2px;
      margin-bottom: 10px;
    }

    p {
      font-size: 1.1rem;
    }

    .error {
      background: rgba(255, 255, 255, 0.1);
      padding: 20px;
      border-radius: 12px;
      color: #ffcccc;
      animation: fadeIn 0.8s ease-in-out;
    }

    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: scale(0.9);
      }

      to {
        opacity: 1;
        transform: scale(1);
      }
    }
  </style>

  <?php if ($estado_conexion): ?>
    <script>
      const tieneSesion = <?php echo isset($_SESSION['usuario']) ? 'true' : 'false'; ?>;

      setTimeout(() => {
        if (tieneSesion) {
          // Redirigir al dashboard
          window.location.href = "/pretecor/app/screens/dashboard/index.php";
        } else {
          // Redirigir al login
          window.location.href = "/pretecor/app";
        }
      }, 2000);
    </script>
  <?php endif; ?>

</head>

<body>
  <div class="splash-container">
    <h1>PRETECOR</h1>

    <?php if ($estado_conexion): ?>
      <div class="loader"></div>
      <p>Iniciando el sistema...</p>
    <?php else: ?>
      <div class="error">
        <h2>⚠ Error de Conexión</h2>
        <p><?php echo $mensaje_error; ?></p>
        <p>Por favor, inténtalo más tarde o contacta con el <strong>Soporte de Sistemas</strong>.</p>
      </div>
    <?php endif; ?>
  </div>
</body>

</html>