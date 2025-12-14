<?php
require_once __DIR__ . '/../helpers/nomor_surat.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;

/**
 * MODE PREVIEW (DEFAULT)
 */
$mode = 'preview';

/**
 * JENIS SURAT
 */
$jenis = $_POST['jenis'] ?? 'keterangan_domisili';

/**
 * DATA DARI FORM
 */
$data = [
    'nama'        => $_POST['nama'] ?? '',
    'nik'         => $_POST['nik'] ?? '',
    'alamat'      => $_POST['alamat'] ?? '',
    'keperluan'   => $_POST['keperluan'] ?? '',
    'sekolah'     => $_POST['sekolah'] ?? '',
    'desa'        => 'Suka Maju',
    'kepala_desa' => 'Budi Santoso'
];

extract($data);

/**
 * GENERATE NOMOR SURAT
 */
$nomor_surat = generateNomorSurat($jenis);

/**
 * SIMPAN KE DATABASE (PENDING)
 */
insert('surat_log', [
    'nomor_surat' => $nomor_surat,
    'jenis_surat' => $jenis,
    'nama'        => $nama,
    'tanggal'     => date('Y-m-d'),
    'status'      => 'pending'
]);

/**
 * RENDER PDF (TANPA TTD)
 */
ob_start();
require "../views/surat/$jenis.php";
require "../views/surat/penutup.php";
$html = ob_get_clean();

$pdf = new Dompdf();
$pdf->loadHtml($html);
$pdf->setPaper('A4', 'portrait');
$pdf->render();
$pdf->stream("PREVIEW_$jenis.pdf", ["Attachment" => false]);
