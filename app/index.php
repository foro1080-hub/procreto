<?php
include __DIR__ . "/../config/conexion.php";
include "./includes/header.php";
include "./includes/navbar.php";
?>
<main>
  <h2>Bienvenido al Sistema Interno de PROCRETO</h2>
  <p>Seleccione su módulo según su rol:</p>

  <section class="cards-container">
    <?php
    $sql = "SELECT * FROM rol";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
      while ($rol = mysqli_fetch_assoc($result)) {
        include __DIR__ . "/components/card-user.php";
      }
    } else {
      echo "<p>No hay roles disponibles.</p>";
    }
    ?>
  </section>
</main>

<?php include __DIR__ . "/includes/footer.php"; ?>