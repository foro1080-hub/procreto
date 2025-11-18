<?php
// Validación por seguridad
if (!isset($rol) || !is_array($rol)) {
  echo "<!-- Error: variable \$rol no definida correctamente -->";
  return;
}

// Normaliza nombre del rol
$nombreRol = strtolower(trim($rol['nombre_rol'] ?? ''));

// Si la base de datos tiene imagen, úsala directamente
if (!empty($rol['imagen_url'])) {
  $imagen = $rol['imagen_url'];
} else {
  // Si está vacía, usar respaldo por nombre
  switch ($nombreRol) {
    case 'super admin':
    case 'administrador':
      $imagen = 'https://images.pexels.com/photos/546819/pexels-photo-546819.jpeg'; // oficina
      break;
    default:
      $imagen = 'https://fise.co/wp-content/uploads/2020/06/pretecor-1.jpg'; // genérica
      break;
  }
}
?>

<div class="card">
  <img src="<?= htmlspecialchars($imagen) ?>" alt="Imagen del rol <?= htmlspecialchars($rol['nombre_rol']) ?>">
  <div class="card-body">
    <h3><?= htmlspecialchars($rol['nombre_rol']) ?></h3>
    <p><?= !empty($rol['descripcion_rol'])
          ? htmlspecialchars($rol['descripcion_rol'])
          : 'En mantenimiento...' ?></p>
    <a href="/pretecor/app/auth/login.php" class="btn">Ingresar</a>

  </div>
</div>