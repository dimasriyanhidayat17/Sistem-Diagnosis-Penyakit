<?php
session_start();
include "koneksi.php";
$username = $_SESSION['username'] ?? 'Pengunjung';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Riwayat Diagnosis</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body {
  margin:0;
  font-family:'Segoe UI',sans-serif;
  background:#020617;
  color:#e5e7eb;
}
.navbar {
  padding:15px 30px;
  border-bottom:1px solid #1e293b;
}
.navbar a {
  color:#facc15;
  text-decoration:none;
  font-weight:bold;
}
.container { padding:40px; }
h1 { color:#facc15; }
.box {
  border:1px solid #1e293b;
  border-radius:14px;
  padding:20px;
  margin-bottom:20px;
}
.small { color:#94a3b8; font-size:13px; }
</style>
</head>

<body>

<div class="navbar">
  <a href="dashboard.php">⬅ Dashboard</a>
</div>

<div class="container">
<h1>🗂️ Riwayat Diagnosis</h1>

<?php
$q = mysqli_query($conn, "
  SELECT * FROM riwayat_diagnosis
  WHERE username='$username'
  ORDER BY created_at DESC
");

if (mysqli_num_rows($q) == 0) {
  echo "<p><i>Belum ada riwayat diagnosis.</i></p>";
}

while ($r = mysqli_fetch_assoc($q)) {
  $hasil_nb = json_decode($r['hasil_nb'], true);

  echo "<div class='box'>";
  echo "<b>🕒 {$r['created_at']}</b><br><br>";

  echo "<b>Gejala:</b><br>{$r['gejala']}<br><br>";
  echo "<b>Forward Chaining:</b><br>{$r['hasil_fc']}<br><br>";

  echo "<b>Naive Bayes:</b><br>";
  foreach ($hasil_nb as $p => $v) {
    echo "$p : ".round($v,2)." %<br>";
  }

  echo "<br><b>📌 Diagnosis Akhir:</b> {$r['diagnosis_akhir']}";
  echo "</div>";
}
?>

</div>
</body>
</html>
