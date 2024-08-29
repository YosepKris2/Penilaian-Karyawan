<?php
session_start();
require 'database.php'; // Pastikan database.php berada di folder yang sama dengan edit_penilaian.php

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Ambil ID penilaian dari query string
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Ambil data penilaian dari database
$stmt = $pdo->prepare("SELECT * FROM penilaian WHERE id = ?");
$stmt->execute([$id]);
$penilaian = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $nama = $_POST['nama'];
    $jabatan = $_POST['jabatan'];
    $nik = $_POST['nik'];
    $tanggung_jawab = (int)$_POST['tanggung_jawab'];
    $ketepatan_waktu = (int)$_POST['ketepatan_waktu'];
    $kualitas_pekerjaan = (int)$_POST['kualitas_pekerjaan'];
    $kuantitas_hasil = (int)$_POST['kuantitas_hasil'];
    $kehadiran = (int)$_POST['kehadiran'];
    $kerja_sama_tim = (int)$_POST['kerja_sama_tim'];
    $inisiatif = (int)$_POST['inisiatif'];
    $kepemimpinan = (int)$_POST['kepemimpinan'];
    $perilaku = (int)$_POST['perilaku'];
    $karakter = (int)$_POST['karakter'];

    // Hitung total nilai
    $total_nilai = $tanggung_jawab + $ketepatan_waktu + $kualitas_pekerjaan + $kuantitas_hasil + $kehadiran + $kerja_sama_tim + $inisiatif + $kepemimpinan + $perilaku + $karakter;

    // Update data penilaian di database
    $stmt = $pdo->prepare("UPDATE penilaian SET nama = ?, jabatan = ?, nik = ?, tanggung_jawab = ?, ketepatan_waktu = ?, kualitas_pekerjaan = ?, kuantitas_hasil = ?, kehadiran = ?, kerja_sama_tim = ?, inisiatif = ?, kepemimpinan = ?, perilaku = ?, karakter = ?, total_nilai = ? WHERE id = ?");
    $stmt->execute([$nama, $jabatan, $nik, $tanggung_jawab, $ketepatan_waktu, $kualitas_pekerjaan, $kuantitas_hasil, $kehadiran, $kerja_sama_tim, $inisiatif, $kepemimpinan, $perilaku, $karakter, $total_nilai, $id]);

    header('Location: list_penilaian.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Penilaian</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
            overflow-y: auto; /* Menambahkan scroll jika diperlukan */
            max-height: calc(100vh - 40px); /* Mengatur tinggi maksimal container */
        }
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
        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        a {
            color: #007bff;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Penilaian</h2>
        <form method="post">
            <label>Nama:</label>
            <input type="text" name="nama" value="<?php echo htmlspecialchars($penilaian['nama']); ?>" required>
            
            <label>Jabatan:</label>
            <input type="text" name="jabatan" value="<?php echo htmlspecialchars($penilaian['jabatan']); ?>" required>

            <label>NIK:</label>
            <input type="text" name="nik" value="<?php echo htmlspecialchars($penilaian['nik']); ?>" required>

            <?php
            // Daftar penilaian dengan 10 poin
            $criteria = [
                'tanggung_jawab' => 'Tanggung Jawab Peran',
                'ketepatan_waktu' => 'Ketepatan Waktu',
                'kualitas_pekerjaan' => 'Kualitas Pekerjaan',
                'kuantitas_hasil' => 'Kuantitas Hasil',
                'kehadiran' => 'Kehadiran',
                'kerja_sama_tim' => 'Kerja Sama Tim',
                'inisiatif' => 'Inisiatif',
                'kepemimpinan' => 'Kepemimpinan',
                'perilaku' => 'Perilaku',
                'karakter' => 'Karakter'
            ];

            foreach ($criteria as $key => $label) {
                echo "<label>$label:</label>
                <div class='rating-group'>";
                for ($i = 0; $i <= 10; $i++) {
                    $checked = $penilaian[$key] == $i ? 'checked' : '';
                    echo "<input type='radio' id='{$key}_$i' name='$key' value='$i' $checked required>
                    <label for='{$key}_$i'>$i</label>";
                }
                echo "</div>";
            }
            ?>

            <button type="submit">Update Penilaian</button>
        </form>
        <a href="list_penilaian.php">Kembali ke Daftar Penilaian</a>
    </div>
</body>
</html>
