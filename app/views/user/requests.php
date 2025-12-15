<?php
// Set page metadata
$title = 'Riwayat Permohonan';
$extra_css = [];
$extra_js = ['requests'];
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

                    <a href="<?php echo BASE_URL; ?>/dashboard" class="sidebar-link flex items-center py-4">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <a href="<?php echo BASE_URL; ?>/profile" class="sidebar-link flex items-center py-4">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>Profile Saya</span>
                    </a>
                </div>

                <div class="px-6">
                    <p class="sidebar-section-title uppercase tracking-wider mb-3">Layanan Surat</p>

                    <a href="<?php echo BASE_URL; ?>/requests/create" class="sidebar-link flex items-center py-4">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span>Buat Surat</span>
                    </a>

                    <a href="<?php echo BASE_URL; ?>/requests" class="sidebar-link active flex items-center py-4">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Riwayat Surat</span>
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
    <div class="flex-1 flex flex-col overflow-hidden main-panel">
    <!-- STICKY HEADER SECTION -->
    <div class="sticky-header-container">
        <div class="px-6">
            <div class="welcome-box p-6 text-white">
                <div class="flex justify-between items-start relative z-10">
                    <div>
                        <h1 class="text-2xl font-bold mb-2">Riwayat Permohonan</h1>
                        <p class="text-lg opacity-95 mb-1">Pantau status permohonan surat Anda</p>
                    </div>
                    <div class="text-right">
                        <div class="profile-box inline-flex items-center space-x-4">
                            <div class="w-12 h-12 profile-avatar rounded-full flex items-center justify-center text-lg">
                                <span class="font-bold text-white"><?php echo strtoupper(substr(getCurrentUser()['full_name'], 0, 1)); ?></span>
                            </div>
                            <div class="text-white">
                                <p class="font-bold text-lg"><?php echo htmlspecialchars(getCurrentUser()['full_name']); ?></p>
                                <p class="text-xs opacity-90 mt-1">Pengguna Sistem</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SCROLLABLE CONTENT SECTION -->
    <div class="scroll-container">
        <main class="px-6 pb-6">
            <div class="max-w-7xl mx-auto">
                <div class="cream-card p-8">
                    <div class="flex justify-between items-center mb-6 border-b pb-4 cream-border">
                        <div>
                            <h2 class="text-2xl font-bold text-dark mb-2">Riwayat Permohonan Surat</h2>
                            <p class="text-sm text-gray-500">Kelola dan pantau semua permohonan surat yang telah Anda ajukan</p>
                        </div>
                        <a href="<?php echo BASE_URL; ?>/requests/create"
                           class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary-light transition-colors flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Buat Permohonan Baru
                        </a>
                    </div>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                            <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                            <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Filter and Search -->
                    <div class="flex flex-col sm:flex-row gap-4 mb-6">
                        <div class="flex-1">
                            <input type="text" id="searchInput" placeholder="Cari permohonan..."
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>
                        <div>
                            <select id="statusFilter" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Semua Status</option>
                                <option value="pending">Menunggu</option>
                                <option value="approved">Disetujui</option>
                                <option value="rejected">Ditolak</option>
                                <option value="completed">Selesai</option>
                            </select>
                        </div>
                    </div>

                    <!-- Requests Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full leading-normal">
                            <thead>
                                <tr class="text-left text-xs font-semibold uppercase tracking-wider text-dark cream-table-header">
                                    <th class="px-5 py-4 border-b-2 cream-border">No</th>
                                    <th class="px-5 py-4 border-b-2 cream-border">Jenis Surat</th>
                                    <th class="px-5 py-4 border-b-2 cream-border">Status</th>
                                    <th class="px-5 py-4 border-b-2 cream-border">Tanggal Pengajuan</th>
                                    <th class="px-5 py-4 border-b-2 cream-border">Tanggal Selesai</th>
                                    <th class="px-5 py-4 border-b-2 cream-border">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="requestsTable">
                                <?php if (!empty($requests)): ?>
                                    <?php $no = 1; foreach ($requests as $request): ?>
                                    <tr class="table-row-hover">
                                        <td class="px-5 py-4 border-b cream-border text-sm font-medium">
                                            <?php echo $no++; ?>
                                        </td>
                                        <td class="px-5 py-4 border-b cream-border text-sm font-medium">
                                            <?php echo htmlspecialchars($request['letter_type_name']); ?>
                                        </td>
                                        <td class="px-5 py-4 border-b cream-border text-sm">
                                            <span class="status-<?php
                                                echo $request['status'] === 'approved' ? 'approved' :
                                                     ($request['status'] === 'pending' ? 'waiting' :
                                                      ($request['status'] === 'rejected' ? 'pending' : 'approved')); ?>">
                                                <?php
                                                echo $request['status'] === 'approved' ? 'Disetujui' :
                                                     ($request['status'] === 'pending' ? 'Menunggu' :
                                                      ($request['status'] === 'rejected' ? 'Ditolak' : 'Selesai')); ?>
                                            </span>
                                        </td>
                                        <td class="px-5 py-4 border-b cream-border text-sm text-gray-500">
                                            <?php echo date('d M Y, H:i', strtotime($request['created_at'])); ?>
                                        </td>
                                        <td class="px-5 py-4 border-b cream-border text-sm text-gray-500">
                                            <?php echo $request['completed_at'] ? date('d M Y, H:i', strtotime($request['completed_at'])) : '-'; ?>
                                        </td>
                                        <td class="px-5 py-4 border-b cream-border text-sm">
                                            <div class="flex items-center space-x-2">
                                                <button onclick="viewRequest(<?php echo $request['id']; ?>)"
                                                        title="Lihat Detail"
                                                        class="action-button text-primary hover:text-dark transition p-2 rounded-lg hover:bg-primary/10">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </button>
                                                <?php if ($request['status'] === 'approved' || $request['status'] === 'completed'): ?>
                                                <button onclick="downloadRequest(<?php echo $request['id']; ?>)"
                                                        title="Download"
                                                        class="action-button text-secondary hover:text-dark transition p-2 rounded-lg hover:bg-secondary/10">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                    </svg>
                                                </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="px-5 py-8 border-b cream-border text-center text-gray-500">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                <p>Belum ada permohonan surat</p>
                                                <a href="<?php echo BASE_URL; ?>/requests/create"
                                                   class="text-primary hover:text-primary-light mt-2">Buat permohonan pertama Anda</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if (!empty($requests)): ?>
                    <div class="flex justify-between items-center mt-6 pt-4 border-t cream-border">
                        <div class="text-sm text-gray-500">
                            Menampilkan <?php echo count($requests); ?> permohonan
                        </div>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50 disabled:opacity-50" disabled>
                                Previous
                            </button>
                            <button class="px-3 py-1 bg-primary text-white rounded">1</button>
                            <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50" disabled>
                                Next
                            </button>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Request Detail Modal -->
<div id="requestModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-2xl w-full max-h-screen overflow-y-auto">
            <div class="p-6 border-b">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-dark">Detail Permohonan</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div id="modalContent" class="p-6">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
// Make BASE_URL available to JavaScript
const BASE_URL = '<?php echo BASE_URL; ?>';
</script>

            </main>
        </div>
    </div>
</div>
