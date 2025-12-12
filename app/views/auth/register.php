<?php
// Set page metadata
$title = 'Register';
$extra_css = ['auth.css'];
?>

<div class="min-h-screen flex items-center justify-center bg-linear-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="w-20 h-20 mx-auto bg-linear-to-br from-primary to-dark rounded-2xl flex items-center justify-center shadow-xl">
                <i class="fas fa-user-plus text-white text-3xl"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-bold text-gray-900">
                Daftar Akun Baru
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Bergabung dengan ABIS untuk layanan surat digital
            </p>
        </div>

        <!-- Display error messages -->
        <?php if (isset($error)): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Display success messages -->
        <?php if (isset($success)): ?>
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <?php echo htmlspecialchars($success); ?>
                </div>
            </div>
        <?php endif; ?>

        <form class="mt-8 space-y-6" method="POST" action="<?php echo BASE_URL; ?>/register" data-validate>
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

            <div class="space-y-4">
                <div>
                    <label for="full_name" class="label">Nama Lengkap</label>
                    <input id="full_name" name="full_name" type="text" required
                           class="input-field"
                           placeholder="Masukkan nama lengkap Anda"
                           value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>">
                </div>

                <div>
                    <label for="username" class="label">Username</label>
                    <input id="username" name="username" type="text" required
                           class="input-field"
                           placeholder="Masukkan username Anda"
                           value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                    <p class="text-xs text-gray-500 mt-1">Username akan digunakan untuk login</p>
                </div>

                <div>
                    <label for="email" class="label">Email</label>
                    <input id="email" name="email" type="email" required
                           class="input-field"
                           placeholder="Masukkan email Anda"
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                </div>

                <div>
                    <label for="phone" class="label">Nomor Telepon</label>
                    <input id="phone" name="phone" type="tel"
                           class="input-field"
                           placeholder="Masukkan nomor telepon Anda"
                           value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                </div>

                <div>
                    <label for="password" class="label">Password</label>
                    <input id="password" name="password" type="password" required
                           class="input-field"
                           placeholder="Masukkan password Anda"
                           minlength="8">
                    <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                </div>

                <div>
                    <label for="password_confirm" class="label">Konfirmasi Password</label>
                    <input id="password_confirm" name="password_confirm" type="password" required
                           class="input-field"
                           placeholder="Konfirmasi password Anda">
                </div>
            </div>

            <div>
                <button type="submit" class="btn-primary w-full flex justify-center py-3 px-4">
                    <i class="fas fa-user-plus mr-2"></i>
                    Daftar Akun
                </button>
            </div>

            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Sudah punya akun?
                    <a href="<?php echo BASE_URL; ?>/login" class="font-medium text-primary hover:text-primary-600">
                        Masuk sekarang
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>
