<?php
// Set page metadata
$title = 'Export Data';
$extra_css = ['admin-export.css'];
$extra_js = ['admin-export.js'];
?>

<div id="appLayout" class="flex min-h-screen">
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
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </div>

                <div class="px-6">
                    <p class="sidebar-section-title uppercase tracking-wider mb-3">Pengelolaan Data</p>
                    <a href="<?php echo BASE_URL; ?>/admin/users" class="sidebar-link flex items-center py-4">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20h-5v-2a3 3 0 00-5.356-1.857M9 20v-2a3 3 0 015.548-1.077M10 11a2 2 0 100-4 2 2 0 000 4zm7 0a2 2 0 100-4 2 2 0 000 4zM10 17a5 5 0 008.274 2.87M15 17a5 5 0 008.274 2.87"></path>
                        </svg>
                        <span>Users</span>
                    </a>

                    <a href="<?php echo BASE_URL; ?>/admin/export" class="sidebar-link active flex items-center py-4">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v4a1 1 0 001 1h6a1 1 0 001-1V7m0 0a2 2 0 00-2-2H9a2 2 0 00-2 2m0 0v11a2 2 0 002 2h4a2 2 0 002-2V7m-4-2H8"></path>
                        </svg>
                        <span>Export Data</span>
                    </a>
                </div>
                <div class="px-6">
                    <p class="sidebar-section-title uppercase tracking-wider mb-3">Surat & Dokumen</p>

                    <a href="<?php echo BASE_URL; ?>/admin/letter-types" class="sidebar-link flex items-center py-4">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Jenis Surat</span>
                    </a>

                    <a href="<?php echo BASE_URL; ?>/admin/requests" class="sidebar-link flex items-center py-4">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2m-6 0h6"></path>
                        </svg>
                        <span>Pengajuan Surat</span>
                    </a>
                </div>
            </nav>

            <!-- Logout sederhana di bawah -->
            <div class="px-6 mt-auto">
                <div class="logout-container">
                    <form method="POST" action="<?php echo BASE_URL; ?>/logout" class="inline-block w-full">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        <a href="#" onclick="this.closest('form').submit(); return false;" class="logout-link">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
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
                                    Export Data
                                </h1>
                                <!-- Garis dekoratif bawah judul -->
                                <div class="h-1.5 w-32 mt-2 bg-linear-to-r from-primary/60 to-secondary/40 rounded-full"></div>
                            </div>

                            <!-- Badge jumlah data -->
                            <div class="px-4 py-1.5 bg-linear-to-r from-accent/20 to-highlight/20 rounded-full border border-accent/30">
                                <span class="text-sm font-semibold text-dark flex items-center gap-1.5">
                                    <span class="w-2 h-2 bg-accent rounded-full animate-pulse"></span>
                                    <span class="bg-linear-to-r from-dark to-primary bg-clip-text text-transparent">3 Data Surat</span>
                                </span>
                            </div>
                        </div>

                        <p class="text-gray-600 mt-3 pl-1 text-lg font-medium">
                            Ekspor data surat dengan filter yang fleksibel
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
                </div>
            </div>
        </div>

        <!-- Konten utama -->
        <div class="scroll-container">
            <div class="max-w-7xl mx-auto px-6 pb-6">

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

                    <!-- Jenis Surat Filter -->
                    <p class="text-base font-medium text-gray-700 mb-4 border-b pb-2 mt-6">Filter berdasarkan jenis surat</p>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                        <div class="flex items-center">
                            <input type="checkbox" id="jenis_keterangan" class="w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary/50">
                            <label for="jenis_keterangan" class="ml-2 text-sm text-gray-700">Surat Keterangan</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="jenis_pengantar" class="w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary/50">
                            <label for="jenis_pengantar" class="ml-2 text-sm text-gray-700">Surat Pengantar</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="jenis_lainnya" class="w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary/50">
                            <label for="jenis_lainnya" class="ml-2 text-sm text-gray-700">Lainnya</label>
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
                        <button id="btn-export-pdf" class="px-6 py-3 bg-linear-to-r from-accent to-highlight text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            Export PDF
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
