// User Navigation and Dashboard functionality

document.addEventListener('DOMContentLoaded', function() {
    initUserNavigation();
    initDateTime();
    initFormHandlers();
});

// Inisialisasi navigasi user
function initUserNavigation() {
    // Event listener untuk menu items
    document.querySelectorAll('.menu-item').forEach(item => {
        item.addEventListener('click', function() {
            if (this.id === 'logout-btn') {
                // Logout sudah ditangani oleh form submit
                return;
            }

            // Remove active class from all items
            document.querySelectorAll('.menu-item').forEach(i => i.classList.remove('active'));

            // Add active class to clicked item
            this.classList.add('active');

            // Show corresponding page
            const target = this.getAttribute('data-target');
            if (target) {
                showPage(target);
            }
        });
    });
}

// Update tanggal dan waktu
function initDateTime() {
    updateDateTime();
    setInterval(updateDateTime, 60000); // Update every minute
}

function updateDateTime() {
    const now = new Date();
    const options = {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    };
    const dateString = now.toLocaleDateString('id-ID', options);
    const dateElement = document.getElementById('page-date');
    if (dateElement) {
        dateElement.textContent = dateString;
    }
}

// Menampilkan halaman tertentu
function showPage(pageName) {
    // Hide all pages
    document.querySelectorAll('.page-content').forEach(content => {
        content.classList.remove('active');
    });

    // Show target page
    const targetContent = document.getElementById(`${pageName}-content`);
    if (targetContent) {
        targetContent.classList.add('active');

        // Update page title
        const titles = {
            'dashboard': 'Selamat Datang, ' + (window.currentUserName || 'User') + '!',
            'pengajuan': 'Buat Surat - ABIS',
            'riwayat': 'Riwayat Surat - ABIS',
            'profil': 'Profil Saya - ABIS'
        };

        const titleElement = document.getElementById('page-title');
        if (titleElement) {
            titleElement.textContent = titles[pageName] || 'ABIS';
        }
    }
}

// Fungsi untuk buat surat dari dashboard
function buatSurat(jenis) {
    showPage('pengajuan');
    const selectElement = document.getElementById('jenis-surat');
    if (selectElement) {
        selectElement.value = jenis;
        ubahFormSurat();
    }
}

// Mengubah form berdasarkan jenis surat
function ubahFormSurat() {
    const jenis = document.getElementById('jenis-surat').value;
    const container = document.getElementById('surat-forms-container');

    if (!container) return;

    // Clear existing forms
    container.innerHTML = '';

    if (!jenis) return;

    // Generate form based on type
    const formHTML = generateFormHTML(jenis);
    container.innerHTML = formHTML;
}

// Generate form HTML berdasarkan jenis surat
function generateFormHTML(jenis) {
    const forms = {
        'SKD': `
            <div id="form-skd" class="surat-form">
                <div class="form-group">
                    <label for="keperluan">Keperluan Surat</label>
                    <input type="text" id="keperluan" placeholder="Contoh: Untuk melamar pekerjaan">
                </div>
                <div class="form-group">
                    <label for="keterangan">Keterangan Tambahan</label>
                    <textarea id="keterangan" rows="3" placeholder="Jelaskan keperluan surat secara detail"></textarea>
                </div>
            </div>
        `,
        'SKU': `
            <div id="form-sku" class="surat-form">
                <div class="form-group">
                    <label for="nama-usaha">Nama Usaha</label>
                    <input type="text" id="nama-usaha" placeholder="Contoh: Toko Sembako Murah">
                </div>
                <div class="form-group">
                    <label for="alamat-usaha">Alamat Usaha</label>
                    <textarea id="alamat-usaha" rows="2" placeholder="Alamat lengkap usaha"></textarea>
                </div>
                <div class="form-group">
                    <label for="jenis-usaha">Jenis Usaha</label>
                    <input type="text" id="jenis-usaha" placeholder="Contoh: Perdagangan sembako">
                </div>
                <div class="form-group">
                    <label for="lama-usaha">Lama Usaha</label>
                    <input type="text" id="lama-usaha" placeholder="Contoh: 2 tahun">
                </div>
            </div>
        `,
        'SPN': `
            <div id="form-spn" class="surat-form">
                <div class="form-group">
                    <label for="nama-pasangan">Nama Calon Pasangan</label>
                    <input type="text" id="nama-pasangan" placeholder="Nama lengkap calon pasangan">
                </div>
                <div class="form-group">
                    <label for="tempat-lahir-pasangan">Tempat Lahir Calon Pasangan</label>
                    <input type="text" id="tempat-lahir-pasangan" placeholder="Tempat lahir calon pasangan">
                </div>
                <div class="form-group">
                    <label for="tanggal-lahir-pasangan">Tanggal Lahir Calon Pasangan</label>
                    <input type="date" id="tanggal-lahir-pasangan">
                </div>
            </div>
        `,
        'SKTM': `
            <div id="form-sktm" class="surat-form">
                <div class="form-group">
                    <label for="penghasilan">Penghasilan per Bulan</label>
                    <input type="text" id="penghasilan" placeholder="Contoh: Rp 1.500.000">
                </div>
                <div class="form-group">
                    <label for="tanggungan">Jumlah Tanggungan Keluarga</label>
                    <input type="number" id="tanggungan" placeholder="Contoh: 4">
                </div>
                <div class="form-group">
                    <label for="alasan">Alasan Permohonan</label>
                    <textarea id="alasan" rows="3" placeholder="Jelaskan alasan membutuhkan surat ini"></textarea>
                </div>
            </div>
        `
    };

    return forms[jenis] || '<p>Jenis surat tidak ditemukan</p>';
}

// Kirim pengajuan surat
function kirimPengajuan() {
    const jenis = document.getElementById('jenis-surat').value;

    if (!jenis) {
        showNotification('Pilih jenis surat terlebih dahulu', 'error');
        return;
    }

    // Collect form data
    const formData = new FormData();
    formData.append('letter_type', jenis);

    // Get form fields based on type
    const fields = getFormFields(jenis);
    let isValid = true;

    fields.forEach(field => {
        const element = document.getElementById(field.id);
        if (element && element.hasAttribute('required') && !element.value.trim()) {
            showFieldError(element, field.label + ' wajib diisi');
            isValid = false;
        } else if (element) {
            formData.append(field.name, element.value);
            clearFieldError(element);
        }
    });

    if (!isValid) {
        return;
    }

    // Show loading
    const submitBtn = document.querySelector('#pengajuan-content .btn-primary');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
    submitBtn.disabled = true;

    // Send request
    fetch(window.baseUrl + '/requests/create', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Pengajuan surat berhasil dikirim!', 'success');
            showPage('dashboard');
            // Reset form
            document.getElementById('jenis-surat').value = '';
            document.getElementById('surat-forms-container').innerHTML = '';
        } else {
            showNotification(data.message || 'Terjadi kesalahan', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan saat mengirim pengajuan', 'error');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

// Get form fields for each letter type
function getFormFields(jenis) {
    const fieldMaps = {
        'SKD': [
            { id: 'keperluan', name: 'keperluan', label: 'Keperluan', required: true },
            { id: 'keterangan', name: 'keterangan', label: 'Keterangan' }
        ],
        'SKU': [
            { id: 'nama-usaha', name: 'nama_usaha', label: 'Nama Usaha', required: true },
            { id: 'alamat-usaha', name: 'alamat_usaha', label: 'Alamat Usaha', required: true },
            { id: 'jenis-usaha', name: 'jenis_usaha', label: 'Jenis Usaha', required: true },
            { id: 'lama-usaha', name: 'lama_usaha', label: 'Lama Usaha' }
        ],
        'SPN': [
            { id: 'nama-pasangan', name: 'nama_pasangan', label: 'Nama Calon Pasangan', required: true },
            { id: 'tempat-lahir-pasangan', name: 'tempat_lahir_pasangan', label: 'Tempat Lahir', required: true },
            { id: 'tanggal-lahir-pasangan', name: 'tanggal_lahir_pasangan', label: 'Tanggal Lahir', required: true }
        ],
        'SKTM': [
            { id: 'penghasilan', name: 'penghasilan', label: 'Penghasilan', required: true },
            { id: 'tanggungan', name: 'tanggungan', label: 'Tanggungan', required: true },
            { id: 'alasan', name: 'alasan', label: 'Alasan' }
        ]
    };

    return fieldMaps[jenis] || [];
}

// View detail function
function viewDetail(requestId) {
    showNotification('Melihat detail pengajuan ID: ' + requestId, 'info');
    // TODO: Implement modal or redirect to detail page
}

// Download function
function downloadSurat(requestId) {
    window.location.href = window.baseUrl + '/requests/' + requestId + '/download';
}

// Notification system
function showNotification(message, type = 'info') {
    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        warning: 'bg-yellow-500',
        info: 'bg-blue-500'
    };

    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg text-white ${colors[type]} shadow-lg transform translate-x-full transition-transform duration-300`;
    notification.innerHTML = `
        <div class="flex items-center">
            <span>${message}</span>
            <button class="ml-4 text-white hover:opacity-75 close">&times;</button>
        </div>
    `;

    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);

    // Auto remove
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => notification.remove(), 300);
    }, 5000);

    // Close on click
    notification.querySelector('.close').addEventListener('click', function() {
        notification.classList.add('translate-x-full');
        setTimeout(() => notification.remove(), 300);
    });
}

// Form validation helpers
function showFieldError(field, message) {
    clearFieldError(field);
    field.classList.add('border-red-500');

    const errorDiv = document.createElement('div');
    errorDiv.className = 'text-red-500 text-sm mt-1 field-error';
    errorDiv.textContent = message;

    field.parentNode.appendChild(errorDiv);
}

function clearFieldError(field) {
    field.classList.remove('border-red-500');
    const errorDiv = field.parentNode.querySelector('.field-error');
    if (errorDiv) {
        errorDiv.remove();
    }
}

// Initialize form handlers
function initFormHandlers() {
    // Add any additional form handlers here
}

// Set global variables (should be set by PHP)
window.baseUrl = '<?php echo BASE_URL; ?>';
window.currentUserName = '<?php echo htmlspecialchars($current_user['full_name'] ?? 'User'); ?>';
