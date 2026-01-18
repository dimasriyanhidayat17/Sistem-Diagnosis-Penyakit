<?php
include "../koneksi.php";

$username = $_POST['username'];
$password = $_POST['password'];
$confirm  = $_POST['confirm'];

if ($password !== $confirm) {
  echo "<script>
    alert('Password tidak sama!');
    window.location='register.php';
  </script>";
  exit;
}

// cek username
$cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
if (mysqli_num_rows($cek) > 0) {
  echo "<script>
    alert('Username sudah terdaftar!');
    window.location='register.php';
  </script>";
  exit;
}

// simpan user
$hash = md5($password);
mysqli_query($conn, "
  INSERT INTO users (username, password)
  VALUES ('$username', '$hash')
");

echo "<script>
  alert('Registrasi berhasil, silakan login!');
  window.location='login.php';
</script>";
?>
