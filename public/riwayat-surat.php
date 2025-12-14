<?php
require_once __DIR__ . '/../helpers/auth.php';
requireAdmin();

require_once __DIR__ . '/../config/database.php';

$surat = fetchAll("SELECT * FROM surat_log ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Surat</title>
    <link href="/public/assets/css/style.css" rel="stylesheet">
</head>
<body class="p-6 bg-gray-100">

<h1 class="text-xl font-bold mb-4">Riwayat Surat</h1>

<table class="w-full bg-white shadow rounded">
    <thead>
        <tr class="bg-gray-200 text-left">
            <th class="p-2">No</th>
            <th class="p-2">Nama</th>
            <th class="p-2">Jenis</th>
            <th class="p-2">Tanggal</th>
            <th class="p-2">Status</th>
            <th class="p-2">Aksi</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($surat as $i => $row): ?>
        <tr class="border-t">
            <td class="p-2"><?= $i + 1 ?></td>
            <td class="p-2"><?= htmlspecialchars($row['nama']) ?></td>
            <td class="p-2"><?= htmlspecialchars($row['jenis_surat']) ?></td>
            <td class="p-2"><?= $row['tanggal'] ?></td>
            <td class="p-2"><?= $row['status'] ?></td>
            <td class="p-2">
                <?php if ($row['status'] === 'pending'): ?>
                    <a href="approve.php?id=<?= $row['id'] ?>"
                       class="bg-green-600 text-white px-3 py-1 rounded text-sm">
                        Approve & Cetak
                    </a>
                <?php else: ?>
                    <a href="cetak-final.php?id=<?= $row['id'] ?>"
                       class="bg-blue-600 text-white px-3 py-1 rounded text-sm">
                        Cetak Ulang
                    </a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
