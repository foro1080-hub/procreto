<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Error de conexión</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background: linear-gradient(135deg, #ff9a9e 0%, #fad0c4 100%);
      font-family: 'Poppins', sans-serif;
      color: #333;
    }

    .error-box {
      background: #fff;
      padding: 2rem 3rem;
      border-radius: 1rem;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
      text-align: center;
      max-width: 500px;
    }

    h1 {
      color: #c62828;
      font-weight: 700;
    }

    p {
      font-size: 1.1rem;
      margin-top: 0.5rem;
    }

    .btn-retry {
      margin-top: 1.5rem;
      background-color: #c62828;
      border: none;
    }

    .btn-retry:hover {
      background-color: #b71c1c;
    }
  </style>
</head>

<body>
  <div class="error-box">
    <h1>⚠ Error de Conexión</h1>
    <p>No fue posible conectarse con el servidor o la base de datos.</p>
    <p>Por favor, verifique su conexión o comuníquese con <strong>Soporte de Sistemas</strong>.</p>
    <a href="javascript:location.reload()" class="btn btn-retry btn-danger">Reintentar</a>
  </div>
</body>

</html>