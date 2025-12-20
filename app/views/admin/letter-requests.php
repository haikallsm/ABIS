<?php
// Set page metadata
$title = 'Pengajuan Surat';
$extra_css = ['admin-dashboard.css'];
$extra_js = ['admin-requests'];
?>

<body class="cream-bg antialiased">
<style>
/* Disable hover effects pada tombol approve/reject untuk mencegah menghilang */
.force-visible:hover {
    opacity: 1 !important;
    display: inline-flex !important;
    visibility: visible !important;
    transform: none !important;
    box-shadow: none !important;
    background-color: inherit !important;
}

button[id^="approve-btn-"]:hover,
button[id^="reject-btn-"]:hover {
    background-color: inherit !important;
    transform: none !important;
    box-shadow: none !important;
    opacity: 1 !important;
    display: inline-flex !important;
    visibility: visible !important;
}
</style>
<div id="appLayout" class="flex h-screen overflow-auto">
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

                    <a href="<?php echo BASE_URL; ?>/admin/requests" class="sidebar-link flex items-center py-4">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v4a1 1 0 001 1h6a1 1 0 001-1V7m0 0a2 2 0 00-2-2H9a2 2 0 00-2 2m0 0v11a2 2 0 002 2h4a2 2 0 002-2V7m-4-2H8"></path>
                        </svg>
                        <span>Export Data</span>
                    </a>
                </div>

                <div class="px-6">
                    <p class="sidebar-section-title uppercase tracking-wider mb-3">Surat & Dokumen</p>

                    <a href="<?php echo BASE_URL; ?>/admin/letter-requests" class="sidebar-link active flex items-center py-4">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2m-6 0h6"></path>
                        </svg>
                        <span>Pengajuan Surat</span>
                    </a>

                    <a href="<?php echo BASE_URL; ?>/admin/telegram-settings" class="sidebar-link flex items-center py-4">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
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
    <div class="flex-1 flex flex-col overflow-auto main-panel" style="min-height: 100vh;">

        <!-- HEADER JUDUL YANG LEBIH MENARIK -->
        <div class="sticky-header-container">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex items-center gap-5 mb-2">
                    <!-- Icon dekoratif dengan gradient -->
                    <div class="p-3 rounded-2xl bg-linear-to-br from-primary/20 to-secondary/20 border-2 border-white/50 shadow-lg backdrop-blur-sm">
                        <div class="p-3 rounded-xl bg-linear-to-br from-primary to-secondary shadow-inner">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2m-6 0h6"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Judul dengan efek menarik -->
                    <div class="flex-1">
                        <div class="flex items-center gap-4">
                            <div>
                                <h1 class="text-3xl font-bold bg-linear-to-r from-primary via-primary-light to-secondary bg-clip-text text-transparent tracking-tight">
                                    Pengajuan Surat
                                </h1>
                                <!-- Garis dekoratif bawah judul -->
                                <div class="h-1.5 w-40 mt-2 bg-linear-to-r from-primary/60 to-secondary/40 rounded-full"></div>
                            </div>

                            <!-- Badge jumlah requests -->
                            <div class="px-4 py-1.5 bg-linear-to-r from-accent/20 to-highlight/20 rounded-full border border-accent/30">
                                <span class="text-sm font-semibold text-dark flex items-center gap-1.5">
                                    <span class="w-2 h-2 bg-accent rounded-full animate-pulse"></span>
                                    <span class="bg-linear-to-r from-dark to-primary bg-clip-text text-transparent"><?php echo $requests_data['total'] ?? 0; ?> Pengajuan</span>
                                </span>
                            </div>
                        </div>

                        <p class="text-gray-600 mt-3 pl-1 text-lg font-medium">
                            Kelola pengajuan surat dari warga dengan mudah dan efisien
                        </p>
                    </div>
                </div>

                <!-- Dekorasi tambahan -->
                <div class="flex items-center gap-6 mt-4">
                    <div class="flex items-center gap-2 text-sm">
                        <div class="w-3 h-3 rounded-full bg-yellow-500 animate-pulse"></div>
                        <span class="text-gray-600 font-medium">Menunggu: <?php echo $this->letterRequestModel->getCountByStatus('pending'); ?></span>
                    </div>
                    <div class="flex items-center gap-2 text-sm">
                        <div class="w-3 h-3 rounded-full bg-green-500 animate-pulse"></div>
                        <span class="text-gray-600 font-medium">Disetujui: <?php echo $this->letterRequestModel->getCountByStatus('approved'); ?></span>
                    </div>
                    <div class="flex items-center gap-2 text-sm">
                        <div class="w-3 h-3 rounded-full bg-red-500 animate-pulse"></div>
                        <span class="text-gray-600 font-medium">Ditolak: <?php echo $this->letterRequestModel->getCountByStatus('rejected'); ?></span>
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

                <!-- Requests Management Section -->
                <div class="cream-card p-8 mb-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-gray-800">Daftar Pengajuan Surat</h2>
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Kelola pengajuan surat dari warga</span>
                        </div>
                    </div>

                    <!-- FILTER STATUS -->
                    <div class="flex justify-end items-center mb-6">
                        <div class="flex items-center gap-4">
                            <select id="statusFilter" onchange="filterByStatus()" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none">
                                <option value="">Semua Status</option>
                                <option value="pending" <?php echo ($status ?? '') === 'pending' ? 'selected' : ''; ?>>Menunggu</option>
                                <option value="approved" <?php echo ($status ?? '') === 'approved' ? 'selected' : ''; ?>>Disetujui</option>
                                <option value="rejected" <?php echo ($status ?? '') === 'rejected' ? 'selected' : ''; ?>>Ditolak</option>
                            </select>
                        </div>
                    </div>

                    <!-- Requests Table -->
                    <div class="overflow-x-auto mb-6 max-h-[70vh] overflow-y-auto border border-gray-200 rounded-lg shadow-sm">
                        <table class="min-w-full table-fixed">
                            <thead>
                                <tr class="bg-linear-to-r from-gray-50 to-gray-100">
                                    <th class="px-6 py-4 w-16 text-sm font-bold text-gray-700 text-center border-r border-gray-200">No</th>
                                    <th class="px-6 py-4 text-sm font-bold text-gray-700 border-r border-gray-200 min-w-[150px]">Tanggal</th>
                                    <th class="px-6 py-4 text-sm font-bold text-gray-700 border-r border-gray-200 min-w-[200px]">Pemohon</th>
                                    <th class="px-6 py-4 text-sm font-bold text-gray-700 border-r border-gray-200 min-w-[200px]">Jenis Surat</th>
                                    <th class="px-6 py-4 text-sm font-bold text-gray-700 border-r border-gray-200 w-32">Status</th>
                                    <th class="px-6 py-4 w-40 text-sm font-bold text-gray-700 text-center border-r border-gray-200">Setujui/Tolak</th>
                                    <th class="px-6 py-4 w-48 text-sm font-bold text-gray-700 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php $no = (($requests_data['current_page'] ?? 1) - 1) * ITEMS_PER_PAGE + 1; ?>
                                <?php foreach (($requests_data['requests'] ?? []) as $request): ?>
                                <tr class="hover:bg-gray-50 transition-colors h-16">
                                    <td class="px-6 py-4 text-center font-medium text-gray-800"><?php echo $no++; ?></td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-800"><?php echo date('d/m/Y', strtotime($request['created_at'])); ?></div>
                                        <div class="text-xs text-gray-500"><?php echo date('H:i', strtotime($request['created_at'])); ?></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-linear-to-br from-primary to-primary-light flex items-center justify-center text-white font-semibold text-xs">
                                                <?php echo strtoupper(substr($request['user_full_name'], 0, 1)); ?>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-800"><?php echo htmlspecialchars($request['user_full_name']); ?></div>
                                                <div class="text-xs text-gray-500">NIK: <?php echo htmlspecialchars($request['nik'] ?? $request['user_nik'] ?? '-'); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-800"><?php echo htmlspecialchars($request['letter_type_name']); ?></div>
                                        <div class="text-xs text-gray-500"><?php echo htmlspecialchars($request['letter_type_code'] ?? ''); ?></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php
                                        $statusClass = '';
                                        $statusText = '';
                                        switch($request['status']) {
                                            case 'pending':
                                                $statusClass = 'bg-yellow-100 text-yellow-700 border-yellow-200';
                                                $statusText = 'Menunggu';
                                                break;
                                            case 'approved':
                                                $statusClass = 'bg-green-100 text-green-700 border-green-200';
                                                $statusText = 'Disetujui';
                                                break;
                                            case 'rejected':
                                                $statusClass = 'bg-red-100 text-red-700 border-red-200';
                                                $statusText = 'Ditolak';
                                                break;
                                        }
                                        ?>
                                        <div class="text-center">
                                            <span class="px-3 py-1.5 rounded-full text-xs font-semibold border <?php echo $statusClass; ?>">
                                                <?php echo $statusText; ?>
                                            </span>
                                        </div>
                                    </td>
                                    <!-- Kolom Setujui/Tolak: Tombol PERMANEN untuk status PENDING -->
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <?php if ($request['status'] === 'pending'): ?>
                                            <!-- Tombol Setujui: Selalu terlihat untuk pending TANPA HOVER EFFECT -->
                                            <button onclick="approveRequest(<?php echo $request['id']; ?>, '<?php echo htmlspecialchars($request['user_full_name']); ?>')"
                                                    class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg text-xs font-medium flex items-center gap-1 force-visible"
                                                    style="opacity: 1 !important; display: inline-flex !important; visibility: visible !important; transition: none !important;"
                                                    onmouseover="this.style.backgroundColor='rgb(220, 252, 231)'"
                                                    onmouseout="this.style.backgroundColor='rgb(220, 252, 231)'"
                                                    id="approve-btn-<?php echo $request['id']; ?>">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Setujui
                                            </button>
                                            <!-- Tombol Tolak: Selalu terlihat untuk pending TANPA HOVER EFFECT -->
                                            <button onclick="rejectRequest(<?php echo $request['id']; ?>, '<?php echo htmlspecialchars($request['user_full_name']); ?>')"
                                                    class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-xs font-medium flex items-center gap-1 force-visible"
                                                    style="opacity: 1 !important; display: inline-flex !important; visibility: visible !important; transition: none !important;"
                                                    onmouseover="this.style.backgroundColor='rgb(254, 226, 226)'"
                                                    onmouseout="this.style.backgroundColor='rgb(254, 226, 226)'"
                                                    id="reject-btn-<?php echo $request['id']; ?>">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                Tolak
                                            </button>
                                            <?php elseif ($request['status'] === 'approved'): ?>
                                            <!-- Status sudah disetujui - tampilkan status final -->
                                            <span class="px-3 py-1.5 bg-green-600 text-white rounded-lg text-xs font-medium flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Disetujui
                                            </span>
                                            <?php elseif ($request['status'] === 'rejected'): ?>
                                            <!-- Status sudah ditolak - tampilkan status final -->
                                            <span class="px-3 py-1.5 bg-red-600 text-white rounded-lg text-xs font-medium flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                Ditolak
                                            </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <!-- Kolom Aksi: Detail dan Download PDF -->
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <!-- Tombol View Details: SELALU MUNcul untuk semua status -->
                                            <button onclick="viewDetails(<?php echo $request['id']; ?>)"
                                                    class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg text-xs font-medium hover:bg-gray-200 flex items-center gap-1 force-visible" style="opacity: 1 !important;">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Detail
                                            </button>

                                            <!-- Tombol Download PDF: Hanya muncul untuk status APPROVED -->
                                            <?php if ($request['status'] === 'approved'): ?>
                                            <button onclick="downloadPDF(<?php echo $request['id']; ?>)"
                                                    class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg text-xs font-medium hover:bg-blue-200 flex items-center gap-1 force-visible" style="opacity: 1 !important;">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                Download PDF
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Info and Pagination -->
                    <div class="mt-6 pt-4 border-t flex justify-between items-center text-sm text-gray-600">
                        <div class="flex items-center gap-4">
                            <?php if (($requests_data['total'] ?? 0) <= 1000): ?>
                                <span class="font-medium text-green-600">📋 Menampilkan semua pengajuan surat</span>
                            <?php else: ?>
                                <span class="font-medium">Halaman <?php echo $requests_data['current_page'] ?? 1; ?> dari <?php echo $requests_data['pages'] ?? 1; ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="text-xs text-gray-500">
                            Total: <?php echo $requests_data['total'] ?? 0; ?> pengajuan
                        </div>
                    </div>

                    <?php if (($requests_data['pages'] ?? 0) > 1 && ($requests_data['total'] ?? 0) > 1000): ?>
                    <div class="mt-4 flex justify-center">
                        <div class="flex space-x-2">
                            <?php if (($requests_data['current_page'] ?? 1) > 1): ?>
                            <a href="?page=<?php echo ($requests_data['current_page'] - 1); ?>&status=<?php echo urlencode($status ?? ''); ?>"
                               class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm hover:bg-gray-50 transition-colors">Previous</a>
                            <?php endif; ?>

                            <?php
                            $current_page = $requests_data['current_page'] ?? 1;
                            $total_pages = $requests_data['pages'] ?? 1;
                            $start_page = max(1, $current_page - 2);
                            $end_page = min($total_pages, $current_page + 2);

                            for ($i = $start_page; $i <= $end_page; $i++):
                            ?>
                            <a href="?page=<?php echo $i; ?>&status=<?php echo urlencode($status ?? ''); ?>"
                               class="px-4 py-2 <?php echo $i === $current_page ? 'bg-primary text-white' : 'bg-white border border-gray-300 hover:bg-gray-50'; ?> rounded-lg text-sm transition-colors">
                                <?php echo $i; ?>
                            </a>
                            <?php endfor; ?>

                            <?php if (($requests_data['current_page'] ?? 1) < ($requests_data['pages'] ?? 1)): ?>
                            <a href="?page=<?php echo ($requests_data['current_page'] + 1); ?>&status=<?php echo urlencode($status ?? ''); ?>"
                               class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm hover:bg-gray-50 transition-colors">Next</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
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

    // Auto-hide flash messages after 5 seconds
    const flashMessages = document.querySelectorAll('.bg-green-100, .bg-red-100');
    flashMessages.forEach(message => {
        setTimeout(() => {
            message.style.opacity = '0';
            message.style.transform = 'translateY(-10px)';
            message.style.transition = 'all 0.3s ease';
            setTimeout(() => message.remove(), 5000);
        }, 5000);
    });
});

// Approve request
function approveRequest(requestId, userName) {
    Swal.fire({
        title: 'Setujui Pengajuan',
        text: `Apakah Anda yakin ingin menyetujui pengajuan surat dari ${userName}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#4C7A5B',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Setujui',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit approval form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `${BASE_URL}/admin/requests/${requestId}/approve`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = 'csrf_token';
            csrfToken.value = '<?php echo generateCSRFToken(); ?>';

            const notesInput = document.createElement('input');
            notesInput.type = 'hidden';
            notesInput.name = 'notes';
            notesInput.value = 'Disetujui oleh admin';

            form.appendChild(csrfToken);
            form.appendChild(notesInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Reject request
function rejectRequest(requestId, userName) {
    Swal.fire({
        title: 'Tolak Pengajuan',
        text: `Apakah Anda yakin ingin menolak pengajuan surat dari ${userName}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Tolak',
        cancelButtonText: 'Batal',
        input: 'textarea',
        inputPlaceholder: 'Masukkan alasan penolakan...',
        inputValidator: (value) => {
            if (!value) {
                return 'Alasan penolakan harus diisi!';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit rejection form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `${BASE_URL}/admin/requests/${requestId}/reject`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = 'csrf_token';
            csrfToken.value = '<?php echo generateCSRFToken(); ?>';

            const notesInput = document.createElement('input');
            notesInput.type = 'hidden';
            notesInput.name = 'notes';
            notesInput.value = result.value;

            form.appendChild(csrfToken);
            form.appendChild(notesInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Download PDF
function downloadPDF(requestId) {
    window.open(`${BASE_URL}/admin/requests/${requestId}/download`, '_blank');
}

// View details
function viewDetails(requestId) {
    // Redirect to detail page, not download
    window.location.href = `${BASE_URL}/admin/letter-requests/${requestId}`;
}

// Filter by status
function filterByStatus() {
    const statusFilter = document.getElementById('statusFilter').value;

    // Build URL with parameters
    let url = `${BASE_URL}/admin/letter-requests`;
    const params = [];

    if (statusFilter) {
        params.push(`status=${encodeURIComponent(statusFilter)}`);
    }

    if (params.length > 0) {
        url += '?' + params.join('&');
    }

    window.location.href = url;
}
</script>
