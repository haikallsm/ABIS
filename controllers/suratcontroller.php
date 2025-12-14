<?php
require_once __DIR__ . '/../helpers/nomor_surat.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;

// jenis surat dari form
$jenis = $_GET['jenis'] ?? '';

// generate nomor surat otomatis
$nomor_surat = generateNomorSurat($jenis);

// data dari form
$data = array_merge($_GET, [
    // data desa (nanti bisa dari DB)
    'desa'         => 'Suka Maju',
    'kecamatan'    => 'Maju Jaya',
    'kabupaten'    => 'Makmur',
    'alamat_desa'  => 'Jl. Merdeka No. 1',
    'kepala_desa'  => 'Budi Santoso',

    // nomor surat otomatis
    'nomor_surat'  => $nomor_surat
]);

// buffer HTML
ob_start();
extract($data);

require __DIR__ . "/../views/surat/kop.php";
require __DIR__ . "/../views/surat/{$jenis}.php";
require __DIR__ . "/../views/surat/penutup.php";

$html = ob_get_clean();

// render PDF
$pdf = new Dompdf();
$pdf->loadHtml($html);
$pdf->setPaper('A4', 'portrait');
$pdf->render();
$pdf->stream($jenis . ".pdf", ["Attachment" => false]);
