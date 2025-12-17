<?php
// Set page metadata
$title = 'Detail Surat - ' . APP_NAME;
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

                    <a href="<?php echo BASE_URL; ?>/dashboard" class="sidebar-link flex items-center py-4">
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

                    <a href="<?php echo BASE_URL; ?>/requests" class="sidebar-link active flex items-center py-4">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Riwayat Surat</span>
                    </a>

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

    <!-- SCROLLABLE CONTENT SECTION -->
    <div class="scroll-container">
        <main class="px-6 pb-6">
            <div class="max-w-6xl mx-auto">
                <div class="cream-card p-8">
                    <!-- Header -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h2 class="text-3xl font-bold text-dark mb-2">Detail Surat</h2>
                                <p class="text-gray-600">Permohonan #<?php echo htmlspecialchars($request['id']); ?></p>
                            </div>
                            <div class="flex space-x-3">
                                <a href="<?php echo BASE_URL; ?>/requests" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                                    ← Kembali
                                </a>
                                <?php if ($request['status'] === 'approved' || $request['status'] === 'completed'): ?>
                                <a href="<?php echo BASE_URL; ?>/requests/<?php echo $request['id']; ?>/preview"
                                   target="_blank"
                                   class="px-4 py-2 bg-info text-white rounded-lg hover:bg-info-light transition-colors">
                                    👁️ Preview PDF
                                </a>
                                <button onclick="downloadRequest(<?php echo $request['id']; ?>)"
                                        class="px-4 py-2 bg-secondary text-white rounded-lg hover:bg-secondary-light transition-colors">
                                    ⬇️ Download PDF
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <div class="flex items-center space-x-4 mb-6">
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                <?php
                                switch($request['status']) {
                                    case 'pending':
                                        echo 'bg-yellow-100 text-yellow-800';
                                        break;
                                    case 'approved':
                                        echo 'bg-green-100 text-green-800';
                                        break;
                                    case 'rejected':
                                        echo 'bg-red-100 text-red-800';
                                        break;
                                    case 'completed':
                                        echo 'bg-blue-100 text-blue-800';
                                        break;
                                    default:
                                        echo 'bg-gray-100 text-gray-800';
                                }
                                ?>">
                                <?php
                                switch($request['status']) {
                                    case 'pending':
                                        echo '⏳ Menunggu Persetujuan';
                                        break;
                                    case 'approved':
                                        echo '✅ Disetujui';
                                        break;
                                    case 'rejected':
                                        echo '❌ Ditolak';
                                        break;
                                    case 'completed':
                                        echo '🎉 Selesai';
                                        break;
                                    default:
                                        echo htmlspecialchars($request['status']);
                                }
                                ?>
                            </span>
                            <span class="text-sm text-gray-500">
                                Dibuat: <?php echo date('d M Y H:i', strtotime($request['created_at'])); ?>
                            </span>
                        </div>
                    </div>

                    <!-- PDF Preview Section -->
                    <?php if ($request['status'] === 'approved' || $request['status'] === 'completed'): ?>
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-dark mb-4">Preview Surat</h3>
                        <div class="bg-gray-50 border rounded-lg p-4">
                            <div class="text-center">
                                <?php if (!empty($request['generated_file'])): ?>
                                    <!-- PDF Preview menggunakan iframe -->
                                    <iframe src="<?php echo BASE_URL; ?>/requests/<?php echo $request['id']; ?>/preview"
                                            width="100%"
                                            height="600px"
                                            style="border: 1px solid #ddd; border-radius: 8px;"
                                            title="Preview Surat PDF">
                                        <p>Browser Anda tidak mendukung preview PDF.
                                           <a href="<?php echo BASE_URL; ?>/requests/<?php echo $request['id']; ?>/download"
                                              class="text-primary hover:underline">Download PDF</a>
                                        </p>
                                    </iframe>

                                    <div class="mt-4 text-sm text-gray-600">
                                        📄 Preview PDF - <?php echo htmlspecialchars(basename($request['generated_file'])); ?>
                                    </div>
                                <?php else: ?>
                                    <div class="py-12 text-gray-500">
                                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-lg font-medium">PDF belum tersedia</p>
                                        <p class="text-sm">Surat sedang dalam proses pembuatan</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Request Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Left Column -->
                        <div>
                            <h3 class="text-xl font-semibold text-dark mb-4">Informasi Permohonan</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">ID Permohonan</label>
                                    <p class="text-dark font-mono">#<?php echo htmlspecialchars($request['id']); ?></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Jenis Surat</label>
                                    <p class="text-dark"><?php echo htmlspecialchars($request['letter_type_name'] ?? 'Unknown'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tanggal Dibuat</label>
                                    <p class="text-dark"><?php echo date('d M Y H:i', strtotime($request['created_at'])); ?></p>
                                </div>
                                <?php if (!empty($request['updated_at'])): ?>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Terakhir Diupdate</label>
                                    <p class="text-dark"><?php echo date('d M Y H:i', strtotime($request['updated_at'])); ?></p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div>
                            <h3 class="text-xl font-semibold text-dark mb-4">Status & File</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Status</label>
                                    <span class="px-3 py-1 rounded-full text-sm font-medium
                                        <?php
                                        switch($request['status']) {
                                            case 'pending':
                                                echo 'bg-yellow-100 text-yellow-800';
                                                break;
                                            case 'approved':
                                                echo 'bg-green-100 text-green-800';
                                                break;
                                            case 'rejected':
                                                echo 'bg-red-100 text-red-800';
                                                break;
                                            case 'completed':
                                                echo 'bg-blue-100 text-blue-800';
                                                break;
                                            default:
                                                echo 'bg-gray-100 text-gray-800';
                                        }
                                        ?>">
                                        <?php
                                        switch($request['status']) {
                                            case 'pending':
                                                echo '⏳ Menunggu Persetujuan';
                                                break;
                                            case 'approved':
                                                echo '✅ Disetujui';
                                                break;
                                            case 'rejected':
                                                echo '❌ Ditolak';
                                                break;
                                            case 'completed':
                                                echo '🎉 Selesai';
                                                break;
                                            default:
                                                echo htmlspecialchars($request['status']);
                                        }
                                        ?>
                                    </span>
                                </div>

                                <?php if (!empty($request['generated_file'])): ?>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">File PDF</label>
                                    <p class="text-dark font-mono text-sm"><?php echo htmlspecialchars(basename($request['generated_file'])); ?></p>
                                </div>
                                <?php endif; ?>

                                <?php if (!empty($request['admin_notes'])): ?>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Catatan Admin</label>
                                    <p class="text-dark bg-gray-50 p-3 rounded-lg"><?php echo nl2br(htmlspecialchars($request['admin_notes'])); ?></p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Request Data (if available) -->
                    <?php
                    $requestData = json_decode($request['request_data'], true);
                    if (!empty($requestData)):
                    ?>
                    <div class="mt-8">
                        <h3 class="text-xl font-semibold text-dark mb-4">Data Permohonan</h3>
                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <?php foreach ($requestData as $key => $value): ?>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 capitalize">
                                        <?php echo str_replace(['_', '-'], ' ', $key); ?>
                                    </label>
                                    <p class="text-dark"><?php echo htmlspecialchars($value); ?></p>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>
