<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once __DIR__ . "/../../../../../config/conexion.php";

// ===== Filtros =====
$busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : "";
$filtro_req = isset($_GET['filtro_req']) ? $_GET['filtro_req'] : ""; // "con" | "sin"

// ===== Paginación =====
$por_pagina = 20;
$pagina = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$inicio = ($pagina - 1) * $por_pagina;

// ===== Condición base =====
$where = "WHERE 1 ";

// Buscar por nombre o correo del usuario
if ($busqueda !== "") {
  $busqueda = $conn->real_escape_string($busqueda);
  $where .= " AND (
      CONCAT(u.nombres_usuario,' ',u.primer_apellido_usuario,' ',u.segundo_apellido_usuario) LIKE '%$busqueda%' 
      OR u.correo_usuario LIKE '%$busqueda%'
      OR u.numero_documento_usuario LIKE '%$busqueda%'
      OR EXISTS (
          SELECT 1 FROM requerimiento rq 
          WHERE rq.id_receptor_usuario = u.id_usuario 
          AND rq.descripcion_requerimiento LIKE '%$busqueda%'
      )
  )";
}

// Filtro: usuarios con o sin requerimientos
if ($filtro_req === "con") {
  $where .= " AND EXISTS (SELECT 1 FROM requerimiento rq WHERE rq.id_receptor_usuario = u.id_usuario)";
} elseif ($filtro_req === "sin") {
  $where .= " AND NOT EXISTS (SELECT 1 FROM requerimiento rq WHERE rq.id_receptor_usuario = u.id_usuario)";
}

// ===== Consulta principal =====
$sql = "
SELECT u.id_usuario,
       CONCAT(u.nombres_usuario,' ',u.primer_apellido_usuario,' ',u.segundo_apellido_usuario) AS nombre_completo,
       u.correo_usuario,
       COUNT(rq.id_requerimiento) AS total_requerimientos
FROM usuario u
LEFT JOIN requerimiento rq ON rq.id_receptor_usuario = u.id_usuario
$where
GROUP BY u.id_usuario
ORDER BY u.id_usuario DESC
LIMIT $inicio, $por_pagina
";

$result = $conn->query($sql);

// ===== Total para paginación =====
$total_sql = "SELECT COUNT(*) AS total FROM usuario u $where";
$total_result = $conn->query($total_sql);
$total = $total_result ? $total_result->fetch_assoc()['total'] : 0;
$total_paginas = ceil($total / $por_pagina);
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Gestión de Requerimientos | SST</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f5f7fa;
      font-family: 'Segoe UI', sans-serif;
    }

    .card {
      border-radius: 15px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    th {
      background: #007bff;
      color: white;
    }
  </style>
</head>

<body>
  <div class="container mt-5">
    <div class="card p-4 shadow-sm">

      <div class="d-flex justify-content-between align-items-center mb-3">

        <h3 class="mb-0">📌 Requerimientos SST</h3>
        <a href="./../index.php" class="btn btn-secondary">⬅️ Atrás</a>
      </div>

      <!-- Buscador + filtro -->
      <form method="GET" class="mb-3">
        <div class="row g-2">
          <div class="col-md-6">
            <input type="text" name="busqueda" class="form-control" placeholder="Buscar por nombre" value="<?= htmlspecialchars($busqueda) ?>">
          </div>
          <div class="col-md-4">
            <select name="filtro_req" class="form-select">
              <option value="">Todos</option>
              <option value="con" <?= $filtro_req === 'con' ? 'selected' : '' ?>>Usuarios con requerimiento</option>
              <option value="sin" <?= $filtro_req === 'sin' ? 'selected' : '' ?>>Usuarios sin requerimiento</option>
            </select>
          </div>
          <div class="col-md-2">
            <button type="submit" class="btn btn-outline-primary w-100">Filtrar</button>
          </div>
        </div>
      </form>

      <!-- Tabla -->
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Correo</th>
              <th>Total Requerimientos</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($result->num_rows > 0): ?>
              <?php while ($u = $result->fetch_assoc()): ?>
                <tr>
                  <td><?= htmlspecialchars($u['id_usuario']) ?></td>
                  <td><?= htmlspecialchars($u['nombre_completo']) ?></td>
                  <td><?= htmlspecialchars($u['correo_usuario']) ?></td>
                  <td>
                    <span class="badge bg-info"><?= $u['total_requerimientos'] ?></span>
                  </td>
                  <td>
                    <div class="dropdown">
                      <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">⚙️ Acciones</button>
                      <ul class="dropdown-menu">
                        <?php if ($u['total_requerimientos'] > 0): ?>
                          <li><a class="dropdown-item" href="ver.php?id_usuario=<?= $u['id_usuario'] ?>">👁️ Ver Requerimientos</a></li>
                        <?php endif; ?>
                        <li><a class="dropdown-item" href="crear.php?id_usuario=<?= $u['id_usuario'] ?>">➕ Asignar Requerimiento</a></li>

                      </ul>
                    </div>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" class="text-center text-muted">No se encontraron usuarios</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Paginación -->
      <nav>
        <ul class="pagination justify-content-center">
          <?php if ($pagina > 1): ?>
            <li class="page-item"><a class="page-link" href="?pagina=<?= $pagina - 1 ?>&busqueda=<?= urlencode($busqueda) ?>&filtro_req=<?= $filtro_req ?>">⬅️ Anterior</a></li>
          <?php endif; ?>
          <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
            <li class="page-item <?= $i == $pagina ? 'active' : '' ?>">
              <a class="page-link" href="?pagina=<?= $i ?>&busqueda=<?= urlencode($busqueda) ?>&filtro_req=<?= $filtro_req ?>"><?= $i ?></a>
            </li>
          <?php endfor; ?>
          <?php if ($pagina < $total_paginas): ?>
            <li class="page-item"><a class="page-link" href="?pagina=<?= $pagina + 1 ?>&busqueda=<?= urlencode($busqueda) ?>&filtro_req=<?= $filtro_req ?>">Siguiente ➡️</a></li>
          <?php endif; ?>
        </ul>
      </nav>

    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>