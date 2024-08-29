<?php
session_start();
require 'database.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Ambil data dari form
$tanggung_jawab = isset($_POST['tanggung_jawab']) ? $_POST['tanggung_jawab'] : null;
$ketepatan_waktu = isset($_POST['ketepatan_waktu']) ? $_POST['ketepatan_waktu'] : null;
$kualitas_pekerjaan = isset($_POST['kualitas_pekerjaan']) ? $_POST['kualitas_pekerjaan'] : null;
$kuantitas_hasil = isset($_POST['kuantitas_hasil']) ? $_POST['kuantitas_hasil'] : null;
$kehadiran = isset($_POST['kehadiran']) ? $_POST['kehadiran'] : null;
$kerja_sama_tim = isset($_POST['kerja_sama_tim']) ? $_POST['kerja_sama_tim'] : null;
$inisiatif = isset($_POST['inisiatif']) ? $_POST['inisiatif'] : null;
$kepemimpinan = isset($_POST['kepemimpinan']) ? $_POST['kepemimpinan'] : null;
$perilaku = isset($_POST['perilaku']) ? $_POST['perilaku'] : null;
$karakter = isset($_POST['karakter']) ? $_POST['karakter'] : null;

if ($tanggung_jawab === null || $ketepatan_waktu === null || $kualitas_pekerjaan === null || $kuantitas_hasil === null ||
    $kehadiran === null || $kerja_sama_tim === null || $inisiatif === null || $kepemimpinan === null || $perilaku === null ||
    $karakter === null) {
    $_SESSION['error'] = 'Semua bidang harus diisi.';
    header('Location: dashboard.php');
    exit;
}

// Ambil data pengguna dari database
$stmt = $pdo->prepare("SELECT * FROM pengguna WHERE id = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch();

$nama = $user['nama'] ?? '';
$jabatan = $user['jabatan'] ?? '';
$nik = isset($_POST['nik']) ? $_POST['nik'] : '';

// Cek apakah NIK sudah ada di database
$stmt = $pdo->prepare("SELECT COUNT(*) FROM penilaian WHERE nik = :nik");
$stmt->execute(['nik' => $nik]);
$exists = $stmt->fetchColumn();

if ($exists > 0) {
    // Set pesan error dan redirect kembali ke form
    $_SESSION['error'] = 'NIK sudah digunakan. Silakan masukkan NIK yang berbeda.';
    header('Location: dashboard.php');
    exit;
}

// Hitung total nilai dari semua kriteria
$total_nilai = $tanggung_jawab +
               $ketepatan_waktu +
               $kualitas_pekerjaan +
               $kuantitas_hasil +
               $kehadiran +
               $kerja_sama_tim +
               $inisiatif +
               $kepemimpinan +
               $perilaku +
               $karakter;

// Masukkan data ke dalam database
$stmt = $pdo->prepare("
    INSERT INTO penilaian (nama, jabatan, nik, tanggung_jawab, ketepatan_waktu, kualitas_pekerjaan, kuantitas_hasil, kehadiran, kerja_sama_tim, inisiatif, kepemimpinan, perilaku, karakter, total_nilai)
    VALUES (:nama, :jabatan, :nik, :tanggung_jawab, :ketepatan_waktu, :kualitas_pekerjaan, :kuantitas_hasil, :kehadiran, :kerja_sama_tim, :inisiatif, :kepemimpinan, :perilaku, :karakter, :total_nilai)
");
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

// Redirect ke halaman daftar penilaian
header('Location: list_penilaian.php');
exit;
?>
