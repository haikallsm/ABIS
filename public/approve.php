<?php
require_once __DIR__ . '/../helpers/auth.php';
requireAdmin();

require_once __DIR__ . '/../config/database.php';

$id = $_GET['id'] ?? 0;
if (!$id) die('ID tidak valid');

/**
 * UPDATE STATUS
 */
update(
    'surat_log',
    ['status' => 'approved'],
    'id = :id',
    ['id' => $id]
);

/**
 * LANGSUNG KE CETAK FINAL
 */
header("Location: cetak-final.php?id=$id");
exit;
