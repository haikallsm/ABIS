<?php
// Set page metadata
$title = 'Beranda';
$extra_css = ['homepage.css'];
$extra_js = ['homepage.js'];
?>

<!-- HERO SECTION dengan Background Foto Dominan -->
<section class="hero-bg text-gray-800 py-24">
    <div class="container mx-auto px-6 text-center">
        <div class="floating mb-8">
            <div class="w-24 h-24 mx-auto bg-white bg-opacity-90 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-xl">
                <i class="fas fa-file-contract text-primary text-3xl"></i>
            </div>
        </div>

        <h1 class="text-4xl md:text-5xl font-bold mb-6">
            Selamat Datang di
            <span class="text-primary">ABIS</span>
        </h1>

        <p class="text-xl mb-8 max-w-2xl mx-auto bg-white bg-opacity-70 backdrop-blur-sm py-3 px-6 rounded-lg">
            Aplikasi Desa Digital - Sistem pengelolaan surat menyurat desa secara terintegrasi
            <br>
            <span class="text-lg text-natural-light">Modern • Terpercaya • Efisien</span>
        </p>

        <?php if (!isLoggedIn()): ?>
            <button data-action="redirect-login"
                    class="btn-official px-12 py-4 rounded-xl font-semibold text-lg shadow-lg">
                <i class="fas fa-user-lock mr-3"></i>
                Login untuk Mengajukan Surat
            </button>
        <?php else: ?>
            <div class="flex justify-center space-x-4">
                <?php if (isAdmin()): ?>
                    <a href="<?php echo BASE_URL; ?>/admin/dashboard"
                       class="btn-official px-8 py-4 rounded-xl font-semibold text-lg shadow-lg">
                        <i class="fas fa-cog mr-3"></i>
                        Dashboard Admin
                    </a>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>/dashboard"
                       class="btn-official px-8 py-4 rounded-xl font-semibold text-lg shadow-lg">
                        <i class="fas fa-file-alt mr-3"></i>
                        Dashboard User
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- SHOWCASE DESA -->
<section class="village-showcase text-gray-800 py-20">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto text-center bg-white bg-opacity-80 backdrop-blur-sm p-8 rounded-2xl shadow-lg">
            <h2 class="text-3xl font-bold mb-6">Desa Digital Terintegrasi</h2>
            <p class="text-lg mb-8 text-natural">
                ABIS menghadirkan kemudahan layanan administrasi yang modern tanpa meninggalkan
                nilai-nilai kearifan lokal. Sistem terintegrasi untuk pengelolaan surat menyurat
                yang efisien dan transparan.
            </p>
            <div class="grid grid-cols-3 gap-4 mt-8">
                <div class="bg-white bg-opacity-90 p-4 rounded-lg border border-gray-200">
                    <i class="fas fa-rocket text-primary text-2xl mb-2"></i>
                    <p class="font-semibold text-natural">Proses Kilat</p>
                </div>
                <div class="bg-white bg-opacity-90 p-4 rounded-lg border border-gray-200">
                    <i class="fas fa-shield-alt text-primary text-2xl mb-2"></i>
                    <p class="font-semibold text-natural">Aman & Terpercaya</p>
                </div>
                <div class="bg-white bg-opacity-90 p-4 rounded-lg border border-gray-200">
                    <i class="fas fa-users text-primary text-2xl mb-2"></i>
                    <p class="font-semibold text-natural">Layanan Prima</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- KONTEN UTAMA -->
<div class="container mx-auto px-6 py-12">

    <!-- LAYANAN SURAT DIGITAL SECTION -->
    <section class="mb-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-natural mb-4">Layanan Surat Digital</h2>
            <p class="text-natural-light text-lg">Pilih jenis layanan surat yang Anda butuhkan</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Surat Keterangan -->
            <div class="service-card section-card p-8 soft-bg">
                <div class="flex items-start mb-6">
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mr-5 soft-shadow border">
                        <i class="fas fa-file-alt text-primary text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-natural mb-2">Surat Keterangan</h3>
                        <span class="badge">Paling Banyak Diminta</span>
                    </div>
                </div>
                <ul class="text-natural-light space-y-3 text-sm">
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        Surat Keterangan Domisili
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        Surat Keterangan Tidak Mampu
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        Surat Keterangan Usaha
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        Surat Keterangan Belum Menikah
                    </li>
                </ul>
            </div>

            <!-- Surat Izin -->
            <div class="service-card section-card p-8 soft-bg">
                <div class="flex items-start mb-6">
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mr-5 soft-shadow border">
                        <i class="fas fa-clipboard-check text-primary text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-natural mb-2">Surat Izin</h3>
                        <span class="badge">Perizinan Resmi</span>
                    </div>
                </div>
                <ul class="text-natural-light space-y-3 text-sm">
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        Surat Izin Keramaian
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        Surat Izin Kegiatan
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        Surat Izin Usaha
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        Surat Izin Penggunaan Tanah
                    </li>
                </ul>
            </div>

            <!-- Surat Rekomendasi -->
            <div class="service-card section-card p-8 soft-bg">
                <div class="flex items-start mb-6">
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mr-5 soft-shadow border">
                        <i class="fas fa-star text-primary text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-natural mb-2">Surat Rekomendasi</h3>
                        <span class="badge">Dukungan Resmi</span>
                    </div>
                </div>
                <ul class="text-natural-light space-y-3 text-sm">
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        Surat Rekomendasi Beasiswa
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        Surat Rekomendasi Pekerjaan
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        Surat Rekomendasi Studi
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        Surat Rekomendasi Lainnya
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <!-- DASHBOARD STATISTIK -->
    <section class="mb-16 stats-section">
        <div class="section-card p-8 soft-bg">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-natural mb-4">Dashboard Statistik Layanan</h2>
                <p class="text-natural-light">Monitoring real-time layanan surat ABIS</p>
            </div>

            <!-- Stats Grid -->
            <div class="grid md:grid-cols-4 gap-6 mb-12">
                <div class="stat-card p-6 rounded-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-3xl font-bold text-primary" data-counter="<?php echo $stats['total_users'] ?? 1247; ?>">0</div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-primary text-xl"></i>
                        </div>
                    </div>
                    <div class="text-natural-light text-sm">Warga Terdaftar</div>
                    <div class="text-green-600 text-sm font-medium mt-2">
                        <i class="fas fa-arrow-up mr-1"></i> 2.3% dari tahun lalu
                    </div>
                </div>

                <div class="stat-card p-6 rounded-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-3xl font-bold text-primary" data-counter="<?php echo $stats['total_requests'] ?? 245; ?>">0</div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file-alt text-primary text-xl"></i>
                        </div>
                    </div>
                    <div class="text-natural-light text-sm">Surat Diproses</div>
                    <div class="text-green-600 text-sm font-medium mt-2">
                        <i class="fas fa-arrow-up mr-1"></i> 15% bulan ini
                    </div>
                </div>

                <div class="stat-card p-6 rounded-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-3xl font-bold text-primary" data-counter="<?php echo $stats['completed_requests'] ?? 198; ?>">0</div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-primary text-xl"></i>
                        </div>
                    </div>
                    <div class="text-natural-light text-sm">Surat Selesai</div>
                    <div class="text-green-600 text-sm font-medium mt-2">
                        <i class="fas fa-arrow-up mr-1"></i> 12% bulan ini
                    </div>
                </div>

                <div class="stat-card p-6 rounded-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-3xl font-bold text-primary">98%</div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-smile text-primary text-xl"></i>
                        </div>
                    </div>
                    <div class="text-natural-light text-sm">Tingkat Kepuasan</div>
                    <div class="text-green-600 text-sm font-medium mt-2">
                        <i class="fas fa-star mr-1"></i> Sangat Baik
                    </div>
                </div>
            </div>

            <!-- Progress Charts -->
            <div class="grid lg:grid-cols-2 gap-8">
                <div class="bg-white p-6 rounded-lg soft-shadow border">
                    <h3 class="text-lg font-semibold text-natural mb-4">Distribusi Jenis Surat</h3>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="font-medium text-natural">Surat Keterangan</span>
                                <span class="font-semibold text-primary">45%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-3">
                                <div class="bg-primary h-3 rounded-full" style="width: 45%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="font-medium text-natural">Surat Izin</span>
                                <span class="font-semibold text-primary">30%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-3">
                                <div class="bg-secondary h-3 rounded-full" style="width: 30%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="font-medium text-natural">Surat Rekomendasi</span>
                                <span class="font-semibold text-primary">25%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-3">
                                <div class="bg-accent h-3 rounded-full" style="width: 25%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg soft-shadow border">
                    <h3 class="text-lg font-semibold text-natural mb-4">Status Pengajuan</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                                <span class="font-medium text-natural">Selesai</span>
                            </div>
                            <span class="font-semibold text-natural"><?php echo $stats['completed_requests'] ?? 198; ?> Surat</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                                <span class="font-medium text-natural">Dalam Proses</span>
                            </div>
                            <span class="font-semibold text-natural"><?php echo $stats['pending_requests'] ?? 32; ?> Surat</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                                <span class="font-medium text-natural">Menunggu</span>
                            </div>
                            <span class="font-semibold text-natural"><?php echo $stats['approved_requests'] ?? 15; ?> Surat</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FITUR LAYANAN -->
    <section class="mb-16">
        <div class="grid md:grid-cols-3 gap-8">
            <!-- Proses Kilat -->
            <div class="section-card p-8 text-center soft-bg">
                <div class="w-16 h-16 mx-auto mb-6 bg-white rounded-2xl flex items-center justify-center soft-shadow border">
                    <i class="fas fa-bolt text-primary text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-natural mb-4">Proses Kilat</h3>
                <p class="text-natural-light text-sm">
                    Surat diproses dalam 1-3 hari kerja dengan sistem yang efisien dan terintegrasi
                </p>
            </div>

            <!-- Pantau Online -->
            <div class="section-card p-8 text-center soft-bg">
                <div class="w-16 h-16 mx-auto mb-6 bg-white rounded-2xl flex items-center justify-center soft-shadow border">
                    <i class="fas fa-mobile-alt text-primary text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-natural mb-4">Pantau Online</h3>
                <p class="text-natural-light text-sm">
                    Pantau status pengajuan surat secara real-time melalui website resmi
                </p>
            </div>

            <!-- Download Digital -->
            <div class="section-card p-8 text-center soft-bg">
                <div class="w-16 h-16 mx-auto mb-6 bg-white rounded-2xl flex items-center justify-center soft-shadow border">
                    <i class="fas fa-download text-primary text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-natural mb-4">Download Digital</h3>
                <p class="text-natural-light text-sm">
                    Surat dapat diunduh dalam format PDF yang aman dan telah terverifikasi
                </p>
            </div>
        </div>
    </section>

    <!-- TABEL INFORMASI -->
    <section class="mb-16">
        <div class="section-card p-8 soft-bg">
            <h2 class="text-2xl font-bold text-natural mb-6">Informasi Layanan</h2>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-natural-light uppercase tracking-wider">Jenis Layanan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-natural-light uppercase tracking-wider">Waktu Proses</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-natural-light uppercase tracking-wider">Biaya</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-natural-light uppercase tracking-wider">Syarat</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($letter_types as $type): ?>
                        <tr class="table-row">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-natural"><?php echo htmlspecialchars($type['name']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-natural-light">1-3 Hari Kerja</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-natural-light">Gratis</td>
                            <td class="px-6 py-4 text-sm text-natural-light"><?php echo htmlspecialchars($type['description'] ?? 'Dokumen lengkap'); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- CTA FINAL -->
    <section>
        <div class="section-card p-12 text-center bg-linear-to-r from-primary to-dark text-white soft-shadow">
            <h2 class="text-3xl font-bold mb-6">Siap Mengajukan Surat?</h2>
            <p class="text-blue-100 mb-8 text-lg max-w-2xl mx-auto">
                Bergabung dengan pengguna ABIS yang telah merasakan kemudahan layanan surat digital
            </p>

            <?php if (!isLoggedIn()): ?>
                <button data-action="redirect-login"
                        class="bg-white text-primary px-12 py-4 rounded-xl font-semibold text-lg hover:bg-gray-100 transition shadow-lg">
                    <i class="fas fa-user-lock mr-3"></i>
                    Login untuk Memulai Pengajuan
                </button>
            <?php else: ?>
                <a href="<?php echo isAdmin() ? BASE_URL . '/admin/dashboard' : BASE_URL . '/dashboard'; ?>"
                   class="bg-white text-primary px-12 py-4 rounded-xl font-semibold text-lg hover:bg-gray-100 transition shadow-lg inline-block">
                    <i class="fas fa-rocket mr-3"></i>
                    Akses Dashboard
                </a>
            <?php endif; ?>

            <p class="text-blue-100 text-sm mt-4">
                * Layanan resmi ABIS - Sistem Terintegrasi • Gratis untuk semua pengguna
            </p>
        </div>
    </section>

</div>
