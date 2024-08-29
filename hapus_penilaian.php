<?php
session_start();
require 'database.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Pastikan ID ada dalam query string dan valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo 'ID tidak valid';
    exit;
}

$id = (int)$_GET['id'];

// Hapus data dari database
try {
    $stmt = $pdo->prepare("DELETE FROM penilaian WHERE id = ?");
    $stmt->execute([$id]);

    // Periksa apakah ada baris yang terpengaruh
    if ($stmt->rowCount() > 0) {
        // Redirect ke halaman lain setelah penghapusan
        header('Location: list_penilaian.php');
        exit;
    } else {
        echo 'Tidak ada data yang dihapus';
    }
} catch (Exception $e) {
    echo 'Terjadi kesalahan: ' . htmlspecialchars($e->getMessage());
}
?>
