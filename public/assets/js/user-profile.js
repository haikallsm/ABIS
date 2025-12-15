// User Profile JavaScript Functionality

// Edit mode state
let isEditMode = false;

// Toggle edit mode
function toggleEditMode() {
    const form = document.getElementById('profileForm');
    const inputs = form.querySelectorAll('input, textarea');
    const editBtn = document.getElementById('editBtn');
    const actionButtons = document.getElementById('actionButtons');

    isEditMode = !isEditMode;

    if (isEditMode) {
        // Enable editing
        inputs.forEach(input => {
            if (input.id !== 'full_name' && input.id !== 'nik') { // Keep name and NIK readonly
                input.removeAttribute('readonly');
                input.classList.add('bg-white');
                input.classList.remove('bg-gray-50');
            }
        });

        // Update button text
        editBtn.innerHTML = `
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            Batal Edit
        `;
        editBtn.className = 'bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors flex items-center';

        // Show action buttons
        actionButtons.style.display = 'flex';

    } else {
        cancelEdit();
    }
}

// Cancel edit mode
function cancelEdit() {
    const form = document.getElementById('profileForm');
    const inputs = form.querySelectorAll('input, textarea');
    const editBtn = document.getElementById('editBtn');
    const actionButtons = document.getElementById('actionButtons');

    isEditMode = false;

    // Disable editing
    inputs.forEach(input => {
        input.setAttribute('readonly', 'true');
        input.classList.remove('bg-white');
        input.classList.add('bg-gray-50');
    });

    // Reset form to original values
    form.reset();

    // Update button text
    editBtn.innerHTML = `
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
        </svg>
        Edit Profil
    `;
    editBtn.className = 'bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary/90 transition-colors flex items-center';

    // Hide action buttons
    actionButtons.style.display = 'none';
}

// Avatar change function
function changeAvatar() {
    // Create file input
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/*';
    input.onchange = function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                showNotification('Ukuran file maksimal 2MB', 'error');
                return;
            }

            // Validate file type
            if (!file.type.startsWith('image/')) {
                showNotification('File harus berupa gambar', 'error');
                return;
            }

            // Show loading
            showNotification('Mengupload foto...', 'info');

            // Here you would normally upload the file to the server
            // For now, we'll simulate success
            setTimeout(() => {
                showNotification('Foto profil berhasil diupdate!', 'success');
            }, 1500);
        }
    };
    input.click();
}

// Notification system
function showNotification(message, type = 'success') {
    // Remove existing notification
    const existing = document.getElementById('profile-notification');
    if (existing) {
        existing.remove();
    }

    // Create notification element
    const notification = document.createElement('div');
    notification.id = 'profile-notification';
    notification.className = 'fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg flex items-center space-x-3 animate-slide-in';

    let bgColor, textColor, icon;
    switch(type) {
        case 'success':
            bgColor = 'bg-green-100 border-green-400';
            textColor = 'text-green-700';
            icon = `
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            `;
            break;
        case 'error':
            bgColor = 'bg-red-100 border-red-400';
            textColor = 'text-red-700';
            icon = `
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            `;
            break;
        case 'info':
            bgColor = 'bg-blue-100 border-blue-400';
            textColor = 'text-blue-700';
            icon = `
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            `;
            break;
    }

    notification.innerHTML = `
        <div class="border ${bgColor} ${textColor} px-4 py-3 rounded-lg shadow-lg flex items-center space-x-3">
            ${icon}
            <div>
                <p class="font-semibold">${type === 'success' ? 'Berhasil!' : type === 'error' ? 'Error!' : 'Info'}</p>
                <p class="text-sm">${message}</p>
            </div>
            <button onclick="closeNotification()" class="${textColor} hover:opacity-70">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;

    document.body.appendChild(notification);

    // Auto-hide after 3 seconds
    setTimeout(() => {
        closeNotification();
    }, 3000);
}

function closeNotification() {
    const notification = document.getElementById('profile-notification');
    if (notification) {
        notification.classList.add('animate-slide-out');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }
}

// Form validation
document.getElementById('profileForm').addEventListener('submit', function(e) {
    if (!isEditMode) {
        e.preventDefault();
        return;
    }

    const email = document.getElementById('email').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const address = document.getElementById('address').value.trim();

    // Basic validation
    if (!email) {
        e.preventDefault();
        showNotification('Email tidak boleh kosong', 'error');
        return;
    }

    if (!address) {
        e.preventDefault();
        showNotification('Alamat tidak boleh kosong', 'error');
        return;
    }

    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        e.preventDefault();
        showNotification('Format email tidak valid', 'error');
        return;
    }

    // Phone validation (optional)
    if (phone && !/^[\d\s\-\+\(\)]+$/.test(phone)) {
        e.preventDefault();
        showNotification('Format nomor telepon tidak valid', 'error');
        return;
    }

    // Show loading state
    const submitBtn = e.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = `
        <svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
        </svg>
        Menyimpan...
    `;
    submitBtn.disabled = true;

    // Re-enable after 3 seconds (in case of error)
    setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 3000);
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Add animation styles if not already present
    if (!document.getElementById('profile-animations')) {
        const link = document.createElement('link');
        link.id = 'profile-animations';
        link.rel = 'stylesheet';
        link.href = '/assets/css/user-profile.css';
        document.head.appendChild(link);
    }
});
