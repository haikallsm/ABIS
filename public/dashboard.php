<?php
// 1. Config & Database
require_once __DIR__ . '/../config/database.php';

// Definisi BASE_URL biar tidak error
define('BASE_URL', '/public');

// 2. Mockup User Login (Karena belum ada sistem login, kita pura-pura sudah login)
$current_user = [
    'full_name' => 'Andi Saputra', // Ganti sesuai keinginan
    'username'  => 'andisaputra',
    'email'     => 'andi@desa.id',
    'phone'     => '081234567890',
    'address'   => 'Dusun I, RT 01 RW 02, Desa Suka Maju'
];

// 3. Data Jenis Surat (Untuk menu "Buat Surat")
// Kita samakan kodenya dengan database.php/helpers sebelumnya
$letter_types = [
    ['code' => 'keterangan_domisili', 'name' => 'Keterangan Domisili', 'description' => 'Untuk keperluan KTP/KK/Bank'],
    ['code' => 'keterangan_tidak_mampu', 'name' => 'Keterangan Tidak Mampu', 'description' => 'Untuk Beasiswa/Bantuan'],
    ['code' => 'keterangan_usaha', 'name' => 'Keterangan Usaha', 'description' => 'Untuk KUR/Izin Usaha'],
    ['code' => 'belum_menikah', 'name' => 'Belum Menikah', 'description' => 'Syarat Administrasi Nikah'],
    ['code' => 'izin_kegiatan', 'name' => 'Izin Kegiatan', 'description' => 'Keramaian/Acara'],
    ['code' => 'izin_usaha', 'name' => 'Izin Usaha', 'description' => 'Mikro/Kecil'],
    ['code' => 'rekomendasi_beasiswa', 'name' => 'Rekomendasi Beasiswa', 'description' => 'Pendidikan'],
];

// 4. Ambil Riwayat Surat dari Database (surat_log)
// Kita mapping kolom dari 'surat_log' agar cocok dengan tampilan dashboard kamu
try {
    $stmt = $pdo->query("SELECT * FROM surat_log ORDER BY id DESC");
    $raw_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $all_requests = [];
    foreach ($raw_requests as $row) {
        // Kita format ulang datanya biar sesuai sama View kamu
        $all_requests[] = [
            'id' => $row['id'],
            'letter_type_name' => ucwords(str_replace('_', ' ', $row['jenis_surat'])), // ubah keterangan_domisili jadi Keterangan Domisili
            'letter_type_code' => $row['jenis_surat'],
            'created_at' => $row['tanggal'],
            'status' => 'completed', // Karena sistem kita langsung jadi PDF, statusnya otomatis completed
            'admin_notes' => 'Surat otomatis diterbitkan sistem',
            'file_path' => $row['nomor_surat'] // Atau path PDF jika disimpan
        ];
    }

    // Ambil 5 data terakhir untuk "Riwayat Terbaru"
    $recent_requests = array_slice($all_requests, 0, 5);

} catch (Exception $e) {
    die("Gagal koneksi database: " . $e->getMessage());
}

// 5. Helper Icon (Supaya tidak error saat dipanggil $this->getIconForType)
// Karena kita tidak pakai Class, kita bikin object dummy
class ViewHelper {
    public function getIconForType($code) {
        if (strpos($code, 'usaha') !== false) return 'store';
        if (strpos($code, 'domisili') !== false) return 'home';
        if (strpos($code, 'nikah') !== false) return 'heart';
        if (strpos($code, 'sekolah') !== false || strpos($code, 'beasiswa') !== false) return 'graduation-cap';
        return 'file-alt';
    }
}
$this_helper = new ViewHelper();
// Trik agar $this->getIconForType() di view bisa jalan, 
// kita ganti pemanggilan di view nanti atau kita akali sedikit view-nya.
// TAPI: Cara paling gampang, kita ganti variabel $this di view kamu menjadi $viewHelper

// 6. Panggil View Dashboard
// Pastikan path ini sesuai dengan lokasi file view kamu
require_once __DIR__ . '/../app/views/user/dashboard.php';
?>