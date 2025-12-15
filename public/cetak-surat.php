<?php
// Pastikan path ini sesuai struktur folder Anda
// ABIS/public/cetak-surat.php
require_once __DIR__ . '/../helpers/nomor_surat.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;

/**
 * 1. SETTING DEFAULT
 */
$mode = 'preview'; // Saat pertama submit form, modenya preview (belum ada TTD)

/**
 * 2. TANGKAP DATA DARI FORM (Menggunakan Null Coalescing ?? '')
 */
$jenis = $_POST['jenis'] ?? 'keterangan_domisili';

// Kumpulkan semua kemungkinan data yang dikirim form
$data = [
    // --- Data Diri ---
    'nama'          => $_POST['nama'] ?? '',
    'nik'           => $_POST['nik'] ?? '',
    'tempat_lahir'  => $_POST['tempat_lahir'] ?? '',
    'tanggal_lahir' => $_POST['tanggal_lahir'] ?? '', // Format YYYY-MM-DD
    'jenis_kelamin' => $_POST['jenis_kelamin'] ?? '',
    'agama'         => $_POST['agama'] ?? '',
    'pekerjaan'     => $_POST['pekerjaan'] ?? '',
    'warganegara'   => $_POST['warganegara'] ?? 'WNI',
    'alamat'        => $_POST['alamat'] ?? '',
    
    // --- Data Khusus ---
    'keperluan'       => $_POST['keperluan'] ?? '',
    'alamat_domisili' => $_POST['alamat_domisili'] ?? '', // Domisili
    
    // Usaha
    'nama_usaha'    => $_POST['nama_usaha'] ?? '',
    'jenis_usaha'   => $_POST['jenis_usaha'] ?? '',
    'alamat_usaha'  => $_POST['alamat_usaha'] ?? '',
    'mulai_usaha'   => $_POST['mulai_usaha'] ?? '',
    'luas_usaha'    => $_POST['luas_usaha'] ?? '',
    
    // Kegiatan
    'nama_kegiatan'    => $_POST['nama_kegiatan'] ?? '',
    'tanggal_kegiatan' => $_POST['tanggal_kegiatan'] ?? '',
    'waktu_kegiatan'   => $_POST['waktu_kegiatan'] ?? '',
    'tempat_kegiatan'  => $_POST['tempat_kegiatan'] ?? '',
    'hiburan'          => $_POST['hiburan'] ?? '',
    
    // Beasiswa
    'nama_beasiswa' => $_POST['nama_beasiswa'] ?? '',
    'nama_ayah'     => $_POST['nama_ayah'] ?? '',
    'sekolah'       => $_POST['sekolah'] ?? '',
    'nis_nim'       => $_POST['nis_nim'] ?? '',
    'jurusan'       => $_POST['jurusan'] ?? '',
    'semester'      => $_POST['semester'] ?? '',

    // --- Data Statis Desa (Bisa diambil dari DB jika ada tabel profil_desa) ---
    'desa'        => 'Suka Maju',
    'kecamatan'   => 'Suka Makmur',
    'kabupaten'   => 'Lombok Barat',
    'kepala_desa' => 'Budi Santoso, S.Sos'
];

// Ekstrak array ke variabel ($nama, $nik, dll) agar bisa dipanggil di template
extract($data);

/**
 * 3. LOGIKA FORMAT TANGGAL
 */
// Ubah format tanggal lahir dari YYYY-MM-DD ke DD-MM-YYYY (Indo)
if (!empty($tanggal_lahir)) {
    $dateObj = DateTime::createFromFormat('Y-m-d', $tanggal_lahir);
    if ($dateObj) {
        $tanggal_lahir = $dateObj->format('d-m-Y');
    }
}
// Lakukan hal yang sama untuk tanggal kegiatan jika ada
if (!empty($tanggal_kegiatan)) {
    $dateObj = DateTime::createFromFormat('Y-m-d', $tanggal_kegiatan);
    if ($dateObj) {
        $bulanIndo = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        $hari_kegiatan = date('l', strtotime($tanggal_kegiatan)); // Hari Inggris (bisa ditranslate array manual)
        // Format cantik: 17 Agustus 2025
        $tanggal_kegiatan = $dateObj->format('d') . ' ' . $bulanIndo[(int)$dateObj->format('m')] . ' ' . $dateObj->format('Y');
    }
}

/**
 * 4. GENERATE NOMOR SURAT
 */
// Pastikan fungsi ini ada di helpers/nomor_surat.php
if (function_exists('generateNomorSurat')) {
    $nomor_surat = generateNomorSurat($jenis);
} else {
    $nomor_surat = '470 / ' . rand(100, 999) . ' / ' . date('Y');
}

/**
 * 5. SIMPAN LOG (Opsional - Sesuai kode lama Anda)
 */
// Pastikan fungsi insert() ada dan tabel 'surat_log' memiliki kolom yang sesuai
// Jika kolom belum lengkap, kode ini mungkin error. 
// Untuk amannya, saya komen dulu bagian insert yang kompleks.
/* insert('surat_log', [
    'nomor_surat' => $nomor_surat,
    'jenis_surat' => $jenis,
    'nama'        => $nama,
    'tanggal'     => date('Y-m-d'),
    'status'      => 'pending'
]); 
*/

/**
 * 6. RENDER PDF
 */
ob_start();

// Path absolut ke views agar aman
$viewPath = __DIR__ . '/../views/surat/';

// Include Kop
if (file_exists($viewPath . 'kop.php')) {
    include $viewPath . 'kop.php';
} else {
    echo "<h1>Error: File kop.php tidak ditemukan di $viewPath</h1>";
}

// Include Isi Surat
if (file_exists($viewPath . $jenis . '.php')) {
    include $viewPath . $jenis . '.php';
} else {
    echo "<h1>Error: Template surat '$jenis.php' tidak ditemukan.</h1>";
}

// Include Penutup
if (file_exists($viewPath . 'penutup.php')) {
    include $viewPath . 'penutup.php';
} else {
    echo "<h1>Error: File penutup.php tidak ditemukan.</h1>";
}

$html = ob_get_clean();

// Konfigurasi DomPDF
$options = new \Dompdf\Options();
$options->set('isRemoteEnabled', true); // Agar bisa load gambar/logo
$pdf = new Dompdf($options);

$pdf->loadHtml($html);
$pdf->setPaper('A4', 'portrait');
$pdf->render();

// Output PDF ke Browser
$pdf->stream("PREVIEW_" . strtoupper($jenis) . "_" . time() . ".pdf", ["Attachment" => false]);
?>