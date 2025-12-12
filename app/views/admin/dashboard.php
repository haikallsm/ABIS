<?php
// Set page metadata
$title = 'Dashboard Admin';
$extra_css = ['sidebar.css', 'admin-dashboard.css'];
$extra_js = ['sidebar.js'];
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

                <a href="<?php echo BASE_URL; ?>/admin/dashboard" class="sidebar-link active flex items-center py-3 hover:bg-blue-50">
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

                <a href="<?php echo BASE_URL; ?>/admin/requests" class="sidebar-link flex items-center py-3 hover:bg-blue-50">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v4a1 1 0 001 1h6a1 1 0 001-1V7m0 0a2 2 0 00-2-2H9a2 2 0 00-2 2m0 0v11a2 2 0 002 2h4a2 2 0 002-2V7m-4-2H8"></path>
                    </svg>
                    <span>Export Data</span>
                </a>

                <p class="text-xs text-gray-500 uppercase tracking-wider px-6 pt-4 pb-2">Surat & Dokumen</p>

                <a href="<?php echo BASE_URL; ?>/admin/letter-types" class="sidebar-link flex items-center py-3 hover:bg-blue-50">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Jenis Surat</span>
                </a>

                <a href="<?php echo BASE_URL; ?>/admin/requests" class="sidebar-link flex items-center py-3 hover:bg-blue-50">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2m-6 0h6"></path>
                    </svg>
                    <span>Pengajuan Surat</span>
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
                        <button type="submit" class="sidebar-link flex items-center py-3 text-red-500 hover:bg-blue-50 w-full text-left">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span>Logout</span>
                        </button>
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
                <h1 class="text-2xl font-semibold">Selamat Datang, <?php echo htmlspecialchars(getCurrentUser()['full_name']); ?>!</h1>
                <p class="text-sm text-gray-500"><?php echo date('l, d F Y', strtotime('today')); ?></p>
            </div>

            <div class="flex items-center space-x-3">
                <div class="text-right">
                    <p class="text-sm font-semibold text-gray-800"><?php echo htmlspecialchars(getCurrentUser()['full_name']); ?></p>
                    <p class="text-xs text-gray-500">Administrator</p>
                </div>
                <span class="w-10 h-10 rounded-full bg-blue-500 text-white font-bold flex items-center justify-center">
                    <?php echo strtoupper(substr(getCurrentUser()['full_name'], 0, 1)); ?>
                </span>
            </div>
        </header>

        <!-- Main Dashboard Content -->
        <main class="flex-1 p-6 bg-gray-50">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">Ringkasan Sistem</h2>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <div class="bg-linear-to-r from-primary-blue to-blue-500 p-6 rounded-xl shadow-lg text-white flex justify-between items-center transition duration-300 transform hover:scale-[1.03] cursor-pointer hover:shadow-primary-blue/50 hover:shadow-2xl">
                    <div>
                        <p class="text-sm opacity-80">Jumlah Pengguna</p>
                        <p class="text-4xl font-extrabold mt-1"><?php echo $stats['users']['total'] ?? 0; ?></p>
                    </div>
                    <svg class="w-10 h-10 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20h-5v-2a3 3 0 00-5.356-1.857M9 20v-2a3 3 0 015.548-1.077M10 11a2 2 0 100-4 2 2 0 000 4zm7 0a2 2 0 100-4 2 2 0 000 4zM10 17a5 5 0 008.274 2.87M15 17a5 5 0 008.274 2.87"></path>
                    </svg>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-gray-200 transition duration-300 transform hover:scale-[1.03] cursor-pointer hover:shadow-gray-400/50 hover:shadow-2xl flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">Jumlah Dokumen</p>
                        <p class="text-4xl font-bold mt-1"><?php echo $stats['requests']['total'] ?? 0; ?></p>
                    </div>
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-green-500 transition duration-300 transform hover:scale-[1.03] cursor-pointer hover:shadow-green-500/50 hover:shadow-2xl flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">Dokumen Disetujui</p>
                        <p class="text-4xl font-bold mt-1"><?php echo $stats['requests']['approved'] ?? 0; ?></p>
                    </div>
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-yellow-500 transition duration-300 transform hover:scale-[1.03] cursor-pointer hover:shadow-yellow-500/50 hover:shadow-2xl flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">Permintaan Hari Ini</p>
                        <p class="text-4xl font-bold mt-1"><?php echo $stats['requests']['pending_today'] ?? 0; ?></p>
                    </div>
                    <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-primary-blue transition duration-300 transform hover:scale-[1.03] cursor-pointer hover:shadow-primary-blue/50 hover:shadow-2xl flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">Disetujui Hari Ini</p>
                        <p class="text-4xl font-bold mt-1"><?php echo $stats['requests']['approved_today'] ?? 0; ?></p>
                    </div>
                    <svg class="w-8 h-8 text-primary-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.765a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.265 21H11a2 2 0 01-2-2v-6a2 2 0 00-2-2 2 2 0 01-2-2V7a2 2 0 012-2h6.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2h-4.586a1 1 0 01-.707-.293l-5.414-5.414a1 1 0 01-.293-.707V19a2 2 0 012-2h4.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2h-4.586a1 1 0 01-.707-.293zM7 7h.01"></path>
                    </svg>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-gray-200 transition duration-300 transform hover:scale-[1.03] cursor-pointer hover:shadow-gray-400/50 hover:shadow-2xl flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">Total Surat Bulan Ini</p>
                        <p class="text-4xl font-bold mt-1"><?php echo $stats['requests']['this_month'] ?? 0; ?></p>
                    </div>
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Recent Requests Table -->
                <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-md">
                    <div class="flex justify-between items-center mb-4 border-b pb-4">
                        <h2 class="text-xl font-bold text-gray-800">Permintaan Surat Terbaru</h2>
                        <a href="<?php echo BASE_URL; ?>/admin/requests" class="text-sm font-semibold text-primary-blue hover:text-blue-800 transition duration-150">Lihat Semua</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full leading-normal">
                            <thead>
                                <tr class="text-left text-xs font-semibold uppercase tracking-wider text-gray-600 bg-gray-50">
                                    <th class="px-5 py-3 border-b-2 border-gray-200">PEMOHON</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200">JENIS SURAT</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200">STATUS</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200">TANGGAL</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (($recent_requests ?? []) as $request): ?>
                                <tr>
                                    <td class="px-5 py-4 border-b border-gray-100 text-sm font-medium">
                                        <div class="flex items-center">
                                            <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 font-bold flex items-center justify-center mr-3">
                                                <?php echo strtoupper(substr($request['user_full_name'], 0, 1)); ?>
                                            </span>
                                            <div>
                                                <p><?php echo htmlspecialchars($request['user_full_name']); ?></p>
                                                <p class="text-xs text-gray-500"><?php echo htmlspecialchars($request['user_email']); ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 border-b border-gray-100 text-sm"><?php echo htmlspecialchars($request['letter_type_name']); ?></td>
                                    <td class="px-5 py-4 border-b border-gray-100 text-sm">
                                        <span class="relative inline-block px-3 py-1 font-semibold text-<?php
                                            echo $request['status'] === 'approved' ? 'green' :
                                                 ($request['status'] === 'pending' ? 'orange' : 'primary-blue'); ?>-800 leading-tight">
                                            <span aria-hidden="true" class="absolute inset-0 bg-<?php
                                                echo $request['status'] === 'approved' ? 'green' :
                                                     ($request['status'] === 'pending' ? 'orange' : 'blue'); ?>-200 opacity-75 rounded-full"></span>
                                            <span class="relative"><?php
                                                echo $request['status'] === 'approved' ? 'Disetujui' :
                                                     ($request['status'] === 'pending' ? 'Menunggu' :
                                                      ($request['status'] === 'rejected' ? 'Ditolak' : 'Selesai')); ?></span>
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 border-b border-gray-100 text-sm text-gray-500">
                                        <?php echo date('d M Y', strtotime($request['created_at'])); ?>
                                    </td>
                                    <td class="px-5 py-4 border-b border-gray-100 text-sm flex items-center space-x-2">
                                        <button title="Lihat" class="text-green-500 hover:text-green-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                        <?php if ($request['status'] === 'approved' || $request['status'] === 'completed'): ?>
                                        <button title="Download" class="text-blue-500 hover:text-blue-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                        </button>
                                        <?php endif; ?>
                                        <?php if ($request['status'] === 'pending'): ?>
                                        <button title="Tolak" class="text-red-500 hover:text-red-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                        <button title="Proses" class="text-green-500 hover:text-green-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Sidebar Content -->
                <div class="space-y-6">
                    <!-- Recent Activity -->
                    <div class="bg-white p-6 rounded-xl shadow-md">
                        <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-3">Aktivitas Terbaru</h3>
                        <ul class="space-y-4">
                            <?php foreach (($recent_activities ?? []) as $activity): ?>
                            <li class="flex items-start">
                                <div class="w-8 h-8 rounded-full bg-<?php echo $activity['color']; ?>-100 text-<?php echo $activity['color']; ?>-600 flex items-center justify-center shrink-0 mr-3 mt-1">
                                    <i class="fas <?php echo $activity['icon']; ?>"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium"><?php echo htmlspecialchars($activity['title']); ?></p>
                                    <p class="text-xs text-gray-500"><?php echo htmlspecialchars($activity['time']); ?></p>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Monthly Statistics -->
                    <div class="bg-white p-6 rounded-xl shadow-md">
                        <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-3">Statistik Bulan Ini</h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Surat Diterbitkan:</span>
                                <span class="font-semibold text-primary-blue"><?php echo $stats['requests']['this_month'] ?? 0; ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Surat Menunggu:</span>
                                <span class="font-semibold text-yellow-600"><?php echo $stats['requests']['pending'] ?? 0; ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Pengguna Aktif:</span>
                                <span class="font-semibold text-green-600"><?php echo $stats['users']['active'] ?? 0; ?></span>
                            </div>
                            <div class="border-t pt-3 mt-3 flex justify-between">
                                <span class="text-gray-600 font-bold">Rata-rata Waktu Proses:</span>
                                <span class="font-bold text-primary-blue"><?php echo $stats['avg_process_time'] ?? '2.3'; ?> hari</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
