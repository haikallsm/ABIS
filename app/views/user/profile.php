<?php
// Set page title
$title = 'Profile Saya';
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

                    <a href="<?php echo BASE_URL; ?>/profile" class="sidebar-link active flex items-center py-4">
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
                                    <h1 class="text-2xl font-bold mb-2">Profile Saya</h1>
                                    <p class="text-lg opacity-95 mb-1">Kelola informasi identitas Anda</p>
                                </div>

                        <div class="text-right">
                            <div class="profile-box inline-flex items-center space-x-4">
                                <div class="w-12 h-12 profile-avatar rounded-full flex items-center justify-center text-lg">
                                    <span class="font-bold text-white"><?php echo strtoupper(substr($user['full_name'] ?? 'U', 0, 1)); ?></span>
                                </div>
                                <div class="text-white">
                                    <p class="font-bold text-lg"><?php echo htmlspecialchars($user['full_name'] ?? ''); ?></p>
                                    <p class="text-xs opacity-90 mt-1"><?php echo htmlspecialchars($user['username'] ?? ''); ?></p>
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
                <!-- Flash Messages -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <!-- Profile Form -->
                <div class="max-w-4xl mx-auto">
                    <!-- WELCOME CARD -->
                    <div class="p-6 card-bg-white rounded-xl card-hover-effect no-move mb-6 border-2 border-gray-200">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-xl font-bold text-gray-800 mb-2">Informasi Identitas Diri</h2>
                                <p class="text-gray-600 text-sm">Data ini akan digunakan untuk membuat berbagai jenis surat</p>
                            </div>
                            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- FORM CARD -->
                    <div class="p-6 card-bg-white rounded-xl card-hover-effect no-move">

                        <!-- Form Content -->
                        <form method="POST" action="<?php echo BASE_URL; ?>/profile">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

                            <!-- Personal Information -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="nik" class="block text-sm font-medium text-gray-700 mb-2">
                                        NIK <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="nik" name="nik" required
                                           value="<?php echo htmlspecialchars($user['nik'] ?? ''); ?>"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
                                           placeholder="Masukkan 16 digit NIK">
                                </div>

                                <div>
                                    <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nama Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="full_name" name="full_name" required
                                           value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
                                           placeholder="Masukkan nama lengkap sesuai KTP">
                                </div>

                                <div>
                                    <label for="birth_place" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tempat Lahir
                                    </label>
                                    <input type="text" id="birth_place" name="birth_place"
                                           value="<?php echo htmlspecialchars($user['birth_place'] ?? ''); ?>"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
                                           placeholder="Contoh: Denpasar">
                                </div>

                                <div>
                                    <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tanggal Lahir
                                    </label>
                                    <input type="date" id="birth_date" name="birth_date"
                                           value="<?php echo htmlspecialchars($user['birth_date'] ?? ''); ?>"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors">
                                </div>

                                <div>
                                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">
                                        Jenis Kelamin
                                    </label>
                                    <select id="gender" name="gender"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki" <?php echo ($user['gender'] ?? '') === 'Laki-laki' ? 'selected' : ''; ?>>Laki-laki</option>
                                        <option value="Perempuan" <?php echo ($user['gender'] ?? '') === 'Perempuan' ? 'selected' : ''; ?>>Perempuan</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="religion" class="block text-sm font-medium text-gray-700 mb-2">
                                        Agama
                                    </label>
                                    <select id="religion" name="religion"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors">
                                        <option value="">Pilih Agama</option>
                                        <option value="Islam" <?php echo ($user['religion'] ?? '') === 'Islam' ? 'selected' : ''; ?>>Islam</option>
                                        <option value="Kristen" <?php echo ($user['religion'] ?? '') === 'Kristen' ? 'selected' : ''; ?>>Kristen</option>
                                        <option value="Katolik" <?php echo ($user['religion'] ?? '') === 'Katolik' ? 'selected' : ''; ?>>Katolik</option>
                                        <option value="Hindu" <?php echo ($user['religion'] ?? '') === 'Hindu' ? 'selected' : ''; ?>>Hindu</option>
                                        <option value="Buddha" <?php echo ($user['religion'] ?? '') === 'Buddha' ? 'selected' : ''; ?>>Buddha</option>
                                        <option value="Konghucu" <?php echo ($user['religion'] ?? '') === 'Konghucu' ? 'selected' : ''; ?>>Konghucu</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="space-y-6">
                                <div>
                                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                        Alamat Lengkap
                                    </label>
                                    <textarea id="address" name="address" rows="3"
                                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors resize-none"
                                              placeholder="Masukkan alamat lengkap sesuai KTP"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                            Nomor HP
                                        </label>
                                        <input type="tel" id="phone" name="phone"
                                               value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
                                               placeholder="Contoh: 081234567890">
                                    </div>

                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                            Email
                                        </label>
                                        <input type="email" id="email" name="email"
                                               value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
                                               placeholder="Contoh: nama@email.com">
                                    </div>

                                    <div>
                                        <label for="occupation" class="block text-sm font-medium text-gray-700 mb-2">
                                            Pekerjaan
                                        </label>
                                        <input type="text" id="occupation" name="occupation"
                                               value="<?php echo htmlspecialchars($user['occupation'] ?? ''); ?>"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
                                               placeholder="Contoh: Pegawai Swasta, Pelajar, dll">
                                    </div>

                                    <div>
                                        <label for="marital_status" class="block text-sm font-medium text-gray-700 mb-2">
                                            Status Perkawinan
                                        </label>
                                        <select id="marital_status" name="marital_status"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors">
                                            <option value="">Pilih Status</option>
                                            <option value="Belum Kawin" <?php echo ($user['marital_status'] ?? '') === 'Belum Kawin' ? 'selected' : ''; ?>>Belum Kawin</option>
                                            <option value="Kawin" <?php echo ($user['marital_status'] ?? '') === 'Kawin' ? 'selected' : ''; ?>>Kawin</option>
                                            <option value="Cerai Hidup" <?php echo ($user['marital_status'] ?? '') === 'Cerai Hidup' ? 'selected' : ''; ?>>Cerai Hidup</option>
                                            <option value="Cerai Mati" <?php echo ($user['marital_status'] ?? '') === 'Cerai Mati' ? 'selected' : ''; ?>>Cerai Mati</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end pt-6 border-t border-gray-200">
                                <button type="submit" class="px-6 py-3 bg-primary text-white font-semibold rounded-lg hover:bg-primary-dark focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-all duration-300 flex items-center gap-2 transform hover:-translate-y-0.5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- INFORMATION CARD -->
                    <div class="p-6 card-bg-white rounded-xl card-hover-effect no-move mt-6 border-2 border-gray-200">
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-gray-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h3 class="text-gray-800 font-semibold mb-2">Informasi Penting</h3>
                                <ul class="text-gray-700 text-sm space-y-1">
                                    <li>• Pastikan NIK dan nama lengkap sesuai dengan KTP</li>
                                    <li>• Data ini akan digunakan untuk membuat berbagai jenis surat</li>
                                    <li>• Lengkapi semua informasi untuk kemudahan proses pembuatan surat</li>
                                    <li>• Data yang telah disimpan dapat diperbarui kapan saja</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

</div>

    <script>
        // Toggle sidebar collapsed state
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

            // NIK validation
            const nikInput = document.getElementById('nik');
            if (nikInput) {
                nikInput.addEventListener('input', function(e) {
                    // Only allow numbers and limit to 16 digits
                    this.value = this.value.replace(/\D/g, '').substring(0, 16);
                });
            }
        });
    </script>
</body>
