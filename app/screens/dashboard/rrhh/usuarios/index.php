<?php
include_once __DIR__ . "/../../../../../config/conexion.php";

// ======== 🔍 BUSCADOR ========
$busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : "";

// ======== 📄 PAGINACIÓN ========
$por_pagina = 20; // cantidad de registros por página
$pagina = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$inicio = ($pagina - 1) * $por_pagina;

// ======== 🧩 CONSULTA PRINCIPAL ========
$where = "";
if ($busqueda !== "") {
  $busqueda = $conn->real_escape_string($busqueda);
  $where = "WHERE 
    u.nombres_usuario LIKE '%$busqueda%' OR 
    u.primer_apellido_usuario LIKE '%$busqueda%' OR 
    u.segundo_apellido_usuario LIKE '%$busqueda%' OR 
    u.numero_documento_usuario LIKE '%$busqueda%'";
}

$sql = "
  SELECT 
    u.id_usuario,
    CONCAT(u.nombres_usuario, ' ', u.primer_apellido_usuario, ' ', u.segundo_apellido_usuario) AS nombre_completo,
    u.numero_documento_usuario,
    u.correo_usuario,
    r.nombre_rol AS rol,
    c.nombre_cargo AS cargo,
    a.nombre_area AS area,
    e.nombre_estado_usuario AS estado
  FROM usuario u
  LEFT JOIN rol r ON u.id_rol = r.id_rol
  LEFT JOIN cargo c ON u.id_cargo = c.id_cargo
  LEFT JOIN area a ON u.id_area = a.id_area
  LEFT JOIN estado_usuario e ON u.id_estado_usuario = e.id_estado_usuario
  $where
  ORDER BY u.id_usuario DESC
  LIMIT $inicio, $por_pagina
";

$result = $conn->query($sql);

// ======== 📊 TOTAL PARA PAGINACIÓN ========
$total_result = $conn->query("SELECT COUNT(*) as total FROM usuario u $where");
$total = $total_result->fetch_assoc()['total'];
$total_paginas = ceil($total / $por_pagina);
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Gestión de Usuarios</title>
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
        <a href="./../index.php" class="btn btn-secondary">⬅️ Atrás</a>
        <h3 class="mb-0">👥 Gestión de Usuarios</h3>
        <a href="crear.php" class="btn btn-primary">➕ Nuevo Usuario</a>
      </div>

      <!-- 🔍 BUSCADOR -->
      <form method="GET" class="mb-3">
        <div class="input-group">
          <input type="text" name="busqueda" class="form-control" placeholder="Buscar por nombre, apellido o documento..."
            value="<?= htmlspecialchars($busqueda) ?>">
          <button type="submit" class="btn btn-outline-primary">Buscar</button>
        </div>
      </form>

      <!-- 📋 TABLA -->
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre Completo</th>
              <th>Documento</th>
              <th>Correo</th>
              <th>Rol</th>
              <th>Área</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($result->num_rows > 0): ?>
              <?php while ($u = $result->fetch_assoc()): ?>
                <tr>
                  <td><?= htmlspecialchars($u['id_usuario']) ?></td>
                  <td><?= htmlspecialchars($u['nombre_completo']) ?></td>
                  <td><?= htmlspecialchars($u['numero_documento_usuario'] ?? '—') ?></td>
                  <td><?= htmlspecialchars($u['correo_usuario']) ?></td>
                  <td><?= htmlspecialchars($u['rol'] ?? '—') ?></td>
                  <td><?= htmlspecialchars($u['area'] ?? '—') ?></td>
                  <td>
                    <?php if (($u['estado'] ?? '') === 'Activo'): ?>
                      <span class="badge bg-success">Activo</span>
                    <?php else: ?>
                      <span class="badge bg-danger"><?= htmlspecialchars($u['estado'] ?? 'Inactivo') ?></span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <div class="dropdown">
                      <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        ⚙️ Acciones
                      </button>
                      <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="ver.php?id=<?= $u['id_usuario'] ?>">👁️ Ver</a></li>
                        <li><a class="dropdown-item" href="editar.php?id=<?= $u['id_usuario'] ?>">✏️ Editar</a></li>
                        <li><a class="dropdown-item" href="descargar_excel.php?id=<?= $u['id_usuario'] ?>">📊 Excel</a></li>
                        <li>
                          <hr class="dropdown-divider">
                        </li>
                        <li>
                          <a class="dropdown-item text-danger" href="eliminar.php?id=<?= $u['id_usuario'] ?>"
                            onclick="return confirm('¿Seguro de eliminar este usuario?')">🗑️ Eliminar</a>
                        </li>
                      </ul>
                    </div>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="8" class="text-center text-muted">No se encontraron usuarios</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- 📑 PAGINACIÓN -->
      <nav>
        <ul class="pagination justify-content-center">
          <?php if ($pagina > 1): ?>
            <li class="page-item"><a class="page-link" href="?pagina=<?= $pagina - 1 ?>&busqueda=<?= urlencode($busqueda) ?>">⬅️ Anterior</a></li>
          <?php endif; ?>
          <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
            <li class="page-item <?= $i == $pagina ? 'active' : '' ?>">
              <a class="page-link" href="?pagina=<?= $i ?>&busqueda=<?= urlencode($busqueda) ?>"><?= $i ?></a>
            </li>
          <?php endfor; ?>
          <?php if ($pagina < $total_paginas): ?>
            <li class="page-item"><a class="page-link" href="?pagina=<?= $pagina + 1 ?>&busqueda=<?= urlencode($busqueda) ?>">Siguiente ➡️</a></li>
          <?php endif; ?>
        </ul>
      </nav>

    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>