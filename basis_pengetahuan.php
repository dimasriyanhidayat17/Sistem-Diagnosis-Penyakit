<?php
session_start();
include "koneksi.php";
$username = $_SESSION['username'] ?? 'Pengunjung';

// ambil log terakhir (pakai session dari diagnosis)
$log_fc = $_SESSION['log_fc'] ?? [];
$input_gejala = $_SESSION['input_gejala'] ?? [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Basis Pengetahuan - Forward Chaining</title>
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
h1,h2 { color:#facc15; }
.box {
  border:1px solid #1e293b;
  border-radius:14px;
  padding:20px;
  margin-bottom:25px;
}
.rule {
  padding:10px;
  border-bottom:1px dashed #334155;
}
.match { color:#22c55e; }
.nomatch { color:#ef4444; }
</style>
</head>

<body>

<div class="navbar">
  <a href="dashboard.php">⬅ Dashboard</a>
</div>

<div class="container">

<h1>📚 Basis Pengetahuan Sistem Pakar</h1>
<p>Halo, <?= $username; ?>. Halaman ini menjelaskan aturan dan proses Forward Chaining.</p>

<!-- ===============================
     PENJELASAN FC
     =============================== -->
<div class="box">
<h2>🔎 Metode Forward Chaining</h2>
<p>
Forward Chaining adalah metode inferensi pada sistem pakar yang bekerja
dengan cara mencocokkan fakta (gejala) yang diberikan pengguna dengan aturan
IF–THEN dalam basis pengetahuan untuk menarik kesimpulan berupa penyakit.
</p>
</div>

<!-- ===============================
     ATURAN IF-THEN
     =============================== -->
<div class="box">
<h2>📌 Aturan (IF–THEN) yang Digunakan</h2>

<?php
$query = mysqli_query($conn,"
  SELECT penyakit.nama, aturan_fc.gejala
  FROM aturan_fc
  JOIN penyakit ON aturan_fc.penyakit_id = penyakit.id
");

while ($r = mysqli_fetch_assoc($query)) {
  echo "<div class='rule'>";
  echo "<b>IF</b> ".$r['gejala']." <b>THEN</b> ".$r['nama'];
  echo "</div>";
}
?>
</div>

<!-- ===============================
     LOG PROSES FC
     =============================== -->
<div class="box">
<h2>🧠 Log Proses Forward Chaining</h2>

<?php if (empty($log_fc)) : ?>
  <p><i>Belum ada proses diagnosis yang dijalankan.</i></p>
<?php else: ?>

<p><b>Gejala yang dipilih:</b> <?= implode(", ", $input_gejala); ?></p>

<?php foreach ($log_fc as $log): ?>
  <div class="rule <?= $log['status'] ? 'match' : 'nomatch'; ?>">
    Rule <?= $log['penyakit']; ?> →
    <?= $log['status'] ? 'TERPENUHI' : 'TIDAK TERPENUHI'; ?>
  </div>
<?php endforeach; ?>

<?php endif; ?>
</div>

</div>
</body>
</html>
