<?php
// Set page metadata
$title = 'Manajemen Users';
$extra_css = ['admin-dashboard.css'];
$extra_js = ['admin-dashboard.js'];
?>

<body class="cream-bg antialiased">
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

                    <a href="<?php echo BASE_URL; ?>/admin/users" class="sidebar-link active flex items-center py-4">
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
    <div class="flex-1 flex flex-col overflow-auto main-panel">

        <!-- HEADER JUDUL YANG LEBIH MENARIK -->
        <div class="sticky-header-container">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex items-center gap-5 mb-2">
                    <!-- Icon dekoratif dengan gradient -->
                    <div class="p-3 rounded-2xl bg-linear-to-br from-primary/20 to-secondary/20 border-2 border-white/50 shadow-lg backdrop-blur-sm">
                        <div class="p-3 rounded-xl bg-linear-to-br from-primary to-secondary shadow-inner">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20h-5v-2a3 3 0 00-5.356-1.857M9 20v-2a3 3 0 015.548-1.077M10 11a2 2 0 100-4 2 2 0 000 4zm7 0a2 2 0 100-4 2 2 0 000 4zM10 17a5 5 0 008.274 2.87M15 17a5 5 0 008.274 2.87"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Judul dengan efek menarik -->
                    <div class="flex-1">
                        <div class="flex items-center gap-4">
                            <div>
                                <h1 class="text-3xl font-bold bg-linear-to-r from-primary via-primary-light to-secondary bg-clip-text text-transparent tracking-tight">
                                    Manajemen Users
                                </h1>
                                <!-- Garis dekoratif bawah judul -->
                                <div class="h-1.5 w-40 mt-2 bg-linear-to-r from-primary/60 to-secondary/40 rounded-full"></div>
                            </div>

                            <!-- Badge jumlah users -->
                            <div class="px-4 py-1.5 bg-linear-to-r from-accent/20 to-highlight/20 rounded-full border border-accent/30">
                                <span class="text-sm font-semibold text-dark flex items-center gap-1.5">
                                    <span class="w-2 h-2 bg-accent rounded-full animate-pulse"></span>
                                    <span class="bg-linear-to-r from-dark to-primary bg-clip-text text-transparent"><?php echo count($users_data['users'] ?? []); ?> Users</span>
                                </span>
                            </div>
                        </div>

                        <p class="text-gray-600 mt-3 pl-1 text-lg font-medium">
                            Kelola dan pantau semua pengguna sistem dengan mudah
                        </p>
                    </div>
                </div>

                <!-- Dekorasi tambahan -->
                <div class="flex items-center gap-6 mt-4">
                    <div class="flex items-center gap-2 text-sm">
                        <div class="w-3 h-3 rounded-full bg-primary animate-pulse"></div>
                        <span class="text-gray-600 font-medium">Admin</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm">
                        <div class="w-3 h-3 rounded-full bg-secondary animate-pulse"></div>
                        <span class="text-gray-600 font-medium">User</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm">
                        <div class="w-3 h-3 rounded-full bg-accent animate-pulse"></div>
                        <span class="text-gray-600 font-medium">Aktif</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- SCROLLABLE CONTENT SECTION -->
        <div class="scroll-container h-full overflow-y-auto">
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

                <!-- Users Management Section -->
                <div class="cream-card p-8 mb-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-gray-800">Daftar Users</h2>
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span>Cari dan kelola pengguna sistem</span>
                        </div>
                    </div>

                    <!-- SEARCH -->
                    <div class="flex justify-between items-center mb-6">
                        <div class="relative flex-1 max-w-md">
                            <input id="searchInput" type="text" placeholder="Cari nama atau email..."
                                class="w-full px-4 py-3 pl-12 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition duration-300"
                                onkeyup="searchUser()" value="<?php echo htmlspecialchars($search ?? ''); ?>">
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 ml-4">
                            <button onclick="refreshUsers()" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-light transition-colors duration-200 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                <span>Refresh</span>
                            </button>
                        </div>
                    </div>

                    <!-- Users Table -->
                    <div class="overflow-x-auto mb-6">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-linear-to-r from-gray-50 to-gray-100">
                                    <th class="px-6 py-4 w-16 text-sm font-bold text-gray-700 text-center border-r border-gray-200">No</th>
                                    <th class="px-6 py-4 text-sm font-bold text-gray-700 border-r border-gray-200 min-w-[200px]">Nama Lengkap</th>
                                    <th class="px-6 py-4 text-sm font-bold text-gray-700 border-r border-gray-200 min-w-[250px]">Email</th>
                                    <th class="px-6 py-4 text-sm font-bold text-gray-700 border-r border-gray-200 w-32">Role</th>
                                    <th class="px-6 py-4 text-sm font-bold text-gray-700 border-r border-gray-200 w-40">Status</th>
                                    <th class="px-6 py-4 w-48 text-sm font-bold text-gray-700 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="userTable" class="divide-y divide-gray-100">
                            <?php $no = 1; ?>
                            <?php foreach (($users_data['users'] ?? []) as $user): ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-center font-medium text-gray-800"><?php echo $no++; ?></td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-linear-to-br from-primary to-primary-light flex items-center justify-center text-white font-semibold text-sm">
                                                <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-800"><?php echo htmlspecialchars($user['full_name']); ?></div>
                                                <div class="text-xs text-gray-500">ID: <?php echo $user['id']; ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                            </svg>
                                            <span class="text-gray-600"><?php echo htmlspecialchars($user['email']); ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1.5 rounded-full text-xs font-semibold <?php echo $user['role'] === 'admin' ? 'bg-blue-100 text-blue-700 border border-blue-200' : 'bg-green-100 border-green-200'; ?>">
                                            <?php echo ucfirst($user['role']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1.5 bg-green-100 text-green-700 font-medium rounded-full text-xs border border-green-200">
                                            Aktif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <?php if ($user['id'] !== getCurrentUserId()): ?>
                                            <button onclick="viewUser(<?php echo $user['id']; ?>)" class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg text-xs font-medium hover:bg-blue-200 transition-colors flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Lihat
                                            </button>
                                            <button onclick="resetPassword(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['full_name']); ?>')" class="px-3 py-1.5 bg-yellow-100 text-yellow-700 rounded-lg text-xs font-medium hover:bg-yellow-200 transition-colors flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                </svg>
                                                Reset
                                            </button>
                                            <button onclick="deleteUser(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['full_name']); ?>')" class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-xs font-medium hover:bg-red-200 transition-colors flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Hapus
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

                </div>
            </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 for notifications -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Make BASE_URL available to JavaScript
const BASE_URL = '<?php echo BASE_URL; ?>';

// User management functions
function viewUser(userId) {
    window.location.href = `${BASE_URL}/admin/users/${userId}`;
}

function resetPassword(userId, userName) {
    Swal.fire({
        title: 'Reset Password',
        text: `Apakah Anda yakin ingin mereset password ${userName}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#4C7A5B',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Reset',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Create and submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `${BASE_URL}/admin/users/${userId}/reset-password`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = 'csrf_token';
            csrfToken.value = '<?php echo generateCSRFToken(); ?>';

            form.appendChild(csrfToken);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function deleteUser(userId, userName) {
    Swal.fire({
        title: 'Hapus User',
        text: `Apakah Anda yakin ingin menghapus user ${userName}? Tindakan ini tidak dapat dibatalkan.`,
        icon: 'error',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Create and submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `${BASE_URL}/admin/users/${userId}/delete`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = 'csrf_token';
            csrfToken.value = '<?php echo generateCSRFToken(); ?>';

            form.appendChild(csrfToken);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function refreshUsers() {
    window.location.reload();
}

function searchUser() {
    const searchTerm = document.getElementById('searchInput').value;
    // For now, just show alert that search is not implemented without pagination
    // In future, we can implement client-side filtering if needed
    if (searchTerm.trim()) {
        alert('Fitur pencarian sementara tidak tersedia. Halaman akan memuat ulang untuk menampilkan semua data.');
        window.location.reload();
    }
}

// Auto-hide flash messages after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const flashMessages = document.querySelectorAll('.bg-green-100, .bg-red-100');

    flashMessages.forEach(message => {
        setTimeout(() => {
            message.style.opacity = '0';
            message.style.transform = 'translateY(-10px)';
            message.style.transition = 'all 0.3s ease';
            setTimeout(() => message.remove(), 300);
        }, 5000);
    });
});
</script>
