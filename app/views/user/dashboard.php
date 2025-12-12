<?php
// Set page metadata
$title = 'Dashboard User';
$extra_css = ['user-sidebar.css', 'user-dashboard.css'];
$extra_js = ['user-navigation.js'];
?>

<body class="bg-gray-100 antialiased">
<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo-section">
            <div class="logo-text">
                <i class="fas fa-leaf"></i>
                <span>ABIS</span>
            </div>
            <div class="logo-subtitle">Dashboard Warga</div>
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
            <div class="menu-item active" data-target="dashboard">
                <i class="fas fa-home"></i>
                <span class="menu-text">Beranda</span>
            </div>
            <div class="menu-item" data-target="pengajuan">
                <i class="fas fa-file-alt"></i>
                <span class="menu-text">Buat Surat</span>
            </div>
            <div class="menu-item" data-target="riwayat">
                <i class="fas fa-history"></i>
                <span class="menu-text">Riwayat Surat</span>
            </div>
            <form method="POST" action="<?php echo BASE_URL; ?>/logout" class="inline-block w-full">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <div class="menu-item" id="logout-btn" onclick="this.parentElement.submit()">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="menu-text">Keluar</span>
                </div>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <div>
                <h1 class="text-2xl font-bold text-slate-800" id="page-title">Selamat Datang, <?php echo htmlspecialchars($current_user['full_name']); ?>!</h1>
                <p class="text-slate-600" id="page-date"><?php echo date('l, d F Y', strtotime('today')); ?></p>
            </div>
            <div class="user-info">
                <div class="text-right">
                    <div class="font-semibold"><?php echo htmlspecialchars($current_user['full_name']); ?></div>
                    <div class="text-sm text-slate-500">Warga Desa</div>
                </div>
                <div class="user-avatar"><?php echo strtoupper(substr($current_user['full_name'], 0, 2)); ?></div>
            </div>
        </div>

        <!-- Dashboard Content dengan background biru tipis -->
        <div id="dashboard-content" class="page-content active">
            <!-- Layanan Cepat -->
            <div class="mb-8">
                <h2 class="section-title">
                    <i class="fas fa-bolt text-blue-500"></i>
                    Buat Surat Baru
                </h2>
                <div class="grid-container">
                    <?php foreach ($letter_types as $type): ?>
                    <div class="service-card">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="icon-circle">
                                <i class="fas fa-<?php echo $this->getIconForType($type['code']); ?>"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg"><?php echo htmlspecialchars($type['name']); ?></h3>
                                <p class="text-slate-500 text-sm"><?php echo htmlspecialchars($type['description'] ?? 'Untuk keperluan umum'); ?></p>
                            </div>
                        </div>
                        <button class="btn-primary w-full justify-center" onclick="buatSurat('<?php echo $type['code']; ?>')">
                            <i class="fas fa-plus"></i> Buat Surat
                        </button>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Riwayat Terbaru -->
            <div class="card">
                <h2 class="section-title">
                    <i class="fas fa-history text-blue-500"></i>
                    Riwayat Terbaru
                </h2>
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Jenis Surat</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (($recent_requests ?? []) as $request): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($request['letter_type_name']); ?></td>
                                <td><?php echo date('d M Y', strtotime($request['created_at'])); ?></td>
                                <td><span class="status-badge status-<?php echo $request['status']; ?>">
                                    <?php
                                    switch($request['status']) {
                                        case 'pending': echo 'Proses'; break;
                                        case 'approved': echo 'Selesai'; break;
                                        case 'rejected': echo 'Ditolak'; break;
                                        case 'completed': echo 'Selesai'; break;
                                        default: echo ucfirst($request['status']);
                                    }
                                    ?>
                                </span></td>
                                <td>
                                    <?php if ($request['status'] === 'approved' || $request['status'] === 'completed'): ?>
                                    <button class="btn-primary text-sm py-1 px-3" onclick="downloadSurat(<?php echo $request['id']; ?>)">
                                        <i class="fas fa-download"></i> Unduh
                                    </button>
                                    <?php else: ?>
                                    <button class="btn-primary text-sm py-1 px-3" onclick="viewDetail(<?php echo $request['id']; ?>)">
                                        <i class="fas fa-eye"></i> Lihat
                                    </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Buat Surat Content dengan background putih -->
        <div id="pengajuan-content" class="page-content">
            <div class="card">
                <h2 class="section-title">
                    <i class="fas fa-file-alt text-blue-500"></i>
                    Buat Surat Baru
                </h2>

                <div class="form-group">
                    <label for="jenis-surat">Jenis Surat</label>
                    <select id="jenis-surat" onchange="ubahFormSurat()">
                        <option value="">Pilih Jenis Surat</option>
                        <?php foreach ($letter_types as $type): ?>
                        <option value="<?php echo $type['code']; ?>"><?php echo htmlspecialchars($type['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Dynamic Forms will be loaded here via JavaScript -->
                <div id="surat-forms-container">
                    <!-- Forms will be dynamically generated -->
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="button" class="btn-primary" onclick="kirimPengajuan()">
                        <i class="fas fa-paper-plane"></i> Kirim Pengajuan
                    </button>
                    <button type="button" class="btn-secondary" onclick="showPage('dashboard')">
                        <i class="fas fa-times"></i> Batal
                    </button>
                </div>
            </div>
        </div>

        <!-- Riwayat Surat Content dengan background putih -->
        <div id="riwayat-content" class="page-content">
            <div class="card">
                <h2 class="section-title">
                    <i class="fas fa-history text-blue-500"></i>
                    Riwayat Semua Surat
                </h2>

                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Jenis Surat</th>
                                <th>Tanggal Buat</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach (($all_requests ?? []) as $request): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo htmlspecialchars($request['letter_type_name']); ?></td>
                                <td><?php echo date('d M Y', strtotime($request['created_at'])); ?></td>
                                <td><span class="status-badge status-<?php echo $request['status']; ?>">
                                    <?php
                                    switch($request['status']) {
                                        case 'pending': echo 'Proses'; break;
                                        case 'approved': echo 'Selesai'; break;
                                        case 'rejected': echo 'Ditolak'; break;
                                        case 'completed': echo 'Selesai'; break;
                                        default: echo ucfirst($request['status']);
                                    }
                                    ?>
                                </span></td>
                                <td><?php echo htmlspecialchars($request['admin_notes'] ?? '-'); ?></td>
                                <td>
                                    <?php if ($request['status'] === 'approved' || $request['status'] === 'completed'): ?>
                                    <button class="btn-primary text-sm py-1 px-3" onclick="downloadSurat(<?php echo $request['id']; ?>)">
                                        <i class="fas fa-download"></i> Unduh
                                    </button>
                                    <?php elseif ($request['status'] === 'rejected'): ?>
                                    <button class="btn-primary text-sm py-1 px-3" onclick="buatSurat('<?php echo $request['letter_type_code']; ?>')">
                                        <i class="fas fa-redo"></i> Ulangi
                                    </button>
                                    <?php else: ?>
                                    <button class="btn-primary text-sm py-1 px-3" onclick="viewDetail(<?php echo $request['id']; ?>)">
                                        <i class="fas fa-eye"></i> Lihat
                                    </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Profil Content dengan background putih -->
        <div id="profil-content" class="page-content">
            <div class="card">
                <h2 class="section-title">
                    <i class="fas fa-user-circle text-blue-500"></i>
                    Profil Saya
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="nama">Nama Lengkap</label>
                        <input type="text" id="nama" value="<?php echo htmlspecialchars($current_user['full_name']); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" value="<?php echo htmlspecialchars($current_user['username']); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" value="<?php echo htmlspecialchars($current_user['email']); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="phone">Nomor Telepon</label>
                        <input type="tel" id="phone" value="<?php echo htmlspecialchars($current_user['phone'] ?? ''); ?>" readonly>
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="alamat">Alamat</label>
                        <textarea id="alamat" rows="3" readonly><?php echo htmlspecialchars($current_user['address'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; <?php echo date('Y'); ?> ABIS - Aplikasi Desa Digital. Semua hak dilindungi.</p>
            <p class="mt-2">Sistem Layanan Surat Digital Terintegrasi</p>
        </div>
    </div>
</div>
