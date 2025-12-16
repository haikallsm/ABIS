<?php

/**
 * CETAK SURAT - Generate PDF dari Database
 * Mengambil data dari database berdasarkan request ID
 * ABIS - Aplikasi Desa Digital
 */

// Pastikan path ini sesuai struktur folder Anda
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/models/LetterRequest.php';
require_once __DIR__ . '/../app/models/LetterType.php';
require_once __DIR__ . '/../app/models/User.php';
require_once __DIR__ . '/../utils/PDFGenerator.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;

initApp();

// Cek authentication
requireAuth('admin');

// Ambil request ID dari URL parameter
$requestId = $_GET['id'] ?? 0;

if (!$requestId) {
    die('Error: Request ID tidak ditemukan');
}

// Inisialisasi models
$letterRequestModel = new LetterRequest();
$userModel = new User();

// Ambil data request dari database
$request = $letterRequestModel->findById($requestId);

if (!$request) {
    die('Error: Data permohonan tidak ditemukan');
}

if ($request['status'] !== 'approved') {
    die('Error: Surat hanya dapat dicetak jika sudah disetujui');
}

// Ambil data user profile
$user = $userModel->findById($request['user_id']);

if (!$user) {
    die('Error: Data user tidak ditemukan');
}

/**
 * 1. KUMPULKAN SEMUA DATA DARI DATABASE
 */

// Data dari request (sudah di-decode JSON)
$requestData = $request;

// Data user profile
$userData = [
    'nama'          => $user['full_name'] ?? '',
    'nik'           => $user['nik'] ?? '',
    'tempat_lahir'  => $user['birth_place'] ?? '',
    'tanggal_lahir' => $user['birth_date'] ?? '',
    'jenis_kelamin' => $user['gender'] ?? 'Laki-laki',
    'agama'         => $user['religion'] ?? 'Islam',
    'pekerjaan'     => $user['occupation'] ?? 'Wiraswasta',
    'warganegara'   => $user['nationality'] ?? 'WNI',
    'alamat'        => $user['address'] ?? '',
];

// Gabungkan semua data
$data = array_merge($requestData, $userData);

// Tentukan jenis surat berdasarkan letter_type_id dengan mapping yang lebih aman
$templateMap = [
    1 => 'surat_keterangan_domisili',
    2 => 'surat_keterangan_usaha',
    3 => 'surat_keterangan_tidak_mampu',
    4 => 'surat_keterangan', // Surat Pengantar Nikah menggunakan template umum
];

$jenis = $templateMap[$request['letter_type_id']] ?? 'surat_keterangan';

// Debug: tampilkan informasi
error_log("Cetak Surat - Request ID: {$requestId}, Letter Type ID: {$request['letter_type_id']}, Template: {$jenis}");

// Ekstrak array ke variabel agar bisa dipanggil di template
extract($data);

/**
 * 2. FORMAT TANGGAL
 */

// Format tanggal lahir
if (!empty($tanggal_lahir)) {
    try {
        $dateObj = DateTime::createFromFormat('Y-m-d', $tanggal_lahir);
        if ($dateObj) {
            $tanggal_lahir_formatted = $dateObj->format('d-m-Y');
        }
    } catch (Exception $e) {
        $tanggal_lahir_formatted = $tanggal_lahir;
    }
}

/**
 * 3. GENERATE NOMOR SURAT
 */

// Gunakan nomor surat yang sudah ada atau generate baru
$nomor_surat = $request['letter_number'] ?? '470 / ' . rand(100, 999) . ' / ' . date('Y');

/**
 * 4. DATA STATIS DESA
 */
$desa = 'Terong Tawah';
$kecamatan = 'Labuapi';
$kabupaten = 'Lombok Barat';
$alamat_desa = 'Jl. TGH. Mansyur - Kode Pos 83361';
$kepala_desa = 'Muhammad Waris Zainal, S.Pd.';

/**
 * 5. RENDER PDF
 */

ob_start();

// Include template surat
$templatePath = __DIR__ . '/../templates/' . $jenis . '.php';

if (file_exists($templatePath)) {
    include $templatePath;
} else {
    echo "<h1>Error: Template surat '$jenis' tidak ditemukan.</h1>";
    echo "<p>Path: $templatePath</p>";
}

$html = ob_get_clean();

// Konfigurasi DomPDF
$options = new \Dompdf\Options();
$options->set('isRemoteEnabled', true);
$options->set('isHtml5ParserEnabled', true);
$options->set('defaultFont', 'Arial');

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Output PDF
$filename = "SURAT_" . strtoupper(str_replace('surat_', '', $jenis)) . "_" . $requestId . "_" . time() . ".pdf";
$dompdf->stream($filename, ["Attachment" => false]);

?>
