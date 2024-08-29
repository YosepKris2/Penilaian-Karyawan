<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $jabatan = $_POST['jabatan'];
    $nik = $_POST['nik'];

    // Cek apakah NIK sudah ada di database
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM pengguna WHERE nik = :nik AND id != :id");
    $stmt->execute(['nik' => $nik, 'id' => $_SESSION['user_id']]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        $error = "NIK sudah terdaftar!";
    } else {
        // Update data pengguna jika NIK tidak ada
        $stmt = $pdo->prepare("UPDATE pengguna SET nama = :nama, jabatan = :jabatan, nik = :nik WHERE id = :id");
        $stmt->execute([
            'nama' => $nama,
            'jabatan' => $jabatan,
            'nik' => $nik,
            'id' => $_SESSION['user_id']
        ]);

        header('Location: dashboard.php');
        exit;
    }
}

// Ambil data pengguna
$stmt = $pdo->prepare("SELECT * FROM pengguna WHERE id = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Detail Pengguna</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="user-details-container">
        <h2>Input Detail Pengguna</h2>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form method="POST">
            <label for="nama">Nama:</label>
            <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($user['nama']); ?>" required>
            <label for="jabatan">Jabatan:</label>
            <input type="text" id="jabatan" name="jabatan" value="<?php echo htmlspecialchars($user['jabatan']); ?>" required>
            <label for="nik">NIK:</label>
            <input type="text" id="nik" name="nik" value="<?php echo htmlspecialchars($user['nik']); ?>" required>
            <button type="submit">Simpan</button>
        </form>
    </div>
</body>
</html>
