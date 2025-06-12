<?php
// File: absen.php
function load_pegawai_grouped() {
  if (!file_exists("master_pegawai.csv")) return [];
  $file = fopen("master_pegawai.csv", "r");
  $grouped = [];
  while (($data = fgetcsv($file)) !== FALSE) {
    $grouped[$data[1]][] = $data[0];
  }
  fclose($file);
  ksort($grouped);
  return $grouped;
}

function save_absen($tanggal, $nama_pegawai) {
  $tanggal = trim($tanggal);
  $data = file_exists("absen.csv") ? file("absen.csv") : [];
  $found = false;
  $output = fopen("absen.csv", "w");
  foreach ($data as $line) {
    $cols = str_getcsv($line);
    if ($cols[0] === $tanggal) {
      fputcsv($output, [$tanggal, implode(",", $nama_pegawai)]);
      $found = true;
    } else {
      fwrite($output, $line);
    }
  }
  if (!$found) fputcsv($output, [$tanggal, implode(",", $nama_pegawai)]);
  fclose($output);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $tanggal = $_POST['tanggal'] ?? date('Y-m-d');
  $absen = $_POST['absen'] ?? [];
  save_absen($tanggal, $absen);
  header("Location: absen.php");
  exit;
}

$grouped = load_pegawai_grouped();
$tanggal_hari_ini = date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Isi Absen</title>
  <style>
    body { font-family: sans-serif; padding: 20px; background: #f4f4f4; }
    form { background: white; padding: 20px; border-radius: 8px; }
    fieldset { margin-bottom: 20px; }
    legend { font-weight: bold; }
    label { display: block; margin: 5px 0; }
    button { padding: 10px 15px; background: #2980b9; color: white; border: none; border-radius: 5px; }
  </style>
</head>
<body>
  <h2>Isi Absen Pegawai</h2>
  <form method="post">
    <label>Tanggal:
      <input type="date" name="tanggal" value="<?= $tanggal_hari_ini ?>">
    </label>
    <br>
    <?php foreach ($grouped as $regu => $namas): ?>
      <fieldset>
        <legend><?= htmlspecialchars($regu) ?></legend>
        <?php foreach ($namas as $nama): ?>
          <label><input type="checkbox" name="absen[]" value="<?= htmlspecialchars($nama) ?>"> <?= htmlspecialchars($nama) ?></label>
        <?php endforeach; ?>
      </fieldset>
    <?php endforeach; ?>
    <button type="submit">Simpan</button>
  </form>
</body>
</html>
