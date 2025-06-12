<?php
// File: regu.php
function load_regu() {
  $file = fopen("master_regu.csv", "r");
  $regus = [];
  while (($data = fgetcsv($file)) !== FALSE) {
    $regus[] = $data[0];
  }
  fclose($file);
  return $regus;
}

function regu_dipakai($nama_regu) {
  if (!file_exists("master_pegawai.csv")) return false;
  $file = fopen("master_pegawai.csv", "r");
  while (($data = fgetcsv($file)) !== FALSE) {
    if ($data[1] === $nama_regu) {
      fclose($file);
      return true;
    }
  }
  fclose($file);
  return false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['regu_baru'])) {
    $nama = trim($_POST['regu_baru']);
    if ($nama !== '' && !preg_match("/[,'\\]/", $nama)) {
      $file = fopen("master_regu.csv", "a");
      fputcsv($file, [$nama]);
      fclose($file);
    }
  }

  if (isset($_POST['hapus_regu'])) {
    $hapus = $_POST['hapus_regu'];
    if (!regu_dipakai($hapus)) {
      $lines = file("master_regu.csv");
      $out = fopen("master_regu.csv", "w");
      foreach ($lines as $line) {
        if (trim($line) !== $hapus) fwrite($out, $line);
      }
      fclose($out);
    }
  }
  header("Location: regu.php");
  exit;
}
$regus = load_regu();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Master Regu</title>
  <style>
    body { font-family: sans-serif; padding: 20px; background: #f4f4f4; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; }
    th, td { padding: 10px; border: 1px solid #ccc; }
    .top-bar { display: flex; justify-content: flex-end; margin-bottom: 10px; }
    button { padding: 10px 15px; border: none; background: #3498db; color: white; border-radius: 5px; }
    .modal { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
             background: rgba(0,0,0,0.5); justify-content: center; align-items: center; }
    .modal-content { background: white; padding: 20px; border-radius: 8px; width: 90%; max-width: 300px; }
    .visible { display: flex !important; }
  </style>
</head>
<body>
  <h2>Master Regu</h2>
  <div class="top-bar">
    <button onclick="openModal()">Regu Baru</button>
  </div>

  <table>
    <tr><th>Nama Regu</th><th>Hapus</th></tr>
    <?php foreach ($regus as $r): ?>
      <tr>
        <td><?= htmlspecialchars($r) ?></td>
        <td>
          <form method="post" onsubmit="return confirm('Anda Yakin?')">
            <input type="hidden" name="hapus_regu" value="<?= htmlspecialchars($r) ?>">
            <button>Hapus</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>

  <div class="modal" id="modal">
    <div class="modal-content">
      <h3>Regu Baru</h3>
      <form method="post">
        <input type="text" name="regu_baru" id="inputRegu" placeholder="Nama Regu" oninput="cekValid()">
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
      const val = document.getElementById('inputRegu').value;
      const tombol = document.getElementById('btnSimpan');
      tombol.disabled = /[,'\\]/.test(val) || val.trim() === '';
    }
  </script>
</body>
</html>
