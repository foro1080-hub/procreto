<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Login | PROCRETO</title>
  <link rel="stylesheet" href="/pretecor/app/assets/css/style.css">
</head>

<body class="login-body">
  <div class="login-container">
    <h2>Iniciar Sesión</h2>
    <form action="verify_login.php" method="POST">
      <input type="text" name="documento" placeholder="Cédula" required>
      <input type="password" name="clave" placeholder="Últimos 4 dígitos de la cédula" required>
      <button type="submit">Ingresar</button>
    </form>

    <?php if (isset($_SESSION['error'])): ?>
      <p style="color:red; margin-top:10px;">
        <?= htmlspecialchars($_SESSION['error']);
        unset($_SESSION['error']); ?>
      </p>
    <?php endif; ?>

    <p class="note">© PROCRETO 2025 | Sistema Interno</p>
  </div>
</body>

</html>