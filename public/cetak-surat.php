<?php
require_once __DIR__ . '/../helpers/nomor_surat.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;

// 1️⃣ ambil jenis surat
$jenis = $_GET['jenis'] ?? 'keterangan_domisili';

// 2️⃣ data dummy (sementara)
$data = [
    'nama'       => $_GET['nama'] ?? '',
    'nik'        => $_GET['nik'] ?? '',
    'alamat'     => $_GET['alamat'] ?? '',
    'keperluan'  => $_GET['keperluan'] ?? '',
    'desa'       => 'Suka Maju'
];


// extract supaya $nama tersedia
extract($data);

// 3️⃣ generate nomor surat
$nomor_surat = generateNomorSurat($jenis);

// 4️⃣ SIMPAN KE DATABASE (INI DIA)
insert('surat_log', [
    'nomor_surat' => $nomor_surat,
    'jenis_surat' => $jenis,
    'nama'        => $nama,
    'tanggal'     => date('Y-m-d')
]);

// 5️⃣ render PDF
ob_start();
require "../views/surat/$jenis.php";
$html = ob_get_clean();

$pdf = new Dompdf();
$pdf->loadHtml($html);
$pdf->setPaper('A4', 'portrait');
$pdf->render();
$pdf->stream($jenis . ".pdf", ["Attachment" => false]);
