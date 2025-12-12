<?php
// Set page metadata
$title = 'Surat Pengantar';
$extra_css = ['sidebar.css', 'admin-requests.css'];
$extra_js = ['sidebar.js', 'admin-requests.js'];
?>

<body class="bg-gray-100 antialiased">
<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside class="w-72 bg-gray-100 shadow-lg flex flex-col">
        <!-- Sidebar Header -->
        <div class="p-6 pb-8 text-white bg-primary-blue shadow-md">
            <div class="flex items-center">
                <svg class="w-8 h-8 mr-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 20h6a1 1 0 001-1V5a1 1 0 00-1-1H9a1 1 0 00-1 1v14a1 1 0 001 1zm0 0h6"></path>
                </svg>
                <div>
                    <h1 class="text-xl font-bold">ABIS</h1>
                    <p class="text-xs font-light opacity-80">Dashboard Admin</p>
                </div>
            </div>
        </div>

        <!-- Sidebar Navigation -->
        <div class="flex-1 bg-white border-t border-gray-200">
            <nav id="sidebar-nav" class="mt-4 space-y-1">
                <p class="text-xs text-gray-500 uppercase tracking-wider px-6 pt-4 pb-2">Menu Utama</p>

                <a href="<?php echo BASE_URL; ?>/admin/dashboard" class="sidebar-link flex items-center py-3 hover:bg-blue-50">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <p class="text-xs text-gray-500 uppercase tracking-wider px-6 pt-4 pb-2">Pengelolaan Data</p>

                <a href="<?php echo BASE_URL; ?>/admin/users" class="sidebar-link flex items-center py-3 hover:bg-blue-50">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20h-5v-2a3 3 0 00-5.356-1.857M9 20v-2a3 3 0 015.548-1.077M10 11a2 2 0 100-4 2 2 0 000 4zm7 0a2 2 0 100-4 2 2 0 000 4zM10 17a5 5 0 008.274 2.87M15 17a5 5 0 008.274 2.87"></path>
                    </svg>
                    <span>Users</span>
                </a>

                <a href="<?php echo BASE_URL; ?>/admin/requests" class="sidebar-link active flex items-center py-3 hover:bg-blue-50">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v4a1 1 0 001 1h6a1 1 0 001-1V7m0 0a2 2 0 00-2-2H9a2 2 0 00-2 2m0 0v11a2 2 0 002 2h4a2 2 0 002-2V7m-4-2H8"></path>
                    </svg>
                    <span>Pengajuan Surat</span>
                </a>

                <p class="text-xs text-gray-500 uppercase tracking-wider px-6 pt-4 pb-2">Surat & Dokumen</p>

                <a href="<?php echo BASE_URL; ?>/admin/letter-types" class="sidebar-link flex items-center py-3 hover:bg-blue-50">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Jenis Surat</span>
                </a>

                <p class="text-xs text-gray-500 uppercase tracking-wider px-6 pt-4 pb-2">Pengaturan</p>

                <a href="<?php echo BASE_URL; ?>/admin/settings" class="sidebar-link flex items-center py-3 hover:bg-blue-50">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>Pengaturan</span>
                </a>

                <div class="pt-8">
                    <form method="POST" action="<?php echo BASE_URL; ?>/logout" class="inline-block w-full">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        <div class="menu-item" id="logout-btn" onclick="this.parentElement.submit()">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span>Logout</span>
                        </div>
                    </form>
                </div>
            </nav>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-y-auto">
        <!-- Header -->
        <header class="bg-white shadow-sm p-4 border-b flex justify-between items-center z-10">
            <div class="text-gray-800">
                <h1 class="text-2xl font-bold text-dark-blue">Permohonan Surat Pengantar</h1>
                <p class="text-sm text-gray-500">Daftar dan kelola semua permohonan surat pengantar dari warga</p>
            </div>

            <div class="flex items-center space-x-3 bg-blue-50/70 p-2 rounded-lg pr-4">
                <span class="w-10 h-10 rounded-full bg-primary-blue text-white font-bold flex items-center justify-center">
                    <?php echo strtoupper(substr(getCurrentUser()['full_name'], 0, 1)); ?>
                </span>
                <div class="text-left">
                    <p class="text-sm font-semibold text-gray-800"><?php echo htmlspecialchars(getCurrentUser()['full_name']); ?></p>
                    <p class="text-xs text-gray-500">Administrator</p>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 p-6 bg-gray-50">
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

            <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-primary-blue/50">
                <div class="mb-4 flex justify-between items-center border-b pb-4">
                    <h2 class="text-xl font-bold text-gray-800">Daftar Permohonan</h2>
                    <button id="btn-tambah-surat" class="flex items-center py-2 px-4 bg-primary-blue text-white font-semibold rounded-lg shadow-md hover:bg-dark-blue transition duration-200 text-sm">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Surat
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="table-header w-12 text-center">No</th>
                                <th class="table-header">Nama Pemohon</th>
                                <th class="table-header">NIK</th>
                                <th class="table-header w-32">Ditujukan ke</th>
                                <th class="table-header w-36 text-center">Status</th>
                                <th class="table-header w-40 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = ((($requests_data['current_page'] ?? 1) - 1) * ITEMS_PER_PAGE + 1); ?>
                            <?php foreach (($requests_data['requests'] ?? []) as $request): ?>
                            <tr id="row-sp-<?php echo $request['id']; ?>">
                                <td class="table-cell text-center font-medium"><?php echo $no++; ?></td>
                                <td class="table-cell font-medium text-gray-800" data-name="<?php echo htmlspecialchars($request['user_full_name']); ?>">
                                    <?php echo htmlspecialchars($request['user_full_name']); ?>
                                </td>
                                <td class="table-cell"><?php echo htmlspecialchars($request['request_data']['nik'] ?? '-'); ?></td>
                                <td class="table-cell"><?php echo htmlspecialchars($request['request_data']['ditujukan_kepada'] ?? '-'); ?></td>
                                <td class="table-cell text-center">
                                    <select data-id="sp-<?php echo $request['id']; ?>" class="status-select status-<?php echo strtolower($request['status']); ?>">
                                        <option value="pending" <?php echo $request['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="approved" <?php echo $request['status'] === 'approved' ? 'selected' : ''; ?>>Approve</option>
                                        <option value="rejected" <?php echo $request['status'] === 'rejected' ? 'selected' : ''; ?>>Tolak</option>
                                    </select>
                                </td>
                                <td class="table-cell text-center">
                                    <div class="flex justify-center space-x-2">
                                        <button class="action-icon-btn bg-soft-yellow hover:bg-yellow-500 p-2 rounded-lg text-white">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-9-6l9 9m-9-6v3h3"></path>
                                            </svg>
                                        </button>

                                        <button class="action-icon-btn bg-soft-green hover:bg-green-600 p-2 rounded-lg text-white <?php echo $request['status'] !== 'approved' ? 'hidden' : ''; ?>" id="print-sp-<?php echo $request['id']; ?>" onclick="printSurat(<?php echo $request['id']; ?>)">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m0 0v1a2 2 0 002 2h6a2 2 0 002-2v-1M7 17h10"></path>
                                            </svg>
                                        </button>

                                        <form method="POST" action="<?php echo BASE_URL; ?>/admin/requests/<?php echo $request['id']; ?>/delete"
                                              onsubmit="return confirmDelete('<?php echo htmlspecialchars($request['user_full_name']); ?>')"
                                              class="inline">
                                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                            <button type="submit" class="action-icon-btn bg-soft-red hover:bg-red-600 delete-btn p-2 rounded-lg text-white" data-id="sp-<?php echo $request['id']; ?>">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if (($requests_data['pages'] ?? 0) > 1): ?>
                <div class="mt-6 flex justify-center">
                    <div class="flex space-x-2">
                        <?php if (($requests_data['current_page'] ?? 1) > 1): ?>
                        <a href="?page=<?php echo ($requests_data['current_page'] - 1); ?>&status=<?php echo urlencode($status ?? ''); ?>&search=<?php echo urlencode($search ?? ''); ?>"
                           class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm hover:bg-gray-50">Previous</a>
                        <?php endif; ?>

                        <?php
                        $current_page = $requests_data['current_page'] ?? 1;
                        $total_pages = $requests_data['pages'] ?? 1;
                        $start_page = max(1, $current_page - 2);
                        $end_page = min($total_pages, $current_page + 2);

                        for ($i = $start_page; $i <= $end_page; $i++):
                        ?>
                        <a href="?page=<?php echo $i; ?>&status=<?php echo urlencode($status ?? ''); ?>&search=<?php echo urlencode($search ?? ''); ?>"
                           class="px-4 py-2 <?php echo $i === $current_page ? 'bg-primary-blue text-white' : 'bg-white border border-gray-300 hover:bg-gray-50'; ?> rounded-lg text-sm">
                            <?php echo $i; ?>
                        </a>
                        <?php endfor; ?>

                        <?php if (($requests_data['current_page'] ?? 1) < ($requests_data['pages'] ?? 1)): ?>
                        <a href="?page=<?php echo ($requests_data['current_page'] + 1); ?>&status=<?php echo urlencode($status ?? ''); ?>&search=<?php echo urlencode($search ?? ''); ?>"
                           class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm hover:bg-gray-50">Next</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>

<!-- Modal Form Tambah Surat -->
<div id="form-modal" class="modal-overlay hidden">
    <div class="modal-content">
        <div class="p-6 border-b flex justify-between items-center sticky top-0 bg-white rounded-t-xl z-10">
            <h3 class="text-xl font-bold text-gray-800">Tambah Surat Pengantar</h3>
            <button id="close-form-modal" class="text-gray-400 hover:text-gray-700 transition duration-150">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-6">
            <p class="text-sm text-gray-500 mb-4">Silakan isi informasi surat pengantar yang akan dibuat.</p>

            <form id="surat-pengantar-form" action="<?php echo BASE_URL; ?>/admin/requests/create" method="POST" class="space-y-4">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" placeholder="Masukkan Nama Lengkap" class="form-input" required>
                    </div>
                    <div>
                        <label for="nik" class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                        <input type="text" id="nik" name="nik" placeholder="Masukkan Nomor Induk Kependudukan" class="form-input" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="ttl" class="block text-sm font-medium text-gray-700 mb-1">Tempat, Tanggal Lahir</label>
                        <input type="text" id="ttl" name="ttl" placeholder="Contoh: Denpasar, 10/01/1990" class="form-input" required>
                    </div>
                    <div>
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <input type="text" id="alamat" name="alamat" placeholder="Masukkan Alamat Lengkap" class="form-input" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="pekerjaan" class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan</label>
                        <input type="text" id="pekerjaan" name="pekerjaan" placeholder="Masukkan Pekerjaan" class="form-input" required>
                    </div>
                    <div>
                        <label for="agama" class="block text-sm font-medium text-gray-700 mb-1">Agama</label>
                        <input type="text" id="agama" name="agama" placeholder="Masukkan Agama" class="form-input" required>
                    </div>
                </div>

                <hr class="my-4">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="ditujukan_kepada" class="block text-sm font-medium text-gray-700 mb-1">Ditujukan Kepada</label>
                        <input type="text" id="ditujukan_kepada" name="ditujukan_kepada" placeholder="Contoh: Polres Badung / Kantor Imigrasi" class="form-input" required>
                    </div>
                    <div>
                        <label for="keperluan" class="block text-sm font-medium text-gray-700 mb-1">Keperluan Surat</label>
                        <input type="text" id="keperluan" name="keperluan" placeholder="Contoh: Membuat SKCK / Mengurus Paspor" class="form-input" required>
                    </div>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" class="py-2.5 px-6 bg-primary-blue text-white font-semibold rounded-lg shadow-md hover:bg-dark-blue transition duration-200">
                        Simpan Permohonan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Delete Confirmation -->
<div id="delete-modal" class="modal-overlay hidden">
    <div class="modal-content max-w-sm">
        <div class="text-center">
            <svg class="mx-auto w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.3 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mt-3">Konfirmasi Penghapusan</h3>
            <p class="text-sm text-gray-500 mt-2">Anda yakin ingin menghapus permohonan surat ini?</p>
            <p class="text-sm font-bold text-gray-800 mt-1 mb-5" id="modal-item-name">Permohonan #ID Akan Dihapus</p>
        </div>

        <div class="flex justify-between space-x-4">
            <button id="modal-cancel" class="flex-1 py-2 px-4 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition duration-150">
                Batal
            </button>
            <button id="modal-confirm" class="flex-1 py-2 px-4 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition duration-150">
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

<!-- SweetAlert2 for notifications -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>