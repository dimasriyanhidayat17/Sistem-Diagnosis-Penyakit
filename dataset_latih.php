<?php
session_start();
include "koneksi.php";

$username = $_SESSION['username'] ?? 'Pengunjung';
$input_gejala = $_SESSION['input_gejala'] ?? [];
$hasil_nb_raw = $_SESSION['hasil_nb_raw'] ?? [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dataset Latih & Naive Bayes</title>
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
  margin-bottom:30px;
}
table {
  width:100%;
  border-collapse:collapse;
  font-size:13px;
}
th,td {
  border:1px solid #334155;
  padding:6px;
  text-align:center;
}
th { background:#020617; color:#facc15; }
.highlight { color:#22c55e; font-weight:bold; }
</style>
</head>

<body>

<div class="navbar">
  <a href="dashboard.php">⬅ Dashboard</a>
</div>

<div class="container">

<h1>📊 Dataset Latih & Perhitungan Naive Bayes</h1>
<p>Halo, <?= $username; ?>. Halaman ini menampilkan dataset latih dan proses perhitungan Naive Bayes.</p>

<!-- ===============================
     PENJELASAN NAIVE BAYES
     =============================== -->
<div class="box">
<h2>🤖 Metode Naive Bayes</h2>
<p>
Naive Bayes adalah metode klasifikasi probabilistik yang menghitung kemungkinan suatu
penyakit berdasarkan data latih dan gejala yang dipilih pengguna.
</p>
<p>
Rumus utama:
<br>
<b>P(C|X) = P(C) × Π P(Xi|C)</b>
</p>
</div>

<!-- ===============================
     DATASET LATIH
     =============================== -->
<div class="box">
<h2>📋 Dataset Latih yang Digunakan</h2>

<table>
<tr>
  <th>Penyakit</th>
  <?php for ($i=1; $i<=25; $i++) echo "<th>G$i</th>"; ?>
</tr>

<?php
$q = mysqli_query($conn, "SELECT * FROM dataset_nb");
while ($r = mysqli_fetch_assoc($q)) {
  echo "<tr>";
  echo "<td>{$r['penyakit']}</td>";
  for ($i=1; $i<=25; $i++) {
    echo "<td>{$r['G'.$i]}</td>";
  }
  echo "</tr>";
}
?>
</table>
</div>

<!-- ===============================
     PERHITUNGAN NAIVE BAYES
     =============================== -->
<div class="box">
<h2>🧮 Proses Perhitungan Naive Bayes</h2>

<?php if (empty($hasil_nb_raw)) : ?>
  <p><i>Belum ada proses diagnosis yang dijalankan.</i></p>
<?php else: ?>

<p><b>Gejala yang dipilih:</b> <?= implode(", ", $input_gejala); ?></p>

<?php
$total_data = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM dataset_nb"));

foreach ($hasil_nb_raw as $penyakit => $nilai_akhir):

  $dataP = mysqli_query($conn, "SELECT * FROM dataset_nb WHERE penyakit='$penyakit'");
  $jumlah_penyakit = mysqli_num_rows($dataP);
  $prior = $jumlah_penyakit / $total_data;

  echo "<hr>";
  echo "<b>$penyakit</b><br>";
  echo "Prior P($penyakit) = $jumlah_penyakit / $total_data = ".round($prior,4)."<br>";

  foreach ($input_gejala as $g) {
    $count = 0;
    mysqli_data_seek($dataP,0);

    while ($row = mysqli_fetch_assoc($dataP)) {
      if ($row[$g] == 1) $count++;
    }

    $likelihood = ($count + 1) / ($jumlah_penyakit + 2);
    echo "P($g | $penyakit) = ($count + 1) / ($jumlah_penyakit + 2) = ".round($likelihood,4)."<br>";
  }

  echo "<span class='highlight'>Probabilitas Akhir = ".round($nilai_akhir,8)."</span><br>";

endforeach;
?>

<?php endif; ?>
</div>

</div>
</body>
</html>
