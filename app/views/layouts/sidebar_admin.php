<!-- SIDEBAR extracted as global component -->
<!-- Sidebar - Mobile-first responsive -->
<aside id="sidebar" class="w-64 lg:w-72 flex flex-col fixed lg:static inset-y-0 left-0 z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-500 ease-out shadow-2xl lg:shadow-none overflow-y-auto mobile-scroll" style="background: linear-gradient(135deg, #2D4A3A 0%, #4C7A5B 100%);">
    <!-- FIX: sidebar color match welcome-box gradient -->

    <!-- sidebar header removed to show menu immediately -->
    <div class="flex-1 sidebar-nav py-2">
        <nav class="space-y-1">
            <div class="px-4 lg:px-6">
                <button id="sidebarToggle" onclick="toggleSidebar()" class="p-2 rounded-md bg-white/10 hover:bg-white/20 text-white focus:outline-none mr-3 inline-flex items-center" aria-label="Toggle sidebar">
                    <img src="<?php echo ASSETS_URL; ?>/icons/hamburger.svg" class="w-5 h-5" alt="Menu">
                </button>
                <h2 class="text-xl lg:text-2xl font-extrabold mb-2 text-white inline-block align-middle">Surat - In</h2>
                <p class="sidebar-section-title uppercase tracking-wider mb-3">Menu Utama</p>

                <a href="<?php echo BASE_URL; ?>/admin/dashboard" class="sidebar-link active flex items-center py-4">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>
            </div>

            <div class="px-4 lg:px-6">
                <p class="sidebar-section-title uppercase tracking-wider mb-3">Pengelolaan Data</p>
                <a href="<?php echo BASE_URL; ?>/admin/users" class="sidebar-link flex items-center py-3 lg:py-4">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20h-5v-2a3 3 0 00-5.356-1.857M9 20v-2a3 3 0 015.548-1.077M10 11a2 2 0 100-4 2 2 0 000 4zm7 0a2 2 0 100-4 2 2 0 000 4zM10 17a5 5 0 008.274 2.87M15 17a5 5 0 008.274 2.87"></path>
                    </svg>
                    <span class="truncate">Users</span>
                </a>

                <a href="<?php echo BASE_URL; ?>/admin/requests" class="sidebar-link flex items-center py-3 lg:py-4">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v4a1 1 0 001 1h6a1 1 0 001-1V7m0 0a2 2 0 00-2-2H9a2 2 0 00-2 2m0 0v11a2 2 0 002 2h4a2 2 0 002-2V7m-4-2H8"></path>
                    </svg>
                    <span class="truncate">Export Data</span>
                </a>
            </div>
            <div class="px-4 lg:px-6">
                <p class="sidebar-section-title uppercase tracking-wider mb-3">Surat & Dokumen</p>

                <a href="<?php echo BASE_URL; ?>/admin/letter-requests" class="sidebar-link flex items-center py-3 lg:py-4">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2m-6 0h6"></path>
                    </svg>
                    <span class="truncate">Pengajuan Surat</span>
                </a>

                <a href="<?php echo BASE_URL; ?>/admin/telegram-settings" class="sidebar-link flex items-center py-3 lg:py-4">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <span class="truncate">Telegram Bot</span>
                </a>

            </div>
        </nav>

        <!-- Logout sederhana di bawah -->
        <div class="px-4 lg:px-6 mt-auto">
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
