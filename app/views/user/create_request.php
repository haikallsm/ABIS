<?php
// Set page metadata
$title = 'Buat Permohonan Surat';
$extra_css = [];
$extra_js = ['create-request'];
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
                </div>

                <div class="px-6">
                    <p class="sidebar-section-title uppercase tracking-wider mb-3">Layanan Surat</p>

                    <a href="<?php echo BASE_URL; ?>/requests/create" class="sidebar-link active flex items-center py-4">
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
                        <h1 class="text-2xl font-bold mb-2">Buat Permohonan Surat</h1>
                        <p class="text-lg opacity-95 mb-1">Pilih jenis surat dan lengkapi data yang diperlukan</p>
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
            <div class="max-w-4xl mx-auto">
                <div class="cream-card p-8">
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-dark mb-4">Pilih Jenis Surat</h2>
                        <p class="text-gray-600">Pilih jenis surat yang ingin Anda ajukan permohonannya</p>
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

                    <form id="requestForm" method="POST" action="<?php echo BASE_URL; ?>/requests/create" class="space-y-6">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

                        <!-- Letter Type Selection -->
                        <div>
                            <label for="letter_type_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Jenis Surat <span class="text-red-500">*</span>
                            </label>
                            <select id="letter_type_id" name="letter_type_id" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors">
                                <option value="">Pilih jenis surat...</option>
                                <?php foreach ($letterTypes as $letterType): ?>
                                    <option value="<?php echo $letterType['id']; ?>" data-description="<?php echo htmlspecialchars($letterType['description']); ?>">
                                        <?php echo htmlspecialchars($letterType['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <p id="letterTypeDescription" class="mt-2 text-sm text-gray-500 hidden"></p>
                        </div>

                        <!-- Dynamic Fields Container -->
                        <div id="dynamicFields" class="space-y-4 hidden">
                            <div class="border-t pt-6">
                                <h3 class="text-lg font-semibold text-dark mb-4">Data Permohonan</h3>
                                <div id="fieldsContainer"></div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end space-x-4 pt-6 border-t">
                            <a href="<?php echo BASE_URL; ?>/dashboard"
                               class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                                Batal
                            </a>
                            <button type="submit" id="submitBtn"
                                    class="px-8 py-3 bg-primary text-white rounded-lg hover:bg-primary-light transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                    Ajukan Permohonan
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            </main>
        </div>
    </div>
</div>

<script>
// Make BASE_URL available to JavaScript
const BASE_URL = '<?php echo BASE_URL; ?>';
</script>
