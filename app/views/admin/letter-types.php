<?php
// Set page metadata
$title = 'Jenis Surat';
$extra_css = ['admin-dashboard.css'];
$extra_js = ['admin-dashboard'];
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

                    <a href="<?php echo BASE_URL; ?>/admin/letter-requests" class="sidebar-link flex items-center py-4">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2m-6 0h6"></path>
                        </svg>
                        <span>Pengajuan Surat</span>
                    </a>

                    <a href="<?php echo BASE_URL; ?>/admin/letter-types" class="sidebar-link active flex items-center py-4">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Jenis Surat</span>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Judul dengan efek menarik -->
                    <div class="flex-1">
                        <div class="flex items-center gap-4">
                            <div>
                                <h1 class="text-3xl font-bold bg-linear-to-r from-primary via-primary-light to-secondary bg-clip-text text-transparent tracking-tight">
                                    Jenis Surat
                                </h1>
                                <!-- Garis dekoratif bawah judul -->
                                <div class="h-1.5 w-32 mt-2 bg-linear-to-r from-primary/60 to-secondary/40 rounded-full"></div>
                            </div>

                            <!-- Badge jumlah types -->
                            <div class="px-4 py-1.5 bg-linear-to-r from-accent/20 to-highlight/20 rounded-full border border-accent/30">
                                <span class="text-sm font-semibold text-dark flex items-center gap-1.5">
                                    <span class="w-2 h-2 bg-accent rounded-full animate-pulse"></span>
                                    <span class="bg-linear-to-r from-dark to-primary bg-clip-text text-transparent"><?php echo count($letterTypes ?? []); ?> Jenis Surat</span>
                                </span>
                            </div>
                        </div>

                        <p class="text-gray-600 mt-3 pl-1 text-lg font-medium">
                            Kelola dan atur jenis-jenis surat yang tersedia dalam sistem
                        </p>
                    </div>
                </div>

                <!-- Dekorasi tambahan -->
                <div class="flex items-center gap-6 mt-4">
                    <div class="flex items-center gap-2 text-sm">
                        <div class="w-3 h-3 rounded-full bg-primary animate-pulse"></div>
                        <span class="text-gray-600 font-medium">Aktif</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm">
                        <div class="w-3 h-3 rounded-full bg-gray-400 animate-pulse"></div>
                        <span class="text-gray-600 font-medium">Tidak Aktif</span>
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

                <!-- Letter Types Management Section -->
                <div class="cream-card p-8 mb-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-gray-800">Daftar Jenis Surat</h2>
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Kelola jenis surat yang tersedia</span>
                        </div>
                    </div>

                    <!-- Add New Letter Type Button -->
                    <div class="mb-6">
                        <button onclick="showAddForm()" class="px-6 py-3 bg-linear-to-r from-primary to-primary-light text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Jenis Surat
                        </button>
                    </div>

                    <!-- Letter Types Table -->
                    <div class="overflow-x-auto mb-6">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-linear-to-r from-gray-50 to-gray-100">
                                    <th class="px-6 py-4 w-16 text-sm font-bold text-gray-700 text-center border-r border-gray-200">No</th>
                                    <th class="px-6 py-4 text-sm font-bold text-gray-700 border-r border-gray-200 min-w-[200px]">Nama Jenis Surat</th>
                                    <th class="px-6 py-4 text-sm font-bold text-gray-700 border-r border-gray-200 min-w-[150px]">Kode</th>
                                    <th class="px-6 py-4 text-sm font-bold text-gray-700 border-r border-gray-200 w-32">Status</th>
                                    <th class="px-6 py-4 w-64 text-sm font-bold text-gray-700 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php $no = 1; ?>
                                <?php foreach (($letterTypes ?? []) as $type): ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-center font-medium text-gray-800"><?php echo $no++; ?></td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-linear-to-br from-primary to-primary-light flex items-center justify-center text-white font-semibold text-xs">
                                                <?php echo strtoupper(substr($type['name'], 0, 1)); ?>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-800"><?php echo htmlspecialchars($type['name']); ?></div>
                                                <div class="text-xs text-gray-500">Dibuat: <?php echo date('d/m/Y', strtotime($type['created_at'])); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 font-medium rounded-full text-xs">
                                            <?php echo htmlspecialchars($type['code']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if ($type['is_active']): ?>
                                            <span class="px-3 py-1.5 bg-green-100 text-green-700 font-medium rounded-full text-xs border border-green-200">
                                                Aktif
                                            </span>
                                        <?php else: ?>
                                            <span class="px-3 py-1.5 bg-gray-100 text-gray-700 font-medium rounded-full text-xs border border-gray-200">
                                                Tidak Aktif
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button onclick="editType(<?php echo $type['id']; ?>, '<?php echo htmlspecialchars($type['name']); ?>', '<?php echo htmlspecialchars($type['code']); ?>', <?php echo $type['is_active'] ? 'true' : 'false'; ?>)" class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg text-xs font-medium hover:bg-blue-200 transition-colors flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Edit
                                            </button>
                                            <?php if ($type['is_active']): ?>
                                            <button onclick="toggleStatus(<?php echo $type['id']; ?>, 'deactivate')" class="px-3 py-1.5 bg-yellow-100 text-yellow-700 rounded-lg text-xs font-medium hover:bg-yellow-200 transition-colors flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                                </svg>
                                                Nonaktifkan
                                            </button>
                                            <?php else: ?>
                                            <button onclick="toggleStatus(<?php echo $type['id']; ?>, 'activate')" class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg text-xs font-medium hover:bg-green-200 transition-colors flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Aktifkan
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if (empty($letterTypes)): ?>
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-500 mb-2">Belum ada jenis surat</h3>
                        <p class="text-gray-400">Tambahkan jenis surat pertama untuk memulai.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="typeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 id="modalTitle" class="text-lg font-semibold text-gray-800 mb-4">Tambah Jenis Surat</h3>

                <form id="typeForm" onsubmit="saveType(event)">
                    <input type="hidden" id="typeId" name="id">

                    <div class="mb-4">
                        <label for="typeName" class="block text-sm font-medium text-gray-700 mb-2">Nama Jenis Surat</label>
                        <input type="text" id="typeName" name="name" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none">
                    </div>

                    <div class="mb-4">
                        <label for="typeCode" class="block text-sm font-medium text-gray-700 mb-2">Kode Surat</label>
                        <input type="text" id="typeCode" name="code" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none"
                               placeholder="Contoh: SK-KTP, SP-DOM">
                    </div>

                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" id="typeActive" name="is_active" class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="ml-2 text-sm text-gray-700">Aktifkan jenis surat ini</span>
                        </label>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-light transition-colors">
                            Simpan
                        </button>
                    </div>
                </form>
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
            setTimeout(() => message.remove(), 300);
        }, 5000);
    });
});

// Show add form
function showAddForm() {
    document.getElementById('modalTitle').textContent = 'Tambah Jenis Surat';
    document.getElementById('typeId').value = '';
    document.getElementById('typeName').value = '';
    document.getElementById('typeCode').value = '';
    document.getElementById('typeActive').checked = true;

    document.getElementById('typeModal').classList.remove('hidden');
}

// Edit type
function editType(id, name, code, isActive) {
    document.getElementById('modalTitle').textContent = 'Edit Jenis Surat';
    document.getElementById('typeId').value = id;
    document.getElementById('typeName').value = name;
    document.getElementById('typeCode').value = code;
    document.getElementById('typeActive').checked = isActive;

    document.getElementById('typeModal').classList.remove('hidden');
}

// Close modal
function closeModal() {
    document.getElementById('typeModal').classList.add('hidden');
}

// Save type
function saveType(event) {
    event.preventDefault();

    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData);

    const url = data.id ? `${BASE_URL}/admin/letter-types/${data.id}` : `${BASE_URL}/admin/letter-types`;
    const method = data.id ? 'PUT' : 'POST';

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': '<?php echo generateCSRFToken(); ?>'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            closeModal();
            location.reload();
        } else {
            alert('Error: ' + result.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan data');
    });
}

// Toggle status
function toggleStatus(id, action) {
    const actionText = action === 'activate' ? 'mengaktifkan' : 'menonaktifkan';

    if (confirm(`Apakah Anda yakin ingin ${actionText} jenis surat ini?`)) {
        fetch(`${BASE_URL}/admin/letter-types/${id}/toggle`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': '<?php echo generateCSRFToken(); ?>'
            },
            body: JSON.stringify({ action: action })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                location.reload();
            } else {
                alert('Error: ' + result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengubah status');
        });
    }
}
</script>
