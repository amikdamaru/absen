<?php
// File: pegawai.php
function load_regu() {
  $file = fopen("master_regu.csv", "r");
  $regus = [];
  while (($data = fgetcsv($file)) !== FALSE) {
    $regus[] = $data[0];
  }
  fclose($file);
  return $regus;
}

function load_pegawai() {
  if (!file_exists("master_pegawai.csv")) return [];
  $file = fopen("master_pegawai.csv", "r");
  $list = [];
  while (($data = fgetcsv($file)) !== FALSE) {
    $list[] = $data;
  }
  fclose($file);
  return $list;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['pegawai_baru'])) {
    $nama = trim($_POST['pegawai_baru']);
    $regu = $_POST['regu'];
    if ($nama !== '' && !preg_match("/[,'\\]/", $nama)) {
      $file = fopen("master_pegawai.csv", "a");
      fputcsv($file, [$nama, $regu]);
      fclose($file);
    }
  }

  if (isset($_POST['hapus_pegawai'])) {
    $hapus = $_POST['hapus_pegawai'];
    $lines = file("master_pegawai.csv");
    $out = fopen("master_pegawai.csv", "w");
    foreach ($lines as $line) {
      if (strpos($line, $hapus . ",") !== 0) fwrite($out, $line);
    }
    fclose($out);
  }
  header("Location: pegawai.php");
  exit;
}

$regus = load_regu();
$pegawai = load_pegawai();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Master Pegawai</title>
  <style>
    body { font-family: sans-serif; padding: 20px; background: #f4f4f4; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; }
    th, td { padding: 10px; border: 1px solid #ccc; }
    .top-bar { display: flex; justify-content: flex-end; margin-bottom: 10px; }
    button { padding: 10px 15px; border: none; background: #27ae60; color: white; border-radius: 5px; }
    .modal { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
             background: rgba(0,0,0,0.5); justify-content: center; align-items: center; }
    .modal-content { background: white; padding: 20px; border-radius: 8px; width: 90%; max-width: 300px; }
    .visible { display: flex !important; }
  </style>
</head>
<body>
  <h2>Master Pegawai</h2>
  <div class="top-bar">
    <button onclick="openModal()">Pegawai Baru</button>
  </div>

  <table>
    <tr><th>Nama Pegawai</th><th>Nama Regu</th><th>Hapus</th></tr>
    <?php foreach ($pegawai as $p): ?>
      <tr>
        <td><?= htmlspecialchars($p[0]) ?></td>
        <td><?= htmlspecialchars($p[1]) ?></td>
        <td>
          <form method="post" onsubmit="return confirm('Anda Yakin?')">
            <input type="hidden" name="hapus_pegawai" value="<?= htmlspecialchars($p[0]) ?>">
            <button>Hapus</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>

  <div class="modal" id="modal">
    <div class="modal-content">
      <h3>Pegawai Baru</h3>
      <form method="post">
        <input type="text" name="pegawai_baru" id="inputPegawai" placeholder="Nama Pegawai" oninput="cekValid()">
        <br><br>
        <select name="regu" required>
          <?php foreach ($regus as $r): ?>
            <option value="<?= htmlspecialchars($r) ?>"><?= htmlspecialchars($r) ?></option>
          <?php endforeach; ?>
        </select>
        <br><br>
        <button id="btnSimpan" disabled>Simpan</button>
      </form>
    </div>
  </div>

  <script>
    function openModal() {
      document.getElementById('modal').classList.add('visible');
    }
    function cekValid() {
      const val = document.getElementById('inputPegawai').value;
      const tombol = document.getElementById('btnSimpan');
      tombol.disabled = /[,'\]/.test(val) || val.trim() === '';
    }
  </script>
</body>
</html>
