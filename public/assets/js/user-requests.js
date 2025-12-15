// User Requests JavaScript Functionality

// Global variables
let currentRequestData = null;

// Modal functions
function viewRequest(requestId) {
    const modal = document.getElementById('requestModal');
    const modalContent = document.getElementById('modalContent');

    modal.classList.remove('hidden');

    // Show loading state
    modalContent.innerHTML = `
        <div class="flex items-center justify-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
            <span class="ml-3 text-gray-600">Memuat detail...</span>
        </div>
    `;

    // Load request details via AJAX (simulated for now)
    setTimeout(() => {
        // This would normally be an AJAX call
        // For now, we'll simulate with static data based on the request ID
        loadRequestDetail(requestId);
    }, 500);
}

function closeModal() {
    document.getElementById('requestModal').classList.add('hidden');
}

// Load request detail content
function loadRequestDetail(requestId) {
    const modalContent = document.getElementById('modalContent');

    // Find the request data from the table (simplified approach)
    const rows = document.querySelectorAll('#requestsTable tr');
    let requestData = null;

    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        if (cells.length > 0 && cells[0].textContent.trim() == requestId.toString()) {
            requestData = {
                id: requestId,
                type: cells[1].textContent.trim(),
                status: cells[2].textContent.trim(),
                createdDate: cells[3].textContent.trim(),
                completedDate: cells[4].textContent.trim()
            };
        }
    });

    if (!requestData) {
        modalContent.innerHTML = '<p class="text-red-500">Data permohonan tidak ditemukan</p>';
        return;
    }

    // Create detailed content
    modalContent.innerHTML = `
        <div class="space-y-6">
            <!-- Status Badge -->
            <div class="flex justify-between items-center">
                <span class="status-${getStatusClass(requestData.status)} text-sm font-semibold px-3 py-1 rounded-full">
                    ${requestData.status}
                </span>
                <span class="text-sm text-gray-500">ID: #${requestData.id}</span>
            </div>

            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Jenis Surat</label>
                    <p class="text-dark font-medium">${requestData.type}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Tanggal Pengajuan</label>
                    <p class="text-dark font-medium">${requestData.createdDate}</p>
                </div>
                ${requestData.completedDate !== '-' ? `
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Tanggal Selesai</label>
                    <p class="text-dark font-medium">${requestData.completedDate}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Estimasi Selesai</label>
                    <p class="text-dark font-medium">${requestData.completedDate}</p>
                </div>
                ` : `
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Estimasi Selesai</label>
                    <p class="text-dark font-medium">2-3 hari kerja</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Status Proses</label>
                    <p class="text-dark font-medium">Sedang diproses oleh admin</p>
                </div>
                `}
            </div>

            <!-- Additional Information -->
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-2">Catatan</label>
                <p class="text-gray-700 bg-gray-50 p-3 rounded-lg">
                    ${getStatusNote(requestData.status)}
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                ${requestData.status === 'Disetujui' || requestData.status === 'Selesai' ? `
                    <button onclick="downloadRequest(${requestData.id})"
                            class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary/90 transition-colors flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Unduh Surat
                    </button>
                ` : requestData.status === 'Ditolak' ? `
                    <button onclick="retryRequest('${requestData.type.toLowerCase().replace('surat ', '')}')"
                            class="bg-accent text-white px-6 py-2 rounded-lg hover:bg-accent/90 transition-colors flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Ulangi Permohonan
                    </button>
                ` : `
                    <button onclick="closeModal()"
                            class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                        Tutup
                    </button>
                `}
            </div>
        </div>
    `;
}

function getStatusClass(status) {
    switch(status) {
        case 'Disetujui': return 'approved';
        case 'Ditolak': return 'pending'; // Using red color for rejected
        case 'Menunggu': return 'waiting';
        case 'Selesai': return 'approved';
        default: return 'waiting';
    }
}

function getStatusNote(status) {
    switch(status) {
        case 'Disetujui':
            return 'Permohonan Anda telah disetujui. Surat dapat diunduh menggunakan tombol di bawah.';
        case 'Ditolak':
            return 'Permohonan Anda ditolak karena data tidak lengkap atau tidak memenuhi syarat. Silakan perbaiki dan ajukan ulang.';
        case 'Menunggu':
            return 'Permohonan sedang dalam proses verifikasi oleh petugas desa. Mohon menunggu konfirmasi selanjutnya.';
        case 'Selesai':
            return 'Permohonan telah selesai diproses dan surat siap diunduh.';
        default:
            return 'Status permohonan sedang diproses.';
    }
}

// Download function
function downloadRequest(requestId) {
    // Show loading notification
    showNotification('Mengunduh surat...', 'info');

    // Simulate download delay
    setTimeout(() => {
        // In a real application, this would trigger a file download
        // For now, we'll just show a success message
        showNotification('Surat berhasil diunduh!', 'success');

        // Close modal if open
        closeModal();
    }, 1500);
}

// Retry function
function retryRequest(letterType) {
    closeModal();

    // Redirect to create page with the letter type
    window.location.href = `${BASE_URL}/requests/create?type=${letterType}`;
}

// Notification functions
function showNotification(message, type = 'success') {
    // Remove existing notification
    const existing = document.getElementById('notificationToast');
    if (existing) {
        existing.remove();
    }

    // Create notification element
    const notification = document.createElement('div');
    notification.id = 'notificationToast';
    notification.className = 'fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg animate-slide-in';

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
    const notification = document.getElementById('notificationToast');
    if (notification) {
        notification.classList.add('animate-slide-out');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }
}

// Filter and search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value.toLowerCase();
        const rows = document.querySelectorAll('#requestsTable tr');

        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length > 1) {
                const typeText = cells[1].textContent.toLowerCase();
                const statusText = cells[2].textContent.toLowerCase();

                const matchesSearch = typeText.includes(searchTerm);
                const matchesStatus = !statusValue || statusText.includes(statusValue);

                row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);

    // Close modal when clicking outside
    document.getElementById('requestModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
});
