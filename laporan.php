<?php
// File: laporan.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $awal = $_POST['tanggal_awal'] ?? date('Y-m-d');
  $akhir = $_POST['tanggal_akhir'] ?? date('Y-m-d');

  $csvData = [];
  if (file_exists("absen.csv")) {
    $rows = array_map('str_getcsv', file("absen.csv"));
    foreach ($rows as $row) {
      $tanggal = $row[0];
      if ($tanggal >= $awal && $tanggal <= $akhir) {
        $csvData[] = $row;
      }
    }
  }

  $filename = "laporan_absen_{$awal}_sd_{$akhir}.csv";
  header('Content-Type: text/csv');
  header("Content-Disposition: attachment; filename=\"$filename\"");
  $output = fopen('php://output', 'w');
  fputcsv($output, ['Tanggal', 'Pegawai Absen']);
  foreach ($csvData as $data) {
    fputcsv($output, $data);
  }
  fclose($output);
  exit;
}

$tanggal_hari_ini = date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laporan Absen</title>
  <style>
    body { font-family: sans-serif; padding: 20px; background: #f4f4f4; }
    form { background: white; padding: 20px; border-radius: 8px; }
    label { display: block; margin-bottom: 10px; }
    button { padding: 10px 15px; background: #c0392b; color: white; border: none; border-radius: 5px; }
  </style>
</head>
<body>
  <h2>Download Laporan Absen</h2>
  <form method="post">
    <label>Tanggal Awal:
      <input type="date" name="tanggal_awal" value="<?= $tanggal_hari_ini ?>">
    </label>
    <label>Tanggal Akhir:
      <input type="date" name="tanggal_akhir" value="<?= $tanggal_hari_ini ?>">
    </label>
    <br>
    <button type="submit">Download</button>
  </form>
</body>
</html>
