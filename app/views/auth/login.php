<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Surat - In<?php echo APP_NAME; ?></title>
    <link href="<?php echo ASSETS_URL; ?>/css/style.css" rel="stylesheet">
    <link href="<?php echo ASSETS_URL; ?>/css/login-portal.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="<?php echo ASSETS_URL; ?>/js/login-portal.js"></script>
</head>

<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <!-- Flash Messages -->
    <?php $flashMessage = getFlashMessage(); ?>
    <?php if ($flashMessage): ?>
        <div class="flash-message flash-<?php echo $flashMessage['type']; ?>" id="flashMessage">
            <div class="flash-content">
                <i class="fas fa-<?php echo $flashMessage['type'] === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
                <span><?php echo $flashMessage['message']; ?></span>
                <button type="button" class="flash-close" onclick="closeFlashMessage()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Portal Container -->
    <div class="main-portal">
        <!-- Left: Elegant Panel -->
        <div class="info-panel">
            <div class="logo-section">
                <div class="logo">
                    <div class="logo-image">
                        <img src="<?php echo BASE_URL; ?>/templates/logo.png" alt="Logo Desa" style="width: 80px; height: auto;">
                    </div>
                    <div class="logo-text">Desa Digital</div>
                </div>
                <div class="tagline">
                    Portal Layanan Digital Desa Kleteran
                </div>
            </div>
            
            <!-- Description/Quote Area -->
            <div class="description-area">
                <div class="quote">
                    Membangun Desa Digital untuk Pelayanan yang Lebih Baik
                </div>
                
                <div class="decoration-line"></div>
                
                <div class="highlight-text">
                    Akses layanan administrasi desa secara online, 
                    kapan saja dan di mana saja. Pengalaman yang lebih 
                    cepat, mudah, dan efisien untuk semua warga.
                </div>
            </div>
        </div>
        
        <!-- Right: Forms Panel -->
        <div class="forms-panel">
            <!-- Tabs Navigation -->
            <div class="tabs-nav">
                <button class="tab-btn active" data-tab="login">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Masuk ke Akun</span>
                </button>
                <button class="tab-btn" data-tab="register">
                    <i class="fas fa-user-plus"></i>
                    <span>Daftar Akun Baru</span>
                </button>
            </div>
            
            <!-- Forms Container (Scrollable) -->
            <div class="forms-container">
                <!-- Login Form -->
                <div class="form-section active" id="login-form">
                    <div class="form-header">
                        <h1 class="form-title">Selamat Datang Kembali</h1>
                        <p class="form-subtitle">
                            Masukkan kredensial Anda untuk mengakses seluruh layanan digital desa
                        </p>
                    </div>
                    
                    <form id="loginForm" action="<?php echo BASE_URL; ?>/login" method="POST">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-user"></i>
                                NIK / Username
                            </label>
                            <input type="text" class="form-input" id="login-username" name="username"
                                   placeholder="Masukkan NIK Anda" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-lock"></i>
                                Password
                            </label>
                            <div class="password-wrapper">
                                <input type="password" class="form-input" id="login-password" name="password"
                                       placeholder="Masukkan password Anda">
                                <span class="password-toggle" onclick="togglePassword('login-password')">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                        </div>
                        
                        <div class="form-options">
                            <div class="remember-group">
                                <div class="checkbox-custom checked" id="rememberCheckbox"></div>
                                <label class="checkbox-label">Ingat saya di perangkat ini</label>
                            </div>
                            <a href="#" class="forgot-link">
                                <i class="fas fa-key"></i>
                                Lupa Password?
                            </a>
                        </div>
                        
                        <button type="submit" class="submit-btn">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Masuk ke Portal</span>
                        </button>
                    </form>
                    
                    <div class="switch-link">
                        Belum memiliki akun? 
                        <a onclick="switchTab('register')">
                            Daftar sekarang
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Register Form -->
                <div class="form-section" id="register-form">
                    <div class="form-header">
                        <h1 class="form-title">Bergabung Bersama Kami</h1>
                        <p class="form-subtitle">
                            Daftarkan diri Anda untuk mengakses seluruh layanan digital desa
                        </p>
                    </div>
                    
                    <form id="registerForm" action="<?php echo BASE_URL; ?>/register" method="POST">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-id-card"></i>
                                NIK
                            </label>
                            <input type="text" class="form-input" id="reg-nik" name="nik"
                                   placeholder="16 digit NIK" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-user"></i>
                                Nama Lengkap
                            </label>
                            <input type="text" class="form-input" id="reg-nama" name="nama"
                                   placeholder="Nama lengkap sesuai KTP" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-envelope"></i>
                                Email
                            </label>
                            <input type="email" class="form-input" id="reg-email" name="email"
                                   placeholder="email@contoh.com" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-phone"></i>
                                Nomor HP
                            </label>
                            <input type="tel" class="form-input" id="reg-phone" name="phone"
                                   placeholder="08xx xxxx xxxx" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-lock"></i>
                                Password
                            </label>
                            <div class="password-wrapper">
                                <input type="password" class="form-input" id="reg-password" name="password"
                                       placeholder="Minimal 8 karakter" required>
                                <span class="password-toggle" onclick="togglePassword('reg-password')">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-lock"></i>
                                Konfirmasi Password
                            </label>
                            <div class="password-wrapper">
                                <input type="password" class="form-input" id="reg-confirm-password" name="password_confirmation"
                                       placeholder="Ulangi password" required>
                                <span class="password-toggle" onclick="togglePassword('reg-confirm-password')">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                        </div>
                        
                        <div class="form-options">
                            <div class="remember-group">
                                <div class="checkbox-custom" id="agreeCheckbox"></div>
                                <label class="checkbox-label">
                                    Saya menyetujui syarat dan ketentuan
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="submit-btn">
                            <i class="fas fa-user-plus"></i>
                            <span>Buat Akun Sekarang</span>
                        </button>
                    </form>
                    
                    <div class="switch-link">
                        Sudah memiliki akun? 
                        <a onclick="switchTab('login')">
                            Login disini
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>