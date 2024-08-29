<?php
session_start();
require 'database.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Ambil data penilaian dari database
$stmt = $pdo->prepare("SELECT * FROM penilaian ORDER BY id DESC");
$stmt->execute();
$penilaians = $stmt->fetchAll();

// Fungsi untuk menentukan keterangan berdasarkan nilai total
function getKeterangan($total) {
    if ($total >= 80) return 'Baik Sekali';
    if ($total >= 66) return 'Baik';
    if ($total >= 56) return 'Cukup';
    if ($total >= 40) return 'Kurang';
    if ($total >= 30) return 'Gagal';
    return 'Tidak Valid';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Penilaian Karyawan</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .detail-container {
            width: 100%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 10px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto;
        }

        th, td {
            padding: 4px;
            text-align: center;
            border-bottom: 1px solid #ddd;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: 10px;
        }

        th {
            background-color: #f4f4f4;
            position: -webkit-sticky;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .action-link {
            text-decoration: none;
            color: #007bff;
            padding: 2px 4px;
            border: 1px solid #007bff;
            border-radius: 2px;
            margin: 0 2px;
            display: inline-block;
            font-size: 8px;
        }

        .action-link:hover {
            background-color: #007bff;
            color: #fff;
        }

        .delete {
            color: #dc3545;
            border-color: #dc3545;
        }

        .delete:hover {
            background-color: #dc3545;
            color: #fff;
        }

        .total-nilai {
            font-weight: bold;
        }

        .print-button-container {
            margin: 10px 0;
            text-align: right;
        }

        .print-button {
            padding: 4px 8px;
            border: 1px solid #007bff;
            border-radius: 3px;
            background-color: #007bff;
            color: #fff;
            font-size: 8px;
            cursor: pointer;
        }

        .print-button:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .back-button {
            text-decoration: none;
            color: #007bff;
            padding: 2px 4px;
            border: 1px solid #007bff;
            border-radius: 3px;
            margin-top: 10px;
            display: inline-block;
            font-size: 8px;
        }

        .back-button:hover {
            background-color: #007bff;
            color: #fff;
        }

        @media print {
            .action-link, .print-button-container, .back-button {
                display: none;
            }

            body {
                margin: 0;
                padding: 0;
                font-size: 8px;
            }

            .detail-container {
                width: 100%;
                margin: 0;
                padding: 0;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                font-size: 8px;
                page-break-inside: auto;
                overflow: hidden;
            }

            th, td {
                border: 1px solid black;
                padding: 2px;
                text-align: center;
                white-space: nowrap;
            }

            th {
                background-color: #f2f2f2;
            }

            tr {
                page-break-inside: auto;
            }

            @page {
                size: landscape;
                margin: 0.5in;
            }
        }

        @media (max-width: 768px) {
            .detail-container {
                width: 100%;
                padding: 5px;
            }

            table {
                font-size: 8px;
                overflow-x: auto;
                display: block;
                white-space: nowrap;
            }

            th, td {
                padding: 2px;
                font-size: 8px;
            }

            .action-link, .back-button {
                font-size: 8px;
                padding: 2px 4px;
                margin: 0 2px;
            }

            .print-button {
                font-size: 8px;
                padding: 4px 6px;
            }
        }
    </style>
</head>
<body>
    <div class="detail-container">
        <h2>Daftar Penilaian Karyawan</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 7%;">Nama</th>
                    <th style="width: 6%;">NIK</th>
                    <th style="width: 8%;">Jabatan</th>
                    <th style="width: 5%;">Tanggung Jawab</th>
                    <th style="width: 5%;">Ketepatan</th>
                    <th style="width: 5%;">Kualitas</th>
                    <th style="width: 5%;">Kuantitas</th>
                    <th style="width: 5%;">Kehadiran</th>
                    <th style="width: 5%;">Kerja Sama</th>
                    <th style="width: 5%;">Inisiatif</th>
                    <th style="width: 5%;">Kepemimpinan</th>
                    <th style="width: 5%;">Perilaku</th>
                    <th style="width: 5%;">Karakter</th>
                    <th style="width: 7%;">Total</th>
                    <th style="width: 10%;">Keterangan</th>
                    <th style="width: 10%;" class="no-print">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($penilaians as $penilaian): ?>
                <?php
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
                $keterangan = getKeterangan($total);
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($penilaian['nama']); ?></td>
                    <td><?php echo htmlspecialchars($penilaian['nik']); ?></td>
                    <td><?php echo htmlspecialchars($penilaian['jabatan']); ?></td>
                    <td><?php echo htmlspecialchars($penilaian['tanggung_jawab']); ?></td>
                    <td><?php echo htmlspecialchars($penilaian['ketepatan_waktu']); ?></td>
                    <td><?php echo htmlspecialchars($penilaian['kualitas_pekerjaan']); ?></td>
                    <td><?php echo htmlspecialchars($penilaian['kuantitas_hasil']); ?></td>
                    <td><?php echo htmlspecialchars($penilaian['kehadiran']); ?></td>
                    <td><?php echo htmlspecialchars($penilaian['kerja_sama_tim']); ?></td>
                    <td><?php echo htmlspecialchars($penilaian['inisiatif']); ?></td>
                    <td><?php echo htmlspecialchars($penilaian['kepemimpinan']); ?></td>
                    <td><?php echo htmlspecialchars($penilaian['perilaku']); ?></td>
                    <td><?php echo htmlspecialchars($penilaian['karakter']); ?></td>
                    <td class="total-nilai"><?php echo htmlspecialchars($penilaian['total_nilai']); ?></td>
                    <td><?php echo htmlspecialchars($keterangan); ?></td>
                    <td class="no-print">
                        <a href="edit_penilaian.php?id=<?php echo $penilaian['id']; ?>" class="action-link">Edit</a>
                        <a href="hapus_penilaian.php?id=<?php echo $penilaian['id']; ?>" class="action-link delete" onclick="return confirm('Yakin ingin menghapus penilaian ini?');">Hapus</a>
                        <a href="detail_penilaian.php?id=<?php echo $penilaian['id']; ?>" class="action-link">Detail</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="print-button-container">
            <button class="print-button" onclick="window.print()">Cetak</button>
        </div>
        <a href="dashboard.php" class="back-button">Kembali ke Dashboard</a>
    </div>
</body>
</html>
