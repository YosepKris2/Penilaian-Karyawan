<?php
session_start();
require 'database.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Ambil data pengguna dari database
$stmt = $pdo->prepare("SELECT * FROM pengguna WHERE id = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch();

// Set nilai awal menjadi kosong
$nama = '';
$jabatan = '';
$nik = '';

// Cek apakah ada pesan error
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .rating-group {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }
        .rating-group label {
            cursor: pointer;
            border: 1px solid #ccc;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }
        .rating-group input[type="radio"] {
            display: none;
        }
        .rating-group input[type="radio"]:checked + label {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
        }
        .error-message {
            color: #dc3545;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h2>Form Penilaian Karyawan</h2>
        
        <?php if ($error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="process_penilaian.php">

            <label for="nama">Nama:</label>
            <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($nama); ?>" required>

            <label for="jabatan">Jabatan:</label>
            <input type="text" id="jabatan" name="jabatan" value="<?php echo htmlspecialchars($jabatan); ?>" required>

            <label for="nik">NIK:</label>
            <input type="text" id="nik" name="nik" value="<?php echo htmlspecialchars($nik); ?>" required>

            <ol>
                <?php
                $criteria = [
                    'Tanggung Jawab Peran',
                    'Ketepatan Waktu',
                    'Kualitas Pekerjaan',
                    'Kuantitas Hasil',
                    'Kehadiran',
                    'Kerja Sama Tim',
                    'Inisiatif',
                    'Kepemimpinan',
                    'Perilaku',
                    'Karakter'
                ];
                foreach ($criteria as $criterion):
                ?>
                <li>
                    <label><?php echo htmlspecialchars($criterion); ?>:</label>
                    <div class="rating-group">
                        <?php for ($i = 0; $i <= 10; $i++): ?>
                        <input type="radio" id="<?php echo htmlspecialchars($criterion . '_' . $i); ?>" name="<?php echo strtolower(str_replace(' ', '_', $criterion)); ?>" value="<?php echo $i; ?>" required>
                        <label for="<?php echo htmlspecialchars($criterion . '_' . $i); ?>"><?php echo $i; ?></label>
                        <?php endfor; ?>
                    </div>
                </li>
                <?php endforeach; ?>
            </ol>
            <button type="submit">Kirim Penilaian</button>
        </form>
        <a href="logout.php" class="logout-button">Logout</a>
    </div>
</body>
</html>
