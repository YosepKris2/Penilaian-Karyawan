<?php
session_start();
require 'database.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Ambil ID penilaian dari query string
$id = $_GET['id'] ?? 0;

// Ambil data penilaian dari database
$stmt = $pdo->prepare("SELECT * FROM penilaian WHERE id = :id");
$stmt->execute(['id' => $id]);
$penilaian = $stmt->fetch();

// Jika penilaian tidak ditemukan
if (!$penilaian) {
    echo "Penilaian tidak ditemukan.";
    exit;
}

// Hitung total nilai dari semua kriteria
$total = $penilaian['tanggung_jawab'] +
         $penilaian['ketepatan_waktu'] +
         $penilaian['kualitas_pekerjaan'] +
         $penilaian['kuantitas_hasil'] +
         $penilaian['kehadiran'] +
         $penilaian['kerja_sama_tim'] +
         $penilaian['inisiatif'] +
         $penilaian['kepemimpinan'] +
         $penilaian['perilaku'] +
         $penilaian['karakter'];

// Tentukan keterangan berdasarkan total nilai
function getKeterangan($total) {
    if ($total >= 80) return 'Baik Sekali';
    if ($total >= 66) return 'Baik';
    if ($total >= 56) return 'Cukup';
    if ($total >= 40) return 'Kurang';
    if ($total >= 30) return 'Gagal';
    return 'Tidak Valid';
}

$keterangan = getKeterangan($total);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Penilaian Karyawan</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        .detail-container {
            width: 100%; /* Menggunakan lebar penuh layar */
            max-width: 1200px; /* Membatasi lebar maksimum */
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow-y: auto; /* Mengaktifkan scroll jika konten melebihi tinggi container */
            max-height: calc(100vh - 40px); /* Mengatur tinggi maksimal container */
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px; /* Menambahkan padding */
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .action-link {
            text-decoration: none;
            color: #007bff;
            padding: 6px 12px; /* Menambahkan padding */
            border: 1px solid #007bff;
            border-radius: 4px;
            margin: 0 4px;
            display: inline-block;
            font-size: 14px; /* Menyesuaikan ukuran font */
        }
        .action-link:hover {
            background-color: #007bff;
            color: #fff;
        }
        .back-button {
            margin-top: 20px;
            display: inline-block;
        }
        .print-button {
            margin-top: 20px;
            padding: 8px 16px; /* Menambahkan padding */
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px; /* Menyesuaikan ukuran font */
        }
        .print-button:hover {
            background-color: #218838;
        }
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .detail-container {
                margin: 0;
                padding: 10px;
                border: none;
                background-color: #fff;
                max-height: none;
                overflow: visible;
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            th, td {
                border: 1px solid black;
                padding: 8px;
                text-align: left;
            }
            th {
                background-color: #f2f2f2;
            }
            .print-button, .back-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="detail-container">
        <h2>Detail Penilaian Karyawan</h2>
        <table>
            <tr>
                <th>Nama:</th>
                <td><?php echo htmlspecialchars($penilaian['nama']); ?></td>
            </tr>
            <tr>
                <th>NIK:</th>
                <td><?php echo htmlspecialchars($penilaian['nik']); ?></td>
            </tr>
            <tr>
                <th>Jabatan:</th>
                <td><?php echo htmlspecialchars($penilaian['jabatan']); ?></td>
            </tr>
            <tr>
                <th>Tanggung Jawab Peran:</th>
                <td><?php echo htmlspecialchars($penilaian['tanggung_jawab']); ?></td>
            </tr>
            <tr>
                <th>Ketepatan Waktu:</th>
                <td><?php echo htmlspecialchars($penilaian['ketepatan_waktu']); ?></td>
            </tr>
            <tr>
                <th>Kualitas Pekerjaan:</th>
                <td><?php echo htmlspecialchars($penilaian['kualitas_pekerjaan']); ?></td>
            </tr>
            <tr>
                <th>Kuantitas Hasil:</th>
                <td><?php echo htmlspecialchars($penilaian['kuantitas_hasil']); ?></td>
            </tr>
            <tr>
                <th>Kehadiran:</th>
                <td><?php echo htmlspecialchars($penilaian['kehadiran']); ?></td>
            </tr>
            <tr>
                <th>Kerja Sama Tim:</th>
                <td><?php echo htmlspecialchars($penilaian['kerja_sama_tim']); ?></td>
            </tr>
            <tr>
                <th>Inisiatif:</th>
                <td><?php echo htmlspecialchars($penilaian['inisiatif']); ?></td>
            </tr>
            <tr>
                <th>Kepemimpinan:</th>
                <td><?php echo htmlspecialchars($penilaian['kepemimpinan']); ?></td>
            </tr>
            <tr>
                <th>Perilaku:</th>
                <td><?php echo htmlspecialchars($penilaian['perilaku']); ?></td>
            </tr>
            <tr>
                <th>Karakter:</th>
                <td><?php echo htmlspecialchars($penilaian['karakter']); ?></td>
            </tr>
            <tr>
                <th>Total Nilai:</th>
                <td><?php echo htmlspecialchars($penilaian['total_nilai']); ?></td>
            </tr>
            <tr>
                <th>Keterangan:</th>
                <td><?php echo htmlspecialchars($keterangan); ?></td>
            </tr>
        </table>
        <button class="print-button" onclick="window.print()">Cetak Detail</button>
        <a href="list_penilaian.php" class="action-link back-button">Kembali</a>
    </div>
</body>
</html>
