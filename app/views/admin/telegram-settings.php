<?php
/**
 * Telegram Bot Settings Page - Clean & Modern Design
 */
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

                    <a href="<?php echo BASE_URL; ?>/admin/telegram-settings" class="sidebar-link active flex items-center py-4">
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
    <div class="flex-1 flex flex-col overflow-hidden main-panel">

        <!-- STICKY HEADER SECTION -->
        <div class="sticky-header-container">
            <div class="px-6">
                <div class="welcome-box p-6 text-white">
                    <div class="flex justify-between items-start relative z-10">
                                <div>
                                    <h1 class="text-2xl font-bold mb-2">📱 Telegram Bot Settings</h1>
                                    <p class="text-lg opacity-95 mb-1">Konfigurasi notifikasi otomatis untuk permohonan surat</p>
                                </div>

                        <div class="text-right">
                            <div class="profile-box inline-flex items-center space-x-4">
                                <div class="w-12 h-12 profile-avatar rounded-full flex items-center justify-center text-lg">
                                    <span class="font-bold text-white"><?php echo strtoupper(substr(getCurrentUser()['full_name'] ?? 'A', 0, 1)); ?></span>
                                </div>
                                <div class="text-white">
                                    <p class="font-bold text-lg"><?php echo htmlspecialchars(getCurrentUser()['full_name'] ?? 'Admin'); ?></p>
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
            <main class="px-6 py-8 max-w-7xl mx-auto">

        <!-- Alert Messages -->
        <?php if (!empty($error)): ?>
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-xl">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <p class="font-medium"><?php echo htmlspecialchars($error); ?></p>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-xl">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="font-medium"><?php echo htmlspecialchars($success); ?></p>
                </div>
            </div>
        <?php endif; ?>

                <!-- Quick Stats Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Bot Status -->
                    <div class="cream-card p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 font-medium">Status Bot</p>
                                <p class="text-2xl font-bold mt-1 <?php echo $botInfo ? 'text-green-600' : 'text-red-600'; ?>">
                                    <?php echo $botInfo ? '🟢 Aktif' : '🔴 Nonaktif'; ?>
                                </p>
                            </div>
                            <div class="w-12 h-12 rounded-full flex items-center justify-center <?php echo $botInfo ? 'bg-green-100' : 'bg-red-100'; ?>">
                                <span class="text-2xl"><?php echo $botInfo ? '🤖' : '❌'; ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Total Messages -->
                    <div class="cream-card p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 font-medium">Log Entries</p>
                                <p class="text-2xl font-bold mt-1 text-blue-600"><?php echo count($recentLogs ?? []); ?></p>
                            </div>
                            <div class="w-12 h-12 rounded-full flex items-center justify-center bg-blue-100">
                                <span class="text-2xl">📋</span>
                            </div>
                        </div>
                    </div>

                    <!-- Notification Status -->
                    <div class="cream-card p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 font-medium">Notifikasi</p>
                                <p class="text-2xl font-bold mt-1 <?php echo ($currentSettings['notifications_enabled'] ?? true) ? 'text-green-600' : 'text-gray-600'; ?>">
                                    <?php echo ($currentSettings['notifications_enabled'] ?? true) ? '🔔 Aktif' : '🔕 Nonaktif'; ?>
                                </p>
                            </div>
                            <div class="w-12 h-12 rounded-full flex items-center justify-center <?php echo ($currentSettings['notifications_enabled'] ?? true) ? 'bg-green-100' : 'bg-gray-100'; ?>">
                                <span class="text-2xl"><?php echo ($currentSettings['notifications_enabled'] ?? true) ? '🔔' : '🔕'; ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Connection Test -->
                    <div class="cream-card p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 font-medium">Test Koneksi</p>
                                <p class="text-sm font-medium mt-1 text-gray-800">Klik untuk test bot</p>
                            </div>
                            <div class="w-12 h-12 rounded-full flex items-center justify-center bg-primary/10">
                                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

                    <!-- Left Column - Bot Configuration -->
                    <div class="xl:col-span-2 space-y-6">

                        <!-- Bot Configuration Card -->
                        <div class="cream-card p-8">
                    <div class="mb-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-2">Konfigurasi Bot Telegram</h2>
                        <p class="text-gray-600">Pengaturan dasar untuk menghubungkan bot Telegram</p>
                    </div>

                    <form method="POST" id="botForm" class="space-y-6">
                        <!-- Bot Token -->
                        <div>
                            <label for="bot_token" class="flex items-center text-sm font-semibold text-gray-800 mb-3">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                                Bot Token <span class="text-red-500">*</span>
                            </label>
                            <input type="password" id="bot_token" name="bot_token"
                                   value="<?php echo htmlspecialchars($currentSettings['bot_token'] ?? ''); ?>"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 text-sm"
                                   placeholder="1234567890:ABCdefGHIjklMNOpqrsTUVwxyz"
                                   required>
                            <div class="mt-3 flex items-start space-x-2">
                                <svg class="w-4 h-4 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-sm text-gray-600">
                                    Dapatkan token dengan mengirim <code class="bg-blue-100 px-1 py-0.5 rounded text-xs">/newbot</code> ke
                                    <a href="https://t.me/BotFather" target="_blank" class="text-blue-600 hover:text-blue-800 font-medium">@BotFather</a>
                                </p>
                            </div>
                        </div>

                        <!-- Chat ID -->
                        <div>
                            <label for="test_chat_id" class="flex items-center text-sm font-semibold text-gray-800 mb-3">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                Chat ID untuk Testing
                            </label>
                            <input type="text" id="test_chat_id" name="test_chat_id"
                                   value="<?php echo htmlspecialchars($currentSettings['test_chat_id'] ?? ''); ?>"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 text-sm"
                                   placeholder="123456789">
                            <div class="mt-3 flex items-start space-x-2">
                                <svg class="w-4 h-4 text-yellow-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                <p class="text-sm text-gray-600">
                                    Kirim pesan ke bot, lalu akses: <code class="bg-yellow-100 px-1 py-0.5 rounded text-xs">https://api.telegram.org/bot&lt;TOKEN&gt;/getUpdates</code>
                                </p>
                            </div>
                        </div>

                        <!-- Settings Toggle -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center <?php echo ($currentSettings['notifications_enabled'] ?? true) ? 'bg-green-100' : 'bg-gray-100'; ?>">
                                        <svg class="w-5 h-5 <?php echo ($currentSettings['notifications_enabled'] ?? true) ? 'text-green-600' : 'text-gray-400'; ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">Notifikasi Otomatis</h4>
                                        <p class="text-xs text-gray-600">Kirim pesan ke user saat surat diproses</p>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="notifications_enabled" name="notifications_enabled"
                                           <?php echo ($currentSettings['notifications_enabled'] ?? true) ? 'checked' : ''; ?>
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-3 pt-2">
                            <button type="submit" name="update_settings"
                                    class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 flex items-center justify-center font-medium">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Pengaturan
                            </button>

                            <button type="submit" name="test_bot" form="botForm"
                                    class="flex-1 bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-200 flex items-center justify-center font-medium">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Test Koneksi Bot
                            </button>
                        </div>
                    </form>
                </div>

                        <!-- Activity Log Card -->
                        <div class="cream-card p-8">
                    <div class="mb-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-2">Log Aktivitas Bot</h2>
                        <p class="text-gray-600">20 entri aktivitas terakhir bot Telegram</p>
                    </div>

                    <?php if (!empty($recentLogs)): ?>
                        <div class="bg-gray-900 rounded-lg p-4 max-h-64 overflow-y-auto font-mono text-sm">
                            <?php foreach ($recentLogs as $log): ?>
                                <?php
                                // Parse log untuk better display
                                if (preg_match('/\[([^\]]+)\]\s*(.+)/', $log, $matches)) {
                                    $timestamp = $matches[1];
                                    $message = $matches[2];
                                    $isError = strpos(strtolower($message), 'error') !== false || strpos(strtolower($message), 'failed') !== false;
                                    $colorClass = $isError ? 'text-red-400' : 'text-green-400';
                                ?>
                                    <div class="flex items-start space-x-3 py-1">
                                        <span class="text-gray-500 text-xs shrink-0"><?php echo $timestamp; ?></span>
                                        <span class="text-gray-300 text-xs">→</span>
                                        <span class="<?php echo $colorClass; ?> text-xs break-all"><?php echo htmlspecialchars($message); ?></span>
                                    </div>
                                <?php } else { ?>
                                    <div class="text-gray-400 text-xs py-1"><?php echo htmlspecialchars($log); ?></div>
                                <?php } ?>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-4 flex items-center justify-between text-sm">
                            <span class="text-gray-600">Menampilkan <?php echo count($recentLogs ?? []); ?> log terbaru</span>
                            <span class="text-gray-500">📄 <?php echo htmlspecialchars(basename(TELEGRAM_LOG_FILE)); ?></span>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Aktivitas</h4>
                            <p class="text-gray-500">Log aktivitas akan muncul di sini setelah bot digunakan</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Column - Info & Preview -->
            <div class="space-y-6">

                        <!-- Bot Status Card -->
                        <div class="cream-card p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Status Bot
                        </h3>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 rounded-full <?php echo $botInfo ? 'bg-green-400' : 'bg-red-400'; ?>"></div>
                            <span class="text-sm font-medium <?php echo $botInfo ? 'text-green-600' : 'text-red-600'; ?>">
                                <?php echo $botInfo ? 'Online' : 'Offline'; ?>
                            </span>
                        </div>
                    </div>

                    <?php if ($botInfo): ?>
                        <div class="space-y-4">
                            <div class="flex items-center p-4 bg-green-50 rounded-lg border border-green-200">
                                <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="font-semibold text-green-800">Bot Terhubung!</p>
                                    <p class="text-sm text-green-600">Siap mengirim notifikasi</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-3">
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="text-sm text-gray-600">Nama Bot</span>
                                    <span class="text-sm font-medium"><?php echo htmlspecialchars($botInfo['result']['first_name'] ?? 'Unknown'); ?></span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="text-sm text-gray-600">Username</span>
                                    <span class="text-sm font-medium">@<?php echo htmlspecialchars($botInfo['result']['username'] ?? 'Unknown'); ?></span>
                                </div>
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-sm text-gray-600">Notifikasi</span>
                                    <span class="text-sm font-medium <?php echo ($currentSettings['notifications_enabled'] ?? true) ? 'text-green-600' : 'text-red-600'; ?>">
                                        <?php echo ($currentSettings['notifications_enabled'] ?? true) ? 'Aktif' : 'Nonaktif'; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">Bot Belum Dikonfigurasi</h4>
                            <p class="text-gray-500 text-sm mb-4">Isi Bot Token dan Chat ID di form sebelah kiri</p>
                            <button type="submit" name="test_bot" form="botForm"
                                    class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200 text-sm font-medium">
                                Test Koneksi
                            </button>
                        </div>
                    <?php endif; ?>
                </div>

                        <!-- Setup Guide Card -->
                        <div class="cream-card p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Panduan Setup
                    </h3>

                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center shrink-0">
                                <span class="text-xs font-bold text-blue-600">1</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Buat Bot</p>
                                <p class="text-xs text-gray-600">Kirim /newbot ke @BotFather</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center shrink-0">
                                <span class="text-xs font-bold text-green-600">2</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Isi Token</p>
                                <p class="text-xs text-gray-600">Masukkan token yang diberikan</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 rounded-full bg-yellow-100 flex items-center justify-center shrink-0">
                                <span class="text-xs font-bold text-yellow-600">3</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Dapatkan Chat ID</p>
                                <p class="text-xs text-gray-600">Akses getUpdates API</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 rounded-full bg-purple-100 flex items-center justify-center shrink-0">
                                <span class="text-xs font-bold text-purple-600">4</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Test & Aktifkan</p>
                                <p class="text-xs text-gray-600">Test koneksi dan aktifkan notifikasi</p>
                            </div>
                        </div>
                    </div>
                </div>

                        <!-- Message Preview Card -->
                        <div class="cream-card p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        Preview Pesan
                    </h3>

                    <div class="space-y-4">
                        <!-- Approval Message -->
                        <div>
                            <div class="flex items-center mb-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Disetujui
                                </span>
                            </div>
                            <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-sm">
                                <div class="font-medium text-green-800 mb-1">✅ Permohonan Disetujui</div>
                                <div class="text-green-700 text-xs space-y-0.5">
                                    <div>📋 Surat Pengantar #001</div>
                                    <div>👤 John Doe</div>
                                    <div>📎 Download tersedia</div>
                                </div>
                            </div>
                        </div>

                        <!-- Rejection Message -->
                        <div>
                            <div class="flex items-center mb-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Ditolak
                                </span>
                            </div>
                            <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-sm">
                                <div class="font-medium text-red-800 mb-1">❌ Permohonan Ditolak</div>
                                <div class="text-red-700 text-xs space-y-0.5">
                                    <div>📋 Surat Keterangan #002</div>
                                    <div>👤 Jane Doe</div>
                                    <div>💬 Dokumen tidak lengkap</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>