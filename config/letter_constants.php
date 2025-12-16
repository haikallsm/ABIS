<?php
/**
 * Letter Template Constants
 * Contains default values and configurations for letter templates
 * ABIS - Aplikasi Desa Digital
 */

// Village/Regional Information
define('DEFAULT_KABUPATEN', 'Magelang');
define('DEFAULT_KECAMATAN', 'Grabag');
define('DEFAULT_DESA', 'Kleteran');
define('DEFAULT_ALAMAT_DESA', 'Jl. Telaga Bleder Km.1 Grabag Magelang');
define('DEFAULT_KEPALA_DESA', 'Muhammad Waris Zainal');

// Default Values for Templates
define('DEFAULT_WARGANEGARA', 'WNI');
define('DEFAULT_KEPERLUAN', 'PERSYARATAN ADMINISTRASI');
define('DEFAULT_PEKERJAAN', 'WIRASWASTA');
define('DEFAULT_JENIS_USAHA', 'PERDAGANGAN');
define('DEFAULT_NAMA_KEGIATAN', 'HAJATAN PERNIKAHAN');
define('DEFAULT_HARI_KEGIATAN', 'Minggu');
define('DEFAULT_WAKTU_KEGIATAN', '08.00 WIB s/d Selesai');
define('DEFAULT_HIBURAN', '-');
define('DEFAULT_NAMA_BEASISWA', 'BEASISWA PENDIDIKAN');

// Template-specific defaults
define('BUSINESS_CERTIFICATE_PLACEHOLDER', '..............................');
define('SCHOOL_PLACEHOLDER', '..............................');
define('BUSINESS_START_YEAR', 'TAHUN ....');
define('BUSINESS_PURPOSE', 'DOMISILI');

// Common template data arrays
// getDefaultTemplateData() moved to LetterService

// Template type mappings
function getTemplateTypeMapping() {
    return [
        'surat_keterangan' => 'Surat Keterangan',
        'surat_keterangan_tidak_mampu' => 'Surat Keterangan Tidak Mampu',
        'surat_keterangan_domisili' => 'Surat Keterangan Domisili',
        'surat_keterangan_belum_menikah' => 'Surat Keterangan Belum Menikah',
        'surat_keterangan_usaha' => 'Surat Keterangan Usaha',
        'surat_rekomendasi_beasiswa' => 'Surat Rekomendasi Beasiswa',
        'surat_izin_usaha' => 'Surat Izin Usaha',
        'surat_izin_kegiatan' => 'Surat Izin Kegiatan',
    ];
}

// Letter number format
define('LETTER_NUMBER_FORMAT', '%s/%s/%s/%s'); // kode_desa/sequential/roman_month/year

// Roman month mapping
function getRomanMonths() {
    return [
        1 => 'I', 'II', 'III', 'IV', 'V', 'VI',
        'VII', 'VIII', 'IX', 'X', 'XI', 'XII'
    ];
}

// File paths
define('TEMPLATE_DIR', __DIR__ . '/../templates/');
define('LOGO_PATH', TEMPLATE_DIR . 'logo.png');
define('STAMP_PATH', TEMPLATE_DIR . 'stempel.png');
define('GENERATED_PDFS_DIR', __DIR__ . '/../generated_pdfs/');

// PDF settings
define('PDF_DEFAULT_FONT', 'Arial');
define('PDF_DEFAULT_SIZE', 'A4');
define('PDF_DEFAULT_ORIENTATION', 'portrait');

// Error messages
define('ERROR_REQUEST_NOT_FOUND', 'Pengajuan surat tidak ditemukan');
define('ERROR_LETTER_TYPE_NOT_FOUND', 'Jenis surat tidak ditemukan');
define('ERROR_PDF_GENERATION_FAILED', 'Gagal membuat file PDF');
define('ERROR_FILE_NOT_FOUND', 'File PDF tidak ditemukan');
define('ERROR_REQUEST_NOT_APPROVED', 'Surat hanya dapat didownload jika sudah disetujui');
?>


