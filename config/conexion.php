<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "procreto_db";

$conn = mysqli_connect($servername, $username, $password, $database);

// Validar conexión
if (!$conn) {
  include __DIR__ . "/../app/includes/connection_error.php";
  exit;
}
