<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_diagnosis"; // database login (boleh beda dengan diagnosis)

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
  die("Koneksi gagal: " . mysqli_connect_error());
}
?>
