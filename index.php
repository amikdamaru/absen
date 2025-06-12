<?php
// Struktur awal project sesuai permintaan
// File: index.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Absen Pegawai</title>
  <style>
    body { font-family: sans-serif; padding: 20px; background: #f8f8f8; }
    .button-container { display: flex; flex-direction: column; gap: 15px; }
    a.button {
      background-color: #3498db;
      color: white;
      padding: 20px;
      text-align: center;
      text-decoration: none;
      font-size: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    @media(min-width:600px){ .button-container{ max-width:400px; margin:auto; } }
  </style>
</head>
<body>
  <div class="button-container">
    <a href="regu.php" class="button">MASTER REGU</a>
    <a href="pegawai.php" class="button">MASTER PEGAWAI</a>
    <a href="absen.php" class="button">ISI ABSEN</a>
    <a href="laporan.php" class="button">LAPORAN</a>
  </div>
</body>
</html>
