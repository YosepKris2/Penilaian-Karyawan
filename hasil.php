<?php
session_start();
require 'database.php'; // Pastikan database.php berada di folder yang sama dengan hasil.php

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Cek apakah data dikirim melalui metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Proses data yang dikirimkan melalui POST
    $nama = $_POST['nama'];
    $jabatan = $_POST['jabatan'];
    $nik = $_POST['nik'];
    $tanggung_jawab = $_POST['tanggung_jawab'];
    $ketepatan_waktu = $_POST['ketepatan_waktu'];
    $kualitas_pekerjaan = $_POST['kualitas_pekerjaan'];
    $kuantitas_hasil = $_POST['kuantitas_hasil'];
    $kehadiran = $_POST['kehadiran'];
    $kerja_sama_tim = $_POST['kerja_sama_tim'];
    $inisiatif = $_POST['inisiatif'];
    $kepemimpinan = $_POST['kepemimpinan'];
    $perilaku = $_POST['perilaku'];
    $karakter = $_POST['karakter'];

    // Hitung total nilai
    $total_nilai = $tanggung_jawab + $ketepatan_waktu + $kualitas_pekerjaan + $kuantitas_hasil + $kehadiran + $kerja_sama_tim + $inisiatif + $kepemimpinan + $perilaku + $karakter;

    // Simpan hasil penilaian ke database
    $stmt = $pdo->prepare("INSERT INTO penilaian (nama, jabatan, nik, tanggung_jawab, ketepatan_waktu, kualitas_pekerjaan, kuantitas_hasil, kehadiran, kerja_sama_tim, inisiatif, kepemimpinan, perilaku, karakter, total_nilai) VALUES (:nama, :jabatan, :nik, :tanggung_jawab, :ketepatan_waktu, :kualitas_pekerjaan, :kuantitas_hasil, :kehadiran, :kerja_sama_tim, :inisiatif, :kepemimpinan, :perilaku, :karakter, :total_nilai)");
    $stmt->execute([
        'nama' => $nama,
        'jabatan' => $jabatan,
        'nik' => $nik,
        'tanggung_jawab' => $tanggung_jawab,
        'ketepatan_waktu' => $ketepatan_waktu,
        'kualitas_pekerjaan' => $kualitas_pekerjaan,
        'kuantitas_hasil' => $kuantitas_hasil,
        'kehadiran' => $kehadiran,
        'kerja_sama_tim' => $kerja_sama_tim,
        'inisiatif' => $inisiatif,
        'kepemimpinan' => $kepemimpinan,
        'perilaku' => $perilaku,
        'karakter' => $karakter,
        'total_nilai' => $total_nilai
    ]);

    // Arahkan ke halaman list_penilaian.php setelah data disimpan
    header('Location: list_penilaian.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penilaian Karyawan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <h2>Form Penilaian Karyawan</h2>
        <form action="hasil.php" method="POST">
            <label for="nama">Nama:</label>
            <input type="text" id="nama" name="nama" required>
            
            <label for="jabatan">Jabatan:</label>
            <input type="text" id="jabatan" name="jabatan" required>
            
            <label for="nik">NIK:</label>
            <input type="text" id="nik" name="nik" required>
            
            <label for="tanggung_jawab">Tanggung Jawab Peran:</label>
            <input type="number" id="tanggung_jawab" name="tanggung_jawab" required>
            
            <label for="ketepatan_waktu">Ketepatan Waktu:</label>
            <input type="number" id="ketepatan_waktu" name="ketepatan_waktu" required>
            
            <label for="kualitas_pekerjaan">Kualitas Pekerjaan:</label>
            <input type="number" id="kualitas_pekerjaan" name="kualitas_pekerjaan" required>
            
            <label for="kuantitas_hasil">Kuantitas Hasil:</label>
            <input type="number" id="kuantitas_hasil" name="kuantitas_hasil" required>
            
            <label for="kehadiran">Kehadiran:</label>
            <input type="number" id="kehadiran" name="kehadiran" required>
            
            <label for="kerja_sama_tim">Kerja Sama Tim:</label>
            <input type="number" id="kerja_sama_tim" name="kerja_sama_tim" required>
            
            <label for="inisiatif">Inisiatif:</label>
            <input type="number" id="inisiatif" name="inisiatif" required>
            
            <label for="kepemimpinan">Kepemimpinan:</label>
            <input type="number" id="kepemimpinan" name="kepemimpinan" required>
            
            <label for="perilaku">Perilaku:</label>
            <input type="number" id="perilaku" name="perilaku" required>
            
            <label for="karakter">Karakter:</label>
            <input type="number" id="karakter" name="karakter" required>
            
            <button type="submit">Kirim Penilaian</button>
        </form>
    </div>
</body>
</html>
