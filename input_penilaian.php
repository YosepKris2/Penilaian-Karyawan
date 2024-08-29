<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Penilaian Karyawan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="input-container">
        <h2>Input Penilaian Karyawan</h2>
        <form method="POST" action="hasil.php">
            <label for="nama">Nama:</label>
            <input type="text" id="nama" name="nama" required>

            <label for="jabatan">Jabatan:</label>
            <input type="text" id="jabatan" name="jabatan" required>

            <label for="nik">NIK:</label>
            <input type="text" id="nik" name="nik" required>

            <ol>
                <li><label for="tanggung_jawab">Tanggung Jawab Peran:</label><input type="number" id="tanggung_jawab" name="tanggung_jawab" min="1" max="10" required></li>
                <li><label for="ketepatan_waktu">Ketepatan Waktu:</label><input type="number" id="ketepatan_waktu" name="ketepatan_waktu" min="1" max="10" required></li>
                <li><label for="kualitas_pekerjaan">Kualitas Pekerjaan:</label><input type="number" id="kualitas_pekerjaan" name="kualitas_pekerjaan" min="1" max="10" required></li>
                <li><label for="kuantitas_hasil">Kuantitas Hasil:</label><input type="number" id="kuantitas_hasil" name="kuantitas_hasil" min="1" max="10" required></li>
                <li><label for="kehadiran">Kehadiran:</label><input type="number" id="kehadiran" name="kehadiran" min="1" max="10" required></li>
                <li><label for="kerja_sama_tim">Kerja Sama Tim:</label><input type="number" id="kerja_sama_tim" name="kerja_sama_tim" min="1" max="10" required></li>
                <li><label for="inisiatif">Inisiatif:</label><input type="number" id="inisiatif" name="inisiatif" min="1" max="10" required></li>
                <li><label for="kepemimpinan">Kepemimpinan:</label><input type="number" id="kepemimpinan" name="kepemimpinan" min="1" max="10" required></li>
                <li><label for="perilaku">Perilaku:</label><input type="number" id="perilaku" name="perilaku" min="1" max="10" required></li>
                <li><label for="karakter">Karakter:</label><input type="number" id="karakter" name="karakter" min="1" max="10" required></li>
            </ol>
            <button type="submit">Kirim Penilaian</button>
        </form>
        <a href="dashboard.php">Kembali</a>
    </div>
</body>
</html>
