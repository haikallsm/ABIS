<?php
// Set page metadata
$title = 'Dashboard Admin';
$extra_css = [];
$extra_js = [];
?>

<div id="appLayout" class="flex min-h-screen">
    <aside class="w-72 sidebar flex flex-col">

        <!-- sidebar header removed to show menu immediately -->
        <div class="flex-1 sidebar-nav py-2">
            <nav class="space-y-1">
                <div class="px-6">
                    <button id="sidebarToggle" onclick="toggleSidebar()" class="p-2 rounded-md bg-white/10 hover:bg-white/20 text-white focus:outline-none mr-3 inline-flex items-center" aria-label="Toggle sidebar">
                        <img src="/public/assets/icons/hamburger.svg" alt="Menu" class="w-5 h-5" />
                    </button>
                    <h2 class="text-2xl font-extrabold mb-2 text-white inline-block align-middle">Surat - In</h2>
                    <p class="sidebar-section-title uppercase tracking-wider mb-3">Menu Utama</p>
                    
                    <a href="<?php echo BASE_URL; ?>/admin/dashboard" class="sidebar-link active flex items-center py-4">
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
                    
                    <a href="<?php echo BASE_URL; ?>/admin/requests" class="sidebar-link flex items-center py-4">
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
                            <img src="/public/assets/icons/logout.svg" alt="Menu" class="w-5 h-5" />
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
                                    <h1 class="text-2xl font-bold mb-2">Selamat Datang, Admin</h1>
                                    <p id="live-clock" class="text-lg opacity-95 mb-1"><?php echo date('l, d F Y H:i:s', strtotime('now')); ?></p>
                                </div>

                        <div class="text-right">
                            <div class="profile-box inline-flex items-center space-x-4">
                                <div class="w-12 h-12 profile-avatar rounded-full flex items-center justify-center text-lg">
                                    <span class="font-bold text-white"><?php echo strtoupper(substr(getCurrentUser()['full_name'], 0, 1)); ?></span>
                                </div>
                                <div class="text-white">
                                    <p class="font-bold text-lg"><?php echo htmlspecialchars(getCurrentUser()['full_name']); ?></p>
                                    <p class="text-xs opacity-90 mt-1">Administrator Sistem</p>
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

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">

                    <!-- JUMLAH PENGGUNA -->
                    <div class="p-6 card-bg-green rounded-xl flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-600 mb-2 font-medium">Jumlah Pengguna</p>
                            <p class="text-4xl font-bold mt-1 text-primary"><?php echo $stats['users']['total'] ?? 0; ?></p>
                            <div class="flex items-center mt-3">
                                <svg class="w-4 h-4 text-primary mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                                <p class="text-xs text-gray-500">+12 dari bulan lalu</p>
                            </div>
                        </div>
                        <div class="relative">
                            <img src="/public/assets/icons/user2.svg" alt="Menu" class="w-12 h-12" />
                        </div>
                    </div>

                    <!-- JUMLAH DOKUMEN -->
                    <div class="p-6 card-bg-teal rounded-xl flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-600 mb-2 font-medium">Jumlah Dokumen</p>
                            <p class="text-4xl font-bold mt-1 text-secondary"><?php echo $stats['requests']['total'] ?? 0; ?></p>
                            <div class="flex items-center mt-3">
                                <svg class="w-4 h-4 text-secondary mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                                <p class="text-xs text-gray-500">+45 dari bulan lalu</p>
                            </div>
                        </div>
                        <img src="/public/assets/icons/file.svg" alt="Menu" class="w-12 h-12" />
                    </div>

                    <!-- DOKUMEN DISETUJUI -->
                    <div class="p-6 card-bg-peach rounded-xl flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-600 mb-2 font-medium">Dokumen Disetujui</p>
                            <p class="text-4xl font-bold mt-1 text-accent"><?php echo $stats['requests']['approved'] ?? 0; ?></p>
                            <div class="flex items-center mt-3">
                                <div class="w-2 h-2 bg-accent rounded-full mr-2"></div>
                                <p class="text-xs text-gray-500">65.6% dari total</p>
                            </div>
                        </div>
                        <svg class="w-12 h-12 text-accent/70 stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>

                    <!-- PERMINTAAN HARI INI -->
                    <div class="p-6 card-bg-coral rounded-xl flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-600 mb-2 font-medium">Permintaan Hari Ini</p>
                            <p class="text-4xl font-bold mt-1 text-highlight"><?php echo $stats['requests']['pending_today'] ?? 0; ?></p>
                            <div class="flex items-center mt-3">
                                <div class="w-2 h-2 bg-highlight rounded-full mr-2 animate-pulse"></div>
                                <p class="text-xs text-gray-500">2 menunggu proses</p>
                            </div>
                        </div>
                        <svg class="w-12 h-12 text-highlight/70 stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>

                    <!-- DISETUJUI HARI INI -->
                    <div class="p-6 card-bg-mint rounded-xl flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-600 mb-2 font-medium">Disetujui Hari Ini</p>
                            <p class="text-4xl font-bold mt-1 text-primary"><?php echo $stats['requests']['approved_today'] ?? 0; ?></p>
                            <div class="flex items-center mt-3">
                                <div class="w-2 h-2 bg-primary rounded-full mr-2"></div>
                                <p class="text-xs text-gray-500">50% dari permintaan</p>
                            </div>
                        </div>
                        <img src="/public/assets/icons/approval.svg" alt="Menu" class="w-12 h-12" />
                    </div>

                    <!-- TOTAL SURAT BULAN INI -->
                    <div class="p-6 card-bg-sage rounded-xl flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-600 mb-2 font-medium">Total Surat Bulan Ini</p>
                            <p class="text-4xl font-bold mt-1 text-secondary"><?php echo $stats['requests']['this_month'] ?? 52; ?></p>
                            <div class="flex items-center mt-3">
                                <svg class="w-4 h-4 text-secondary mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                <p class="text-xs text-gray-500">Rata-rata 2.3/hari</p>
                            </div>
                        </div>
                        <svg class="w-12 h-12 text-secondary/70 stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>

                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 cream-card p-6">
                        <div class="flex justify-between items-center mb-6 border-b pb-4 cream-border">
                            <div>
                                <h2 class="text-2xl font-bold text-dark mb-2">Permintaan Surat Terbaru</h2>
                                <p class="text-sm text-gray-500 mt-1 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    3 permintaan dalam 3 hari terakhir
                                </p>
                            </div>
                            <a href="<?php echo BASE_URL; ?>/admin/requests" class="text-sm font-semibold text-primary hover:text-dark transition duration-150 flex items-center bg-primary/10 px-4 py-2 rounded-lg hover:bg-primary/20">
                                <span>Lihat Semua</span>
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full leading-normal">
                                <thead>
                                    <tr class="text-left text-xs font-semibold uppercase tracking-wider text-dark cream-table-header">
                                        <th class="px-5 py-4 border-b-2 cream-border">PEMOHON</th>
                                        <th class="px-5 py-4 border-b-2 cream-border">JENIS SURAT</th>
                                        <th class="px-5 py-4 border-b-2 cream-border">STATUS</th>
                                        <th class="px-5 py-4 border-b-2 cream-border">TANGGAL</th>
                                        <th class="px-5 py-4 border-b-2 cream-border">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (($recent_requests ?? []) as $request): ?>
                                    <tr class="table-row-hover">
                                        <td class="px-5 py-4 border-b cream-border text-sm font-medium">
                                            <div class="flex items-center">
                                                <span class="w-9 h-9 user-avatar rounded-full font-bold flex items-center justify-center mr-3"><?php echo strtoupper(substr($request['user_full_name'], 0, 1)); ?></span>
                                                <div>
                                                    <p class="font-medium"><?php echo htmlspecialchars($request['user_full_name']); ?></p>
                                                    <p class="text-xs text-gray-500"><?php echo htmlspecialchars($request['user_email']); ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 border-b cream-border text-sm"><?php echo htmlspecialchars($request['letter_type_name']); ?></td>
                                        <td class="px-5 py-4 border-b cream-border text-sm">
                                            <span class="status-<?php
                                                echo $request['status'] === 'approved' ? 'approved' :
                                                     ($request['status'] === 'pending' ? 'waiting' : 'pending'); ?>">
                                                <?php
                                                echo $request['status'] === 'approved' ? 'Disetujui' :
                                                     ($request['status'] === 'pending' ? 'Menunggu' :
                                                      ($request['status'] === 'rejected' ? 'Ditolak' : 'Selesai')); ?>
                                            </span>
                                        </td>
                                        <td class="px-5 py-4 border-b cream-border text-sm text-gray-500"><?php echo date('d M Y', strtotime($request['created_at'])); ?></td>
                                        <td class="px-5 py-4 border-b cream-border text-sm">
                                            <div class="flex items-center space-x-2">
                                                <button title="Lihat" class="action-button text-primary hover:text-dark transition p-2 rounded-lg hover:bg-primary/10">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </button>
                                                <button title="Download" class="action-button text-secondary hover:text-dark transition p-2 rounded-lg hover:bg-secondary/10">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="space-y-6">

                        <div class="cream-card p-6">
                            <div class="flex justify-between items-center mb-6 border-b pb-4 cream-border">
                                <h3 class="text-xl font-bold text-dark">Aktivitas Terbaru</h3>
                                <span class="text-xs text-primary font-semibold bg-primary/10 px-3 py-1 rounded-full">3 aktivitas</span>
                            </div>
                            <ul class="space-y-5">
                                <?php foreach ($recent_activities as $activity): ?>
                                <li class="flex items-start group">
                                    <div class="w-9 h-9 rounded-full bg-<?php echo $activity['color']; ?>/10 text-<?php echo $activity['color']; ?> flex items-center justify-center shrink-0 mr-3 mt-1">
                                        <?php if ($activity['icon'] === 'check'): ?>
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        <?php elseif ($activity['icon'] === 'clock'): ?>
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        <?php elseif ($activity['icon'] === 'x'): ?>
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        <?php elseif ($activity['icon'] === 'check-circle'): ?>
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        <?php else: ?>
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-dark"><?php echo htmlspecialchars($activity['title']); ?></p>
                                        <p class="text-xs text-gray-500 mt-1"><?php echo htmlspecialchars($activity['time']); ?> WIB</p>
                                    </div>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <div class="cream-card p-6">
                            <div class="flex justify-between items-center mb-6 border-b pb-4 cream-border">
                                <h3 class="text-xl font-bold text-dark">Statistik Bulan Ini</h3>
                                <span class="text-xs text-primary font-semibold bg-primary/10 px-3 py-1 rounded-full"><?php echo date('F Y'); ?></span>
                            </div>
                            <div class="space-y-5 text-sm">
                                <div class="flex justify-between items-center p-3 bg-primary/5 rounded-lg">
                                    <span class="text-gray-600">Surat Diterbitkan:</span>
                                    <span class="font-bold text-xl text-primary"><?php echo $stats['requests']['this_month'] ?? 8; ?></span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-accent/5 rounded-lg">
                                    <span class="text-gray-600">Surat Menunggu:</span>
                                    <span class="font-bold text-xl text-accent"><?php echo $stats['requests']['pending'] ?? 3; ?></span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-secondary/5 rounded-lg">
                                    <span class="text-gray-600">Pengguna Aktif:</span>
                                    <span class="font-bold text-xl text-secondary"><?php echo $stats['users']['active'] ?? 12; ?></span>
                                </div>
                                <div class="border-t pt-4 mt-3 cream-border">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-dark font-bold">Rata-rata Waktu Proses:</span>
                                        <span class="font-bold text-2xl text-primary"><?php echo $stats['avg_process_time'] ?? '2.3'; ?> hari</span>
                                    </div>
                                    <div class="flex items-center text-xs text-gray-500">
                                        <svg class="w-4 h-4 text-primary mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                        Lebih cepat 0.4 hari dari bulan lalu
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>
