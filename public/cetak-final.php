<?php
require_once __DIR__ . '/../helpers/auth.php';
requireAdmin();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;

$id = $_GET['id'] ?? 0;

/**
 * AMBIL DATA SURAT
 */
$surat = fetchOne(
    "SELECT * FROM surat_log WHERE id = :id",
    ['id' => $id]
);

if (!$surat || $surat['status'] !== 'approved') {
    die('Surat belum disetujui');
}

/**
 * MODE FINAL
 */
$mode = 'final';
$jenis = $surat['jenis_surat'];

/**
 * DATA FINAL
 */
$data = [
    'nama'        => $surat['nama'],
    'desa'        => 'Suka Maju',
    'kepala_desa' => 'Budi Santoso'
];

extract($data);

/**
 * RENDER PDF FINAL
 */
ob_start();
require "../views/surat/$jenis.php";
require "../views/surat/penutup.php";
$html = ob_get_clean();

$pdf = new Dompdf();
$pdf->loadHtml($html);
$pdf->setPaper('A4', 'portrait');
$pdf->render();
$pdf->stream("FINAL_$jenis.pdf", ["Attachment" => false]);
