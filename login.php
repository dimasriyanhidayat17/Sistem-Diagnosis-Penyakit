<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login Sistem</title>
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
.login-box {
  background: #020617;
  padding: 30px;
  border-radius: 12px;
  width: 350px;
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
</style>
</head>
<body>

<div class="login-box">
  <h2>🔐 Login Sistem</h2>
  <form action="proses_login.php" method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Masuk</button>
  </form>
  <a href="register.php">Belum punya akun? Register</a>

</div>

</body>
</html>
