<?php
session_start();
$username = $_SESSION['username'] ?? 'Pengunjung';
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body {
  margin: 0;
  font-family: 'Segoe UI', sans-serif;
  background: #020617;
  color: #e5e7eb;
}

.navbar {
  background: #020617;
  padding: 15px 30px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #1e293b;
}

.navbar h2 {
  margin: 0;
  color: #facc15;
}

.navbar a {
  color: #facc15;
  text-decoration: none;
  font-weight: bold;
}

.container {
  padding: 40px;
}

.welcome {
  margin-bottom: 30px;
}

.cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 20px;
}

.card {
  background: #020617;
  border: 1px solid #1e293b;
  border-radius: 14px;
  padding: 25px;
  transition: 0.3s;
  cursor: pointer;
}

.card:hover {
  border-color: #facc15;
  transform: translateY(-5px);
}

.card h3 {
  margin-top: 0;
  color: #facc15;
}

.card p {
  color: #94a3b8;
  font-size: 14px;
}
</style>
</head>

<body>

<div class="navbar">
  <h2>🧠 Sistem Diagnosis Lambung</h2>
  <a href="auth/logout.php">Logout</a>
</div>

<div class="container">
  <div class="welcome">
    <h1>
Halo,
<?= isset($_SESSION['username']) ? $_SESSION['username'] : 'Pengunjung'; ?> 👋
</h1>

    <p>Selamat datang di dashboard sistem pakar & machine learning.</p>
  </div>

  <div class="cards">

    <div class="card" onclick="location.href='diagnosis.php'">
      <h3>🩺 Diagnosis Penyakit</h3>
      <p>Input gejala dan dapatkan hasil diagnosis menggunakan Forward Chaining & Naive Bayes.</p>
    </div>

    <div class="card" onclick="location.href='dataset_latih.php'">
      <h3>📊 Dataset Latih</h3>
      <p>Kelola data latih untuk perhitungan Naive Bayes.</p>
    </div>

    <div class="card" onclick="location.href='basis_pengetahuan.php'">
      <h3>📚 Basis Pengetahuan</h3>
      <p>basis_pengetahuan Forward Chaining (IF–THEN).</p>
    </div>

    <div class="card" onclick="location.href='riwayat_diagnosis.php'">
      <h3>🗂️ Riwayat Diagnosis</h3>
      <p>Lihat hasil diagnosis yang pernah dilakukan.</p>
    </div>

  </div>
</div>

</body>
</html>
