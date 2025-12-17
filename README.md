# Surat-In - Sistem Pengelolaan Surat

Sistem pengelolaan surat berbasis web yang memungkinkan pengguna mengajukan berbagai jenis surat secara online dan admin untuk mengelola proses approval.

## Fitur Utama

### Untuk Pengguna
- Registrasi dan login akun
- Pengajuan surat dengan berbagai jenis (Surat Keterangan Domisili, Surat Keterangan Usaha, Surat Pengantar Nikah, Surat Keterangan Tidak Mampu, Surat Keterangan, Surat Keterangan Belum Menikah, Surat Rekomendasi Beasiswa, Surat Izin Usaha, Surat Izin Kegiatan)
- Form dinamis yang menyesuaikan dengan jenis surat yang dipilih
- Validasi form secara real-time
- Pelacakan status pengajuan surat
- Preview dan download surat dalam format PDF setelah disetujui
- Notifikasi via Telegram dengan file PDF surat

### Untuk Admin
- Dashboard untuk monitoring semua pengajuan surat
- Approval atau rejection pengajuan surat
- Generate PDF surat otomatis dengan template yang sesuai
- Kirim notifikasi ke pengguna via Telegram dengan melampirkan file PDF
- Manajemen jenis surat dan konfigurasi sistem
- Laporan dan statistik pengajuan surat

## Teknologi yang Digunakan

- **Backend**: PHP 7.4+ dengan arsitektur MVC native
- **Database**: MySQL untuk penyimpanan data
- **Frontend**: HTML5, CSS3, JavaScript vanilla (tanpa framework)
- **PDF Generation**: DomPDF untuk membuat dokumen PDF
- **Styling**: Tailwind CSS untuk UI/UX
- **Notification**: Telegram Bot API untuk pengiriman notifikasi
- **Authentication**: Session-based authentication
- **Security**: CSRF protection dan input sanitization
- **File Upload**: Upload dan management file PDF

## Struktur Project

```
ABIS_PBP/
├── app/
│   ├── controllers/     # Controller classes
│   ├── models/         # Model classes
│   └── views/          # Template files
├── config/             # Configuration files
├── public/             # Public assets (CSS, JS, images)
├── templates/          # PDF templates
├── utils/              # Utility classes
├── database/           # Database files
└── logs/              # Application logs
```

## Penggunaan

### Akses Aplikasi
- URL: http://localhost/ABIS_PBP
- Admin login: Gunakan akun admin yang telah dibuat
- User registration: Melalui halaman registrasi

### Workflow Pengajuan Surat
1. User login ke sistem
2. Pilih menu "Buat Surat"
3. Pilih jenis surat yang diinginkan
4. Isi form sesuai persyaratan
5. Submit pengajuan
6. Admin akan menerima notifikasi
7. Admin approve/reject pengajuan
8. User menerima notifikasi via Telegram dengan file PDF

## Lisensi

Project ini menggunakan lisensi MIT.

## Kontributor

- Development Team ABIS
