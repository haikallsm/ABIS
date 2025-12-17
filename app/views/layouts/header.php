<!-- HEADER -->
<header class="official-header text-gray-800 sticky top-0 z-50">
    <div class="container mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-linear-to-br from-primary to-dark rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-file-contract text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-natural">Surat - In</h1>
                    <p class="text-natural-light text-sm">Aplikasi Desa Digital - Pemerintah Desa</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <?php if (isLoggedIn()): ?>
                    <div class="text-right">
                        <div class="text-natural-light text-sm">Selamat datang,</div>
                        <div class="text-natural font-semibold"><?php echo htmlspecialchars(getCurrentUser()['full_name']); ?></div>
                    </div>
                    <div class="flex space-x-2">
                        <?php if (isAdmin()): ?>
                            <a href="<?php echo BASE_URL; ?>/admin/dashboard" class="btn-secondary px-4 py-2 rounded-lg text-sm">
                                <i class="fas fa-cog mr-2"></i>Admin
                            </a>
                        <?php else: ?>
                            <a href="<?php echo BASE_URL; ?>/dashboard" class="btn-secondary px-4 py-2 rounded-lg text-sm">
                                <i class="fas fa-user mr-2"></i>Dashboard
                            </a>
                        <?php endif; ?>
                        <form method="POST" action="<?php echo BASE_URL; ?>/logout" class="inline">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            <button type="submit" class="btn-danger px-4 py-2 rounded-lg text-sm">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="flex space-x-2">
                        <a href="<?php echo BASE_URL; ?>/login" class="btn-secondary px-4 py-2 rounded-lg text-sm">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                        </a>
                        <a href="<?php echo BASE_URL; ?>/register" class="btn-primary px-4 py-2 rounded-lg text-sm">
                            <i class="fas fa-user-plus mr-2"></i>Register
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>
