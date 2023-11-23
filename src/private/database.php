<?php
require_once("credentials.php");

function db_connect() {
  $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME, DB_PORT);
  return $connection;
}

function db_disconnect($connection) {
  if (isset($connection)) {
    mysqli_close($connection);
  }
}

function db_escape($string) {
  global $db;
  return mysqli_real_escape_string($db, $string); // Helps prevent SQL injection attacks
}
 ?>
