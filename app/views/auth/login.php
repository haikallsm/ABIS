<?php
// Set page metadata
$title = 'Login';
$extra_css = ['auth.css'];
?>

<div class="min-h-screen flex items-center justify-center bg-linear-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="w-20 h-20 mx-auto bg-linear-to-br from-primary to-dark rounded-2xl flex items-center justify-center shadow-xl">
                <i class="fas fa-file-contract text-white text-3xl"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-bold text-gray-900">
                Masuk ke ABIS
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Sistem Layanan Surat Digital Terintegrasi
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

        <form class="mt-8 space-y-6" method="POST" action="<?php echo BASE_URL; ?>/login" data-validate>
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

            <div class="space-y-4">
                <div>
                    <label for="username" class="label">Username</label>
                    <input id="username" name="username" type="text" required
                           class="input-field"
                           placeholder="Masukkan username Anda"
                           value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                </div>

                <div>
                    <label for="password" class="label">Password</label>
                    <input id="password" name="password" type="password" required
                           class="input-field"
                           placeholder="Masukkan password Anda">
                </div>
            </div>

            <div>
                <button type="submit" class="btn-primary w-full flex justify-center py-3 px-4">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Masuk
                </button>
            </div>

            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Belum punya akun?
                    <a href="<?php echo BASE_URL; ?>/register" class="font-medium text-primary hover:text-primary-600">
                        Daftar sekarang
                    </a>
                </p>
            </div>
        </form>

        <!-- Demo credentials for testing -->
        <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg">
            <div class="flex">
                <i class="fas fa-info-circle text-yellow-400 mt-0.5 mr-2"></i>
                <div class="text-sm text-yellow-700">
                    <p class="font-medium mb-1">Demo Credentials:</p>
                    <p>Admin: username: <code>admin</code>, password: <code>admin123</code></p>
                    <p>User: Daftar akun baru untuk testing</p>
                </div>
            </div>
        </div>
    </div>
</div>
