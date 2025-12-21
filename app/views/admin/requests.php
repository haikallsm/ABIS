<?php
// Set page metadata
$title = 'Export Data';
$extra_css = ['admin-export.css'];
$extra_js = ['admin-export'];
?>

<body class="bg-gray-100 antialiased">
<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside class="w-72 sidebar flex flex-col">

        <!-- sidebar header removed to show menu immediately -->
        <div class="flex-1 sidebar-nav py-2">
            <nav class="space-y-1">
                <div class="px-6">
                    <button id="sidebarToggle" onclick="toggleSidebar()" class="p-2 rounded-md bg-white/10 hover:bg-white/20 text-white focus:outline-none mr-3 inline-flex items-center" aria-label="Toggle sidebar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <h2 class="text-2xl font-extrabold mb-2 text-white inline-block align-middle">Surat - In</h2>
                    <p class="sidebar-section-title uppercase tracking-wider mb-3">Menu Utama</p>

                    <a href="<?php echo BASE_URL; ?>/admin/dashboard" class="sidebar-link flex items-center py-4">
                        <img src="/public/assets/icons/home.svg" alt="Menu" class="w-5 h-5" />
                        <span>Dashboard</span>
                    </a>
                </div>

                <div class="px-6">
                    <p class="sidebar-section-title uppercase tracking-wider mb-3">Pengelolaan Data</p>

                    <a href="<?php echo BASE_URL; ?>/admin/users" class="sidebar-link flex items-center py-4">
                        <img src="/public/assets/icons/users.svg" alt="Menu" class="w-5 h-5" />
                        <span>Users</span>
                    </a>

                    <a href="<?php echo BASE_URL; ?>/admin/requests" class="sidebar-link active flex items-center py-4">
                        <img src="/public/assets/icons/data-storage.svg" alt="Menu" class="w-5 h-5" />
                        <span>Export Data</span>
                    </a>
                </div>

                <div class="px-6">
                    <p class="sidebar-section-title uppercase tracking-wider mb-3">Surat & Dokumen</p>

                    <a href="<?php echo BASE_URL; ?>/admin/letter-requests" class="sidebar-link flex items-center py-4">
                        <img src="/public/assets/icons/inbox.svg" alt="Menu" class="w-5 h-5" />
                        <span>Pengajuan Surat</span>
                    </a>

                    <a href="<?php echo BASE_URL; ?>/admin/telegram-settings" class="sidebar-link flex items-center py-4">
                        <img src="/public/assets/icons/message.svg" alt="Menu" class="w-5 h-5" />
                        <span>Telegram Bot</span>
                    </a>
                </div>

            </nav>

            <!-- Logout sederhana di bawah -->
            <div class="px-6 mt-auto">
                <div class="logout-container">
                    <form method="POST" action="<?php echo BASE_URL; ?>/logout" class="inline-block w-full">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        <a href="#" onclick="this.closest('form').submit(); return false;" class="logout-link">
                            <img src="/public/assets/icons/logout.svg" alt="Logout" class="w-5 h-5" />
                            <span class="text-xs">Logout</span>
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="flex-1 flex flex-col overflow-hidden main-panel">

        <!-- HEADER JUDUL YANG LEBIH MENARIK -->
        <div class="sticky-header-container">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex items-center gap-5 mb-2">
                    <!-- Icon dekoratif dengan gradient -->
                    <div class="p-3 rounded-2xl bg-linear-to-br from-primary/20 to-secondary/20 border-2 border-white/50 shadow-lg backdrop-blur-sm">
                        <div class="p-3 rounded-xl bg-linear-to-br from-primary to-secondary shadow-inner">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v4a1 1 0 001 1h6a1 1 0 001-1V7m0 0a2 2 0 00-2-2H9a2 2 0 00-2 2m0 0v11a2 2 0 002 2h4a2 2 0 002-2V7m-4-2H8"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Judul dengan efek menarik -->
                    <div class="flex-1">
                        <div class="flex items-center gap-4">
                            <div>
                                <h1 class="text-3xl font-bold bg-linear-to-r from-primary via-primary-light to-secondary bg-clip-text text-transparent tracking-tight">
                                    Export Data Surat
                                </h1>
                                <!-- Garis dekoratif bawah judul -->
                                <div class="h-1.5 w-40 mt-2 bg-linear-to-r from-primary/60 to-secondary/40 rounded-full"></div>
                            </div>

                            <!-- Badge jumlah data -->
                            <div class="px-4 py-1.5 bg-linear-to-r from-accent/20 to-highlight/20 rounded-full border border-accent/30">
                                <span class="text-sm font-semibold text-dark flex items-center gap-1.5">
                                    <span class="w-2 h-2 bg-accent rounded-full animate-pulse"></span>
                                    <span class="bg-linear-to-r from-dark to-primary bg-clip-text text-transparent"><?php echo count($recent_requests ?? []); ?> Data Surat</span>
                                </span>
                            </div>
                        </div>

                        <p class="text-gray-600 mt-3 pl-1 text-lg font-medium">
                            Ekspor data surat dengan filter yang fleksibel dan lengkap
                        </p>
                    </div>
                </div>

                <!-- Dekorasi tambahan -->
                <div class="flex items-center gap-6 mt-4">
                    <div class="flex items-center gap-2 text-sm">
                        <div class="w-3 h-3 rounded-full bg-primary animate-pulse"></div>
                        <span class="text-gray-600 font-medium">Surat Keterangan</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm">
                        <div class="w-3 h-3 rounded-full bg-secondary animate-pulse"></div>
                        <span class="text-gray-600 font-medium">Surat Pengantar</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm">
                        <div class="w-3 h-3 rounded-full bg-accent animate-pulse"></div>
                        <span class="text-gray-600 font-medium">Data Lengkap</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- SCROLLABLE CONTENT SECTION -->
        <div class="scroll-container">
            <div class="max-w-7xl mx-auto px-6 py-6">

                <!-- Flash Messages -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Filter Section -->
                <div class="cream-card p-8 mb-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-gray-800">Filter Data Surat</h2>
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Pilih periode waktu untuk mengekspor data</span>
                        </div>
                    </div>

                    <p class="text-base font-medium text-gray-700 mb-4 border-b pb-2">Filter berdasarkan tanggal</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                        <div>
                            <label for="dari_tanggal" class="block text-sm font-medium text-gray-600 mb-2">Dari Tanggal</label>
                            <div class="relative">
                                <input type="date" id="dari_tanggal" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition duration-300">
                                <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="sampai_tanggal" class="block text-sm font-medium text-gray-600 mb-2">Sampai Tanggal</label>
                            <div class="relative">
                                <input type="date" id="sampai_tanggal" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition duration-300">
                                <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label for="pilih_bulan" class="block text-sm font-medium text-gray-600 mb-2">Atau pilih bulan</label>
                            <div class="relative">
                                <select id="pilih_bulan" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition duration-300 appearance-none">
                                    <option value="">— Pilih Bulan —</option>
                                    <option value="01">Januari</option>
                                    <option value="02">Februari</option>
                                    <option value="03">Maret</option>
                                    <option value="04">April</option>
                                    <option value="05">Mei</option>
                                    <option value="06">Juni</option>
                                    <option value="07">Juli</option>
                                    <option value="08">Agustus</option>
                                    <option value="09">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                </select>
                                <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="mt-8 pt-6 border-t flex space-x-4">
                        <button id="btn-tampilkan" class="px-6 py-3 bg-linear-to-r from-primary to-primary-light text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Tampilkan Data
                        </button>
                        <button id="btn-export-excel" class="px-6 py-3 bg-linear-to-r from-secondary to-accent text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Export Excel
                        </button>
                    </div>
                </div>

                <!-- Hasil Data -->
                <div id="data-result" class="cream-card p-6 hidden">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-gray-800">Hasil Pencarian Surat</h3>
                        <div class="text-sm text-gray-500 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Total: <span class="font-bold text-primary" id="total-count">0</span> data ditemukan</span>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-linear-to-r from-gray-50 to-gray-100">
                                    <th class="px-6 py-4 w-16 text-sm font-bold text-gray-700 text-center border-r border-gray-200">No</th>
                                    <th class="px-6 py-4 text-sm font-bold text-gray-700 border-r border-gray-200">Tanggal</th>
                                    <th class="px-6 py-4 text-sm font-bold text-gray-700 border-r border-gray-200">Jenis Surat</th>
                                    <th class="px-6 py-4 text-sm font-bold text-gray-700 border-r border-gray-200">Pemohon</th>
                                    <th class="px-6 py-4 w-48 text-sm font-bold text-gray-700 text-center border-r border-gray-200">Status</th>
                                </tr>
                            </thead>
                            <tbody id="data-table-body" class="divide-y divide-gray-100">
                                <!-- Data will be populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 pt-4 border-t flex justify-between items-center text-sm text-gray-600">
                        <div class="flex items-center gap-4">
                            <span class="font-medium">Tampilkan:</span>
                            <select id="rows-per-page" class="border border-gray-300 rounded px-2 py-1">
                                <option value="10">10 baris</option>
                                <option value="25">25 baris</option>
                                <option value="50">50 baris</option>
                            </select>
                        </div>
                        <div class="text-xs text-gray-500">
                            Data akan diekspor sesuai dengan filter yang dipilih
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>

<script>
// Make BASE_URL available to JavaScript
const BASE_URL = '<?php echo BASE_URL; ?>';

// Toggle sidebar function
function toggleSidebar() {
    var app = document.getElementById('appLayout');
    if (!app) return;
    app.classList.toggle('sidebar-collapsed');
    // Simpan state sidebar di localStorage
    if (app.classList.contains('sidebar-collapsed')) {
        localStorage.setItem('sidebar-collapsed', 'true');
    } else {
        localStorage.setItem('sidebar-collapsed', 'false');
    }
}

// Cek state sidebar saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    if (localStorage.getItem('sidebar-collapsed') === 'true') {
        document.getElementById('appLayout').classList.add('sidebar-collapsed');
    }

    // Set default dates
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('dari_tanggal').value = today;
    document.getElementById('sampai_tanggal').value = today;

    // Handle export buttons
    document.getElementById('btn-export-excel').addEventListener('click', function(e) {
        e.preventDefault();
        exportToExcel();
    });

});

function exportToExcel() {
    // Get filter values
    const dariTanggal = document.getElementById('dari_tanggal').value;
    const sampaiTanggal = document.getElementById('sampai_tanggal').value;

    // Build query parameters
    const params = new URLSearchParams();
    if (dariTanggal) params.append('dari_tanggal', dariTanggal);
    if (sampaiTanggal) params.append('sampai_tanggal', sampaiTanggal);

    // Note: Letter type filters (jenis_keterangan, jenis_pengantar, jenis_lainnya)
    // were removed from the UI but the functionality still works without them

    // Create download link
    const url = `${BASE_URL}/admin/export/excel?${params.toString()}`;

    // Create temporary link and trigger download
    const link = document.createElement('a');
    link.href = url;
    link.style.display = 'none';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
