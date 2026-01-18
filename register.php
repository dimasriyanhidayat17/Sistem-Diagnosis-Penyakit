<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Register</title>
<style>
body {
  background: #020617;
  font-family: Arial, sans-serif;
  color: #e5e7eb;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}
.box {
  background: #020617;
  padding: 30px;
  border-radius: 12px;
  width: 360px;
  box-shadow: 0 0 25px rgba(250,204,21,0.2);
}
h2 {
  text-align: center;
  color: #facc15;
}
input {
  width: 100%;
  padding: 12px;
  margin: 10px 0;
  border-radius: 8px;
  border: none;
}
button {
  width: 100%;
  padding: 12px;
  background: #facc15;
  border: none;
  border-radius: 8px;
  font-weight: bold;
  cursor: pointer;
}
a {
  color: #facc15;
  text-decoration: none;
  display: block;
  text-align: center;
  margin-top: 15px;
}
</style>
</head>
<body>

<div class="box">
  <h2>📝 Register</h2>
  <form action="proses_register.php" method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="password" name="confirm" placeholder="Ulangi Password" required>
    <button type="submit">Daftar</button>
  </form>
  <a href="login.php">Sudah punya akun? Login</a>
</div>

</body>
</html>
