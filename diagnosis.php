<?php
session_start();
$username = $_SESSION['username'] ?? 'Pengunjung';
$input = $_POST['G'] ?? [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Diagnosis Penyakit Lambung</title>
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
  border-bottom: 1px solid #1e293b;
}
.navbar a {
  color: #facc15;
  text-decoration: none;
  font-weight: bold;
}
.container { padding: 40px; }
h1 { color: #facc15; }
.grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px,1fr));
  gap: 12px;
}
label {
  border: 1px solid #1e293b;
  border-radius: 10px;
  padding: 10px;
  cursor: pointer;
}
label.active {
  border-color: #22c55e;
  background: rgba(34,197,94,0.08);
}
button {
  margin-top: 25px;
  padding: 14px 25px;
  background: #facc15;
  border: none;
  border-radius: 10px;
  font-weight: bold;
  cursor: pointer;
}
.reset-btn {
  margin-left:10px;
  padding:14px 20px;
  background:#1e293b;
  color:#e5e7eb;
  border-radius:10px;
  text-decoration:none;
  font-weight:bold;
}
.box {
  margin-top: 30px;
  border: 1px solid #1e293b;
  padding: 20px;
  border-radius: 14px;
}
.note {
  margin-top:10px;
  font-size:13px;
  color:#94a3b8;
}
</style>
</head>

<body>

<div class="navbar">
  <a href="dashboard.php">⬅ Dashboard</a>
</div>

<div class="container">
<h1>🩺 Diagnosis Penyakit Lambung</h1>
<p>Halo, <?= $username; ?> — pilih gejala yang Anda alami.</p>

<form method="POST">
<div class="grid">
<?php
$gejala = [
  "Perih atau rasa terbakar di lambung",
  "Mual sebelum / sesudah makan",
  "Perut terasa penuh (begah)",
  "Rasa tidak nyaman di ulu hati",
  "Dada terasa panas",
  "Muntah berulang",
  "BAB berwarna hitam",
  "Muntah berdarah",
  "Cepat merasa kenyang",
  "Sendawa berlebihan",
  "Nyeri perut bagian atas",
  "Berat badan menurun",
  "Rasa pahit / asam di mulut",
  "BAB berdarah",
  "Demam",
  "Diare cair",
  "Nafsu makan menurun",
  "Perut melilit",
  "Nyeri perut terus menerus",
  "Perut sering kembung",
  "Kram perut",
  "Dehidrasi",
  "Anemia",
  "Sulit menelan",
  "Bau mulut"
];

foreach ($gejala as $i => $g) {
  $kode = 'G'.($i+1);
  $checked = in_array($kode, $input);
  echo "<label class='".($checked?'active':'')."'>
    <input type='checkbox' name='G[]' value='$kode' ".($checked?'checked':'').">
    $kode - $g
  </label>";
}
?>
</div>

<button type="submit" name="proses">🔍 Proses Diagnosis</button>
<a href="diagnosis.php" class="reset-btn">🔄 Reset Gejala</a>
</form>

<?php
/* =================================================
   PROSES DIAGNOSIS
   ================================================= */
if (isset($_POST['proses'])) {

  if (count($input) === 0) {
    echo "<p><b>Pilih minimal satu gejala.</b></p>";
    exit;
  }

  include "koneksi.php";

 /* ===============================
   FORWARD CHAINING 
   =============================== */
$kandidat = [];
$fc_kosong = false;

$_SESSION['log_fc'] = [];
$_SESSION['input_gejala'] = $input;

$query = mysqli_query($conn, "
  SELECT penyakit.nama, aturan_fc.gejala
  FROM aturan_fc
  JOIN penyakit ON aturan_fc.penyakit_id = penyakit.id
");

// === PROSES FC (INI YANG TADI HILANG) ===
while ($row = mysqli_fetch_assoc($query)) {

  $syarat = array_map('trim', explode(",", $row['gejala']));
  $cocok  = array_intersect($syarat, $input);

  $status = (count($cocok) / count($syarat) >= 0.6);

  $_SESSION['log_fc'][] = [
    'penyakit' => $row['nama'],
    'status'   => $status
  ];

  if ($status) {
    $kandidat[] = $row['nama'];
  }
}

// tentukan kondisi FC
if (count($kandidat) === 0) {
  $fc_kosong = true;
}

/* ===============================
   OUTPUT FC + PENJELASAN + SARAN
   =============================== */
echo "<div class='box'>";
echo "<h3>🧠 Hasil Sistem Pakar (Forward Chaining)</h3>";

if ($fc_kosong) {

  echo "<p><b>Hasil:</b><br>";
  echo "Tidak ditemukan penyakit berdasarkan aturan pakar.</p>";

  echo "<p><b>Penjelasan:</b><br>";
  echo "Berdasarkan gejala yang dipilih, tidak ada aturan pakar yang terpenuhi secara memadai untuk menentukan diagnosis tertentu.</p>";

  echo "<p><b>Saran:</b><br>";
  echo "Sistem melanjutkan proses diagnosis menggunakan metode Naive Bayes untuk memberikan rekomendasi berbasis probabilitas. ";
  echo "Apabila gejala dirasakan semakin berat atau berlangsung lama, disarankan untuk segera berkonsultasi dengan tenaga medis.</p>";

} else {

  foreach ($kandidat as $p) {

    echo "<hr>";
    echo "<b>✔ $p</b><br>";

    $qs = mysqli_query($conn, "
      SELECT penjelasan, saran 
      FROM saran_penyakit 
      WHERE penyakit='$p'
    ");

    if ($s = mysqli_fetch_assoc($qs)) {
      echo "<p><b>Penjelasan:</b><br>{$s['penjelasan']}</p>";
      echo "<p><b>Saran:</b><br>{$s['saran']}</p>";
    } else {
      echo "<p><i>Penjelasan dan saran belum tersedia.</i></p>";
    }
  }
}

echo "</div>";

  /* ===============================
     NAIVE BAYES (DATABASE)
     =============================== */

  echo "<div class='box'>";
  echo "<h3>🤖 Hasil Machine Learning (Naive Bayes)</h3>";

  // 👉 SUMBER PENYAKIT UNTUK NB
  $penyakit_nb = [];

  if ($fc_kosong) {
    $q = mysqli_query($conn, "SELECT nama FROM penyakit");
    while ($r = mysqli_fetch_assoc($q)) {
      $penyakit_nb[] = $r['nama'];
    }
  } else {
    $penyakit_nb = $kandidat;
  }

  $hasil_nb = [];

  $total_data = mysqli_num_rows(
    mysqli_query($conn, "SELECT id FROM dataset_nb")
  );

  foreach ($penyakit_nb as $penyakit) {

    $dataP = mysqli_query($conn, "
      SELECT * FROM dataset_nb WHERE penyakit='$penyakit'
    ");

    $jumlah_penyakit = mysqli_num_rows($dataP);
    if ($jumlah_penyakit == 0) continue;

    $prior = $jumlah_penyakit / $total_data;
    $probabilitas = $prior;

    foreach ($input as $g) {
      $count = 0;
      mysqli_data_seek($dataP, 0);

      while ($row = mysqli_fetch_assoc($dataP)) {
        if (isset($row[$g]) && $row[$g] == 1) {
          $count++;
        }
      }
      $probabilitas *= ($count + 1) / ($jumlah_penyakit + 2);
    }

    $hasil_nb[$penyakit] = $probabilitas;
  }

  // SIMPAN UNTUK HALAMAN PERHITUNGAN NB
  $_SESSION['hasil_nb_raw'] = $hasil_nb;

  // NORMALISASI
  $totalProb = array_sum($hasil_nb);
  $hasil_normalisasi = [];

  foreach ($hasil_nb as $penyakit => $nilai) {
    $hasil_normalisasi[$penyakit] = ($nilai / $totalProb) * 100;
  }

  arsort($hasil_normalisasi);

  foreach ($hasil_normalisasi as $p => $v) {
    echo "$p : ".round($v, 2)." %<br>";
  }

  echo "<br><b>📌 Diagnosis Akhir: ".array_key_first($hasil_normalisasi)."</b>";
  echo "</div>";
  // ===============================
// SIMPAN HASIL DIAGNOSIS (UNTUK DATASET LATIH)
// ===============================
$_SESSION['hasil_diagnosis'] = [
  'fc_kosong'        => $fc_kosong,
  'kandidat_fc'      => $kandidat,
  'hasil_nb_raw'     => $hasil_nb,
  'hasil_nb_normal' => $hasil_normalisasi,
  'diagnosis_akhir' => array_key_first($hasil_normalisasi),
  'waktu'            => date('Y-m-d H:i:s')
];
// ===============================
// SIMPAN RIWAYAT DIAGNOSIS KE DB
// ===============================
$hasil_fc_str = $fc_kosong
  ? 'Tidak ditemukan penyakit (FC)'
  : implode(', ', $kandidat);

$hasil_nb_str = json_encode($hasil_normalisasi);

$stmt = mysqli_prepare($conn, "
  INSERT INTO riwayat_diagnosis
  (username, gejala, hasil_fc, hasil_nb, diagnosis_akhir, created_at)
  VALUES (?, ?, ?, ?, ?, NOW())
");

$gejala_str = implode(', ', $input);

$hasil_nb_str = json_encode($hasil_normalisasi);

$gejala_str = implode(', ', $input);

$diagnosis_akhir = array_key_first($hasil_normalisasi);

mysqli_stmt_bind_param(
  $stmt,
  "sssss",
  $username,
  $gejala_str,
  $hasil_fc_str,
  $hasil_nb_str,
  $diagnosis_akhir
);
  

mysqli_stmt_execute($stmt);

}
?>

</div>
</body>
</html>
