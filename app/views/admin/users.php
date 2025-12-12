<?php
// Set page metadata
$title = 'Manajemen Users';
$extra_css = ['sidebar.css', 'admin-users.css'];
$extra_js = ['sidebar.js', 'admin-users.js'];
?>

<body class="bg-gray-100 antialiased">
<div class="flex h-screen overflow-hidden">
    <aside class="w-72 bg-gray-100 shadow-lg flex flex-col">
        <div class="logo-section">
            <div class="logo-text">
                <i class="fas fa-leaf"></i>
                <span>e-Desa Penglipuran</span>
            </div>
            <div class="logo-subtitle">Dashboard Admin</div>
        </div>

        <!-- Menu Profil di atas -->
        <div class="menu-section">
            <div class="menu-title">Akun Saya</div>
            <div class="menu-item" data-target="profil">
                <i class="fas fa-user-circle"></i>
                <span class="menu-text">Profil Saya</span>
            </div>
        </div>

        <div class="menu-section">
            <div class="menu-title">Menu Utama</div>
            <div class="menu-item" data-target="dashboard">
                <i class="fas fa-home"></i>
                <span class="menu-text">Dashboard</span>
            </div>
            <div class="menu-item" data-target="pengajuan">
                <i class="fas fa-file-alt"></i>
                <span class="menu-text">Buat Surat</span>
            </div>
            <div class="menu-item" data-target="riwayat">
                <i class="fas fa-history"></i>
                <span class="menu-text">Riwayat Surat</span>
            </div>
            <div class="menu-item" id="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span class="menu-text">Keluar</span>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-8">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-2xl font-bold mb-6 text-primary">Manajemen Users</h1>

            <!-- Display success/error messages -->
            <?php if (isset($_SESSION['success'])): ?>
                <div id="success-alert" class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center">
                    <i class="fas fa-check-circle mr-3"></i>
                    <?php echo htmlspecialchars($_SESSION['success']); ?>
                    <?php unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div id="error-alert" class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center">
                    <i class="fas fa-exclamation-circle mr-3"></i>
                    <?php echo htmlspecialchars($_SESSION['error']); ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <!-- SEARCH -->
            <div class="flex justify-between mb-4">
                <input id="searchInput" type="text" placeholder="Cari nama atau email..."
                    class="w-1/3 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary outline-none"
                    onkeyup="searchUser()" value="<?php echo htmlspecialchars($search ?? ''); ?>">
            </div>

            <!-- USERS TABLE -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-[680px] w-full text-left">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 w-16 text-sm font-semibold text-gray-600 text-center cursor-pointer" onclick="sortTable(0)">No</th>
                                <th class="px-6 py-3 text-sm font-semibold text-gray-600 min-w-[240px] cursor-pointer" onclick="sortTable(1)">Nama</th>
                                <th class="px-6 py-3 text-sm font-semibold text-gray-600 min-w-[260px] cursor-pointer" onclick="sortTable(2)">Email</th>
                                <th class="px-6 py-3 w-28 text-sm font-semibold text-gray-600">Level</th>
                                <th class="px-6 py-3 w-48 text-sm font-semibold text-gray-600 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="userTable" class="divide-y bg-white text-sm">
                            <?php $no = ((($users_data['current_page'] ?? 1) - 1) * ITEMS_PER_PAGE + 1); ?>
                            <?php foreach (($users_data['users'] ?? []) as $user): ?>
                            <tr>
                                <td class="px-6 py-4 text-center font-medium"><?php echo $no++; ?></td>
                                <td class="px-6 py-4 font-medium text-gray-800 truncate"><?php echo htmlspecialchars($user['full_name']); ?></td>
                                <td class="px-6 py-4 text-gray-600 truncate"><?php echo htmlspecialchars($user['email']); ?></td>
                                <td class="px-6 py-4"><?php echo ucfirst($user['role']); ?></td>
                                <td class="px-6 py-4 text-center">
                                    <div class="inline-flex items-center justify-center gap-2">
                                        <?php if ($user['id'] !== getCurrentUserId()): ?>
                                        <form method="POST" action="<?php echo BASE_URL; ?>/admin/users/<?php echo $user['id']; ?>/reset-password"
                                              onsubmit="return confirm('Apakah Anda yakin ingin mereset password <?php echo htmlspecialchars($user['full_name']); ?>?')"
                                              class="inline">
                                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                            <button type="submit" class="px-3 py-1 bg-primary-blue text-white rounded-lg text-xs">Reset</button>
                                        </form>
                                        <form method="POST" action="<?php echo BASE_URL; ?>/admin/users/<?php echo $user['id']; ?>/delete"
                                              onsubmit="return confirmDelete('<?php echo htmlspecialchars($user['full_name']); ?>')"
                                              class="inline">
                                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                            <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded-lg text-xs">Hapus</button>
                                        </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <?php if (($users_data['pages'] ?? 0) > 1): ?>
            <div class="mt-6 flex justify-center">
                <div class="flex space-x-2">
                    <?php if (($users_data['current_page'] ?? 1) > 1): ?>
                    <a href="?page=<?php echo ($users_data['current_page'] - 1); ?>&search=<?php echo urlencode($search ?? ''); ?>"
                       class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm hover:bg-gray-50">Previous</a>
                    <?php endif; ?>

                    <?php
                    $current_page = $users_data['current_page'] ?? 1;
                    $total_pages = $users_data['pages'] ?? 1;
                    $start_page = max(1, $current_page - 2);
                    $end_page = min($total_pages, $current_page + 2);

                    for ($i = $start_page; $i <= $end_page; $i++):
                    ?>
                    <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search ?? ''); ?>"
                       class="px-4 py-2 <?php echo $i === $current_page ? 'bg-primary-blue text-white' : 'bg-white border border-gray-300 hover:bg-gray-50'; ?> rounded-lg text-sm">
                        <?php echo $i; ?>
                    </a>
                    <?php endfor; ?>

                    <?php if (($users_data['current_page'] ?? 1) < ($users_data['pages'] ?? 1)): ?>
                    <a href="?page=<?php echo ($users_data['current_page'] + 1); ?>&search=<?php echo urlencode($search ?? ''); ?>"
                       class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm hover:bg-gray-50">Next</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<!-- SweetAlert2 for notifications -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const successAlert = document.getElementById('success-alert');
    const errorAlert = document.getElementById('error-alert');

    if (successAlert) {
        setTimeout(() => {
            successAlert.style.opacity = '0';
            setTimeout(() => successAlert.remove(), 300);
        }, 5000);
    }

    if (errorAlert) {
        setTimeout(() => {
            errorAlert.style.opacity = '0';
            setTimeout(() => errorAlert.remove(), 300);
        }, 5000);
    }
});
</script>
