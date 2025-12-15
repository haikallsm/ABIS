// Login Portal JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching
    document.querySelectorAll('.tab-btn').forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active from all tabs
            document.querySelectorAll('.tab-btn').forEach(t => {
                t.classList.remove('active');
            });

            // Add active to clicked tab
            this.classList.add('active');

            // Switch form
            const tabId = this.getAttribute('data-tab');
            switchForm(tabId);
        });
    });

    // Checkbox functionality
    const rememberCheckbox = document.getElementById('rememberCheckbox');
    const agreeCheckbox = document.getElementById('agreeCheckbox');

    if (rememberCheckbox) {
        rememberCheckbox.addEventListener('click', function() {
            this.classList.toggle('checked');
        });
    }

    if (agreeCheckbox) {
        agreeCheckbox.addEventListener('click', function() {
            this.classList.toggle('checked');
        });
    }

    // Enter key support - removed since we have proper form submit handlers

    // Auto focus
    const loginUsername = document.getElementById('login-username');
    if (loginUsername) {
        loginUsername.focus();
    }

    // Form submit event handlers
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            if (!validateAuthForm('loginForm')) {
                e.preventDefault();
                return false;
            }

            // Show loading before form submits
            showLoading();
            showNotification('Memproses login...', 'info');
        });
    }

    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            if (!validateAuthForm('registerForm')) {
                e.preventDefault();
                return false;
            }

            // Show loading before form submits
            showLoading();
            showNotification('Memproses registrasi...', 'info');
        });
    }
});

// Toggle password visibility
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const toggle = input.parentNode.querySelector('.password-toggle');

    if (input.type === 'password') {
        input.type = 'text';
        toggle.innerHTML = '<i class="fas fa-eye-slash"></i>';
    } else {
        input.type = 'password';
        toggle.innerHTML = '<i class="fas fa-eye"></i>';
    }
}

// Switch between forms
function switchForm(formId) {
    // Hide all forms
    document.querySelectorAll('.form-section').forEach(section => {
        section.classList.remove('active');
    });

    // Show selected form
    const targetForm = document.getElementById(`${formId}-form`);
    if (targetForm) {
        targetForm.classList.add('active');

        // Scroll to top of form container
        const formsContainer = document.querySelector('.forms-container');
        if (formsContainer) {
            formsContainer.scrollTop = 0;
        }

        // Focus on first input
        const firstInput = targetForm.querySelector('input');
        if (firstInput) {
            firstInput.focus();
        }
    }
}

// Tab switching helper
function switchTab(tabId) {
    const tabBtn = document.querySelector(`.tab-btn[data-tab="${tabId}"]`);
    if (tabBtn) {
        tabBtn.click();
    }
}

// Show loading
function showLoading() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.style.display = 'flex';
    }
}

// Hide loading
function hideLoading() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.style.display = 'none';
    }
}

// Show notification
function showNotification(message, type = 'info') {
    // Remove existing notifications
    document.querySelectorAll('.notification').forEach(notification => {
        notification.remove();
    });

    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'}"></i>
            <span>${message}</span>
        </div>
    `;
    document.body.appendChild(notification);

    // Auto remove after 4 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 4000);
}

// Validate form (auth-specific validation)
function validateAuthForm(formId) {
    if (formId === 'loginForm') {
        const username = document.getElementById('login-username').value.trim();
        const password = document.getElementById('login-password').value;

        if (!username || !password) {
            showNotification('Harap isi username dan password', 'warning');
            return false;
        }

        return true;
    }

    if (formId === 'registerForm') {
        const nik = document.getElementById('reg-nik').value.trim();
        const nama = document.getElementById('reg-nama').value.trim();
        const email = document.getElementById('reg-email').value.trim();
        const phone = document.getElementById('reg-phone').value.trim();
        const password = document.getElementById('reg-password').value;
        const confirmPassword = document.getElementById('reg-confirm-password').value;

        // Check required fields
        if (!nik || !nama || !email || !phone || !password || !confirmPassword) {
            showNotification('Harap isi semua field yang wajib', 'warning');
            return false;
        }

        // Check NIK length
        if (nik.length !== 16) {
            showNotification('NIK harus 16 digit', 'warning');
            return false;
        }

        // Check NIK is numeric
        if (!/^\d+$/.test(nik)) {
            showNotification('NIK harus berupa angka', 'warning');
            return false;
        }

        // Check email format
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            showNotification('Format email tidak valid', 'warning');
            return false;
        }

        // Check phone number
        if (phone.length < 10 || phone.length > 14) {
            showNotification('Nomor HP harus 10-14 digit', 'warning');
            return false;
        }

        // Check password length
        if (password.length < 8) {
            showNotification('Password minimal 8 karakter', 'warning');
            return false;
        }

        // Check password match
        if (password !== confirmPassword) {
            showNotification('Password tidak cocok', 'warning');
            return false;
        }

        // Check agreement
        const agreeCheckbox = document.getElementById('agreeCheckbox');
        if (agreeCheckbox && !agreeCheckbox.classList.contains('checked')) {
            showNotification('Harap setujui syarat dan ketentuan', 'warning');
            return false;
        }

        return true;
    }

    return false;
}

// Form submit event handlers are now inside DOMContentLoaded blocks

// Close flash message
function closeFlashMessage() {
    const flashMessage = document.getElementById('flashMessage');
    if (flashMessage) {
        flashMessage.style.animation = 'slideUp 0.3s ease-out';
        setTimeout(() => {
            flashMessage.remove();
        }, 300);
    }
}

// Auto hide flash message after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const flashMessage = document.getElementById('flashMessage');
    if (flashMessage) {
        setTimeout(() => {
            closeFlashMessage();
        }, 5000);
    }
});

// Add Enter key functionality for all forms
document.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        const activeForm = document.querySelector('.form-section.active');
        if (activeForm) {
            const activeFormId = activeForm.id.replace('-form', '');

            // For Enter key, just submit the form if validation passes
            if (activeFormId === 'login' && validateAuthForm('loginForm')) {
                document.getElementById('loginForm').submit();
            } else if (activeFormId === 'register' && validateAuthForm('registerForm')) {
                document.getElementById('registerForm').submit();
            }
        }
    }
});

// Add smooth scrolling for form container
const formsContainer = document.querySelector('.forms-container');
if (formsContainer) {
    formsContainer.addEventListener('wheel', function(e) {
        e.preventDefault();
        this.scrollTop += e.deltaY;
    });
}
