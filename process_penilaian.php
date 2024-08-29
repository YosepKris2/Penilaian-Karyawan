<?php
session_start();
require 'database.php';

// Ambil data dari form
$nama = $_POST['nama'];
$jabatan = $_POST['jabatan'];
$nik = $_POST['nik'];
$tanggung_jawab = $_POST['tanggung_jawab_peran'];
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
$total_nilai = $tanggung_jawab + $ketepatan_waktu + $kualitas_pekerjaan + $kuantitas_hasil + 
               $kehadiran + $kerja_sama_tim + $inisiatif + $kepemimpinan + $perilaku + $karakter;

// Periksa apakah NIK sudah ada
$stmt = $pdo->prepare("SELECT COUNT(*) FROM penilaian WHERE nik = :nik");
$stmt->execute(['nik' => $nik]);
$exists = $stmt->fetchColumn();

if ($exists) {
    // Simpan pesan kesalahan dalam sesi dan arahkan kembali
    $_SESSION['error'] = 'NIK sudah ada. Silakan gunakan NIK yang berbeda.';
    header('Location: dashboard.php');
    exit;
}

// Simpan data penilaian ke database
$stmt = $pdo->prepare("INSERT INTO penilaian 
    (nama, nik, jabatan, tanggung_jawab, ketepatan_waktu, kualitas_pekerjaan, kuantitas_hasil, kehadiran, kerja_sama_tim, inisiatif, kepemimpinan, perilaku, karakter, total_nilai) 
    VALUES (:nama, :nik, :jabatan, :tanggung_jawab, :ketepatan_waktu, :kualitas_pekerjaan, :kuantitas_hasil, :kehadiran, :kerja_sama_tim, :inisiatif, :kepemimpinan, :perilaku, :karakter, :total_nilai)");
$stmt->execute([
    'nama' => $nama,
    'nik' => $nik,
    'jabatan' => $jabatan,
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
    'total_nilai' => $total_nilai,
]);

// Redirect ke halaman list_penilaian.php setelah proses selesai
header('Location: list_penilaian.php');
exit;
