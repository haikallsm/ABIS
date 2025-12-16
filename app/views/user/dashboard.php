<?php
// Set page metadata
$title = 'Dashboard User';
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
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <h2 class="text-2xl font-extrabold mb-2 text-white inline-block align-middle">Surat - In</h2>
                    <p class="sidebar-section-title uppercase tracking-wider mb-3">Menu Utama</p>

                    <a href="<?php echo BASE_URL; ?>/dashboard" class="sidebar-link active flex items-center py-4">
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

                    <a href="<?php echo BASE_URL; ?>/requests" class="sidebar-link flex items-center py-4">
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
                                    <h1 class="text-2xl font-bold mb-2">Selamat Datang, <?php echo htmlspecialchars($current_user['full_name']); ?>!</h1>
                                    <p class="text-lg opacity-95 mb-1"><?php echo date('l, d F Y', strtotime('today')); ?></p>
                                </div>

                        <div class="text-right">
                            <div class="profile-box inline-flex items-center space-x-4">
                                <div class="w-12 h-12 profile-avatar rounded-full flex items-center justify-center text-lg">
                                    <span class="font-bold text-white"><?php echo strtoupper(substr($current_user['full_name'], 0, 1)); ?></span>
                                </div>
                                <div class="text-white">
                                    <p class="font-bold text-lg"><?php echo htmlspecialchars($current_user['full_name']); ?></p>
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
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                    <!-- TOTAL PERMOHONAN -->
                    <div class="p-6 card-bg-green rounded-xl card-hover-effect no-move
                                transition duration-300 flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-600 mb-2 font-medium">Total Permohonan</p>
                            <p class="text-4xl font-bold mt-1 text-primary"><?php echo $stats['total_requests'] ?? 0; ?></p>
                            <div class="flex items-center mt-3">
                                <svg class="w-4 h-4 text-primary mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-xs text-gray-500">Total semua permohonan</p>
                            </div>
                        </div>
                        <div class="relative">
                            <svg class="w-12 h-12 text-primary/70 stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- MENUNGGU PROSES -->
                    <div class="p-6 card-bg-coral rounded-xl card-hover-effect no-move
                                transition duration-300 flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-600 mb-2 font-medium">Menunggu Proses</p>
                            <p class="text-4xl font-bold mt-1 text-highlight"><?php echo $stats['pending_requests'] ?? 0; ?></p>
                            <div class="flex items-center mt-3">
                                <div class="w-2 h-2 bg-highlight rounded-full mr-2 animate-pulse"></div>
                                <p class="text-xs text-gray-500">Dalam proses verifikasi</p>
                            </div>
                        </div>
                        <svg class="w-12 h-12 text-highlight/70 stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>

                    <!-- SELESAI -->
                    <div class="p-6 card-bg-mint rounded-xl card-hover-effect no-move
                                transition duration-300 flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-600 mb-2 font-medium">Selesai</p>
                            <p class="text-4xl font-bold mt-1 text-primary"><?php echo $stats['completed_requests'] ?? 0; ?></p>
                            <div class="flex items-center mt-3">
                                <div class="w-2 h-2 bg-primary rounded-full mr-2"></div>
                                <p class="text-xs text-gray-500">Sudah selesai diproses</p>
                            </div>
                        </div>
                        <svg class="w-12 h-12 text-primary/70 stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.765a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.265 21H11a2 2 0 01-2-2v-6a2 2 0 00-2-2 2 2 0 01-2-2V7a2 2 0 012-2h6.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2h-4.586a1 1 0 01-.707-.293l-5.414-5.414a1 1 0 01-.293-.707V19a2 2 0 012-2h4.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2h-4.586a1 1 0 01-.707-.293zM7 7h.01"></path>
                        </svg>
                    </div>

                </div>

                <!-- TELEGRAM CHAT ID SETUP -->
                <div class="cream-card p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8-9 8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-dark">Notifikasi Telegram</h3>
                                <p class="text-sm text-gray-600">Dapatkan notifikasi status permohonan via Telegram</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <?php if (!empty($current_user['telegram_chat_id'])): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3"/>
                                    </svg>
                                    Terhubung
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3"/>
                                    </svg>
                                    Belum Terhubung
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if (empty($current_user['telegram_chat_id'])): ?>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                            <div class="flex items-start">
                                <div class="shrink-0">
                                    <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-blue-800">Hubungkan Akun Telegram</h4>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <p>Dapatkan notifikasi real-time ketika status permohonan surat Anda berubah. Ikuti langkah berikut:</p>
                                        <ol class="mt-2 list-decimal list-inside space-y-1">
                                            <li>Buka Telegram dan cari bot <code class="bg-blue-100 px-1 rounded">@SuratIn_Bot</code></li>
                                            <li>Kirim pesan <code class="bg-blue-100 px-1 rounded">/start</code> ke bot</li>
                                            <li>Bot akan memberikan Chat ID Anda</li>
                                            <li>Masukkan Chat ID di bawah ini</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form id="telegramForm" method="POST" action="<?php echo BASE_URL; ?>/telegram/update-chat-id" class="space-y-4">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

                        <div>
                            <label for="telegram_chat_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Chat ID Telegram <?php if (empty($current_user['telegram_chat_id'])): ?><span class="text-red-500">*</span><?php endif; ?>
                            </label>
                            <div class="flex gap-2">
                                <input type="text"
                                       id="telegram_chat_id"
                                       name="telegram_chat_id"
                                       value="<?php echo htmlspecialchars($current_user['telegram_chat_id'] ?? ''); ?>"
                                       placeholder="Contoh: 123456789"
                                       class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
                                       <?php if (!empty($current_user['telegram_chat_id'])): ?>readonly<?php endif; ?>>
                                <button type="submit"
                                        id="telegramBtn"
                                        class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <?php echo empty($current_user['telegram_chat_id']) ? 'Hubungkan' : 'Update'; ?>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">
                                Chat ID bersifat pribadi dan hanya digunakan untuk mengirim notifikasi ke akun Telegram Anda.
                            </p>
                        </div>
                    </form>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 cream-card p-6">
                        <div class="flex justify-between items-center mb-6 border-b pb-4 cream-border">
                            <div>
                                <h2 class="text-2xl font-bold text-dark mb-2">Riwayat Permohonan Terbaru</h2>
                                <p class="text-sm text-gray-500 mt-1 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <?php echo count($recent_requests); ?> permintaan terbaru
                                </p>
                            </div>
                            <a href="<?php echo BASE_URL; ?>/requests" class="text-sm font-semibold text-primary hover:text-dark transition duration-150 flex items-center bg-primary/10 px-4 py-2 rounded-lg hover:bg-primary/20">
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
                                        <th class="px-5 py-4 border-b-2 cream-border">JENIS SURAT</th>
                                        <th class="px-5 py-4 border-b-2 cream-border">STATUS</th>
                                        <th class="px-5 py-4 border-b-2 cream-border">TANGGAL</th>
                                        <th class="px-5 py-4 border-b-2 cream-border">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($recent_requests)): ?>
                                        <?php foreach ($recent_requests as $request): ?>
                                        <tr class="table-row-hover">
                                            <td class="px-5 py-4 border-b cream-border text-sm font-medium">
                                                <?php echo htmlspecialchars($request['letter_type_name']); ?>
                                            </td>
                                            <td class="px-5 py-4 border-b cream-border text-sm">
                                                <span class="status-<?php
                                                    echo $request['status'] === 'approved' ? 'approved' :
                                                         ($request['status'] === 'pending' ? 'waiting' :
                                                          ($request['status'] === 'rejected' ? 'rejected' :
                                                           ($request['status'] === 'completed' ? 'completed' : 'waiting'))); ?>">
                                                    <?php
                                                    echo $request['status'] === 'approved' ? 'Disetujui' :
                                                         ($request['status'] === 'pending' ? 'Menunggu' :
                                                          ($request['status'] === 'rejected' ? 'Ditolak' :
                                                           ($request['status'] === 'completed' ? 'Selesai' : 'Menunggu'))); ?>
                                                </span>
                                            </td>
                                            <td class="px-5 py-4 border-b cream-border text-sm text-gray-500">
                                                <?php echo date('d M Y', strtotime($request['created_at'])); ?>
                                            </td>
                                            <td class="px-5 py-4 border-b cream-border text-sm">
                                                <div class="flex items-center space-x-2">
                                                    <button onclick="window.location.href='<?php echo BASE_URL; ?>/requests'"
                                                            title="Lihat Detail"
                                                            class="action-button text-primary hover:text-dark transition p-2 rounded-lg hover:bg-primary/10">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="px-5 py-8 border-b cream-border text-center text-gray-500">
                                                <div class="flex flex-col items-center">
                                                    <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    <p>Belum ada riwayat permohonan</p>
                                                    <a href="<?php echo BASE_URL; ?>/requests/create"
                                                       class="text-primary hover:text-primary-light mt-2">Buat permohonan pertama Anda</a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="space-y-6">

                        <div class="cream-card p-6">
                            <div class="flex justify-between items-center mb-6 border-b pb-4 cream-border">
                                <h3 class="text-xl font-bold text-dark">Aktivitas Terbaru</h3>
                                <span class="text-xs text-primary font-semibold bg-primary/10 px-3 py-1 rounded-full">Aktivitas</span>
                            </div>
                            <ul class="space-y-5">
                                <li class="flex items-start group">
                                    <div class="w-9 h-9 rounded-full bg-primary/10 text-primary flex items-center justify-center shrink-0 mr-3 mt-1 group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-dark group-hover:text-primary transition-colors">Permohonan baru dibuat</p>
                                        <p class="text-xs text-gray-500 mt-1"><?php echo date('d M Y, H:i', strtotime('now')); ?></p>
                                    </div>
                                </li>
                                <li class="flex items-start group">
                                    <div class="w-9 h-9 rounded-full bg-secondary/10 text-secondary flex items-center justify-center shrink-0 mr-3 mt-1 group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-dark group-hover:text-secondary transition-colors">Menunggu verifikasi admin</p>
                                        <p class="text-xs text-gray-500 mt-1"><?php echo date('d M Y, H:i', strtotime('-1 hour')); ?></p>
                                    </div>
                                </li>
                                <li class="flex items-start group">
                                    <div class="w-9 h-9 rounded-full bg-accent/10 text-accent flex items-center justify-center shrink-0 mr-3 mt-1 group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-dark group-hover:text-accent transition-colors">Surat siap diunduh</p>
                                        <p class="text-xs text-gray-500 mt-1"><?php echo date('d M Y, H:i', strtotime('-2 hours')); ?></p>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="cream-card p-6">
                            <div class="flex justify-between items-center mb-6 border-b pb-4 cream-border">
                                <h3 class="text-xl font-bold text-dark">Panduan</h3>
                                <span class="text-xs text-primary font-semibold bg-primary/10 px-3 py-1 rounded-full">Bantuan</span>
                            </div>
                            <div class="space-y-4 text-sm">
                                <div class="flex items-start space-x-3 p-3 bg-primary/5 rounded-lg">
                                    <div class="w-8 h-8 bg-primary/10 text-primary rounded-lg flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-dark">Buat Permohonan</p>
                                        <p class="text-gray-600">Klik menu "Buat Surat" untuk membuat permohonan baru</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3 p-3 bg-secondary/5 rounded-lg">
                                    <div class="w-8 h-8 bg-secondary/10 text-secondary rounded-lg flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-dark">Pantau Status</p>
                                        <p class="text-gray-600">Cek status permohonan di menu "Riwayat Surat"</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3 p-3 bg-accent/5 rounded-lg">
                                    <div class="w-8 h-8 bg-accent/10 text-accent rounded-lg flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-dark">Download Surat</p>
                                        <p class="text-gray-600">Unduh surat yang sudah disetujui</p>
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

<script>
// Handle Telegram Chat ID form submission
document.getElementById('telegramForm').addEventListener('submit', function(e) {
    const btn = document.getElementById('telegramBtn');
    const originalText = btn.innerHTML;

    // Show loading state
    btn.disabled = true;
    btn.innerHTML = `
        <svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Menyimpan...
    `;

    // Form will submit normally, loading state will be handled by page reload
});

// Auto-hide success/error messages after 5 seconds
setTimeout(function() {
    const alerts = document.querySelectorAll('.bg-green-100, .bg-red-100, .bg-blue-100');
    alerts.forEach(function(alert) {
        alert.style.transition = 'opacity 0.5s ease-out';
        alert.style.opacity = '0';
        setTimeout(function() {
            alert.remove();
        }, 500);
    });
}, 5000);
</script>
