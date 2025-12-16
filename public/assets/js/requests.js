/**
 * Requests Page JavaScript
 * Handles table filtering, search, and request actions
 */

// Search and filter functionality
function filterRequests() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const rows = document.querySelectorAll('#requestsTable tr');

    rows.forEach(row => {
        // Skip header row
        if (row.cells.length < 4) return;

        const letterType = row.cells[1]?.textContent.toLowerCase() || '';
        const status = row.cells[2]?.textContent.toLowerCase() || '';

        const matchesSearch = letterType.includes(searchTerm);
        const matchesStatus = !statusFilter || status.includes(statusFilter);

        row.style.display = matchesSearch && matchesStatus ? '' : 'none';
    });
}

// Attach event listeners when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Search input event listener
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', filterRequests);
    }

    // Status filter event listener
    const statusFilter = document.getElementById('statusFilter');
    if (statusFilter) {
        statusFilter.addEventListener('change', filterRequests);
    }

    console.log('Requests page initialized');
});

// View request details with PDF preview
function viewRequest(requestId) {
    console.log('View request:', requestId);

    // Show loading modal
    openRequestModal();

    // Load request details via AJAX
    fetch(`${BASE_URL}/api/requests/${requestId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showRequestDetail(data.request);
            } else {
                showErrorModal('Gagal memuat detail permohonan');
            }
        })
        .catch(error => {
            console.error('Error loading request details:', error);
            showErrorModal('Terjadi kesalahan saat memuat data');
        });
}

// Download request
function downloadRequest(requestId) {
    console.log('Download request:', requestId);

    // Create a temporary link to trigger download
    const link = document.createElement('a');
    link.href = `${BASE_URL}/api/requests/${requestId}/download`;
    link.download = `surat_${requestId}.pdf`;
    link.style.display = 'none';

    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Modal functions
function closeModal() {
    const modal = document.getElementById('requestModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

// Open request detail modal with loading state
function openRequestModal() {
    const modal = document.getElementById('requestModal');
    const modalContent = document.getElementById('modalContent');

    if (modal && modalContent) {
        // Show loading state
        modalContent.innerHTML = `
            <div class="flex items-center justify-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
                <span class="ml-2 text-gray-600">Memuat detail permohonan...</span>
            </div>
        `;

        modal.classList.remove('hidden');
    }
}

// Show request detail with PDF preview
function showRequestDetail(request) {
    const modalContent = document.getElementById('modalContent');

    if (!modalContent) return;

    // Format status text and color
    const statusConfig = {
        'pending': { text: 'Menunggu', color: 'yellow' },
        'approved': { text: 'Disetujui', color: 'green' },
        'rejected': { text: 'Ditolak', color: 'red' },
        'completed': { text: 'Selesai', color: 'blue' }
    };

    const status = statusConfig[request.status] || { text: request.status, color: 'gray' };

    // Build HTML content
    let html = `
        <div class="space-y-6">
            <!-- Request Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h4 class="font-semibold text-gray-800 mb-2">Informasi Permohonan</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">ID Permohonan:</span>
                            <span class="font-medium">#${request.id}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Jenis Surat:</span>
                            <span class="font-medium">${request.letter_type_name || 'Tidak diketahui'}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-${status.color}-100 text-${status.color}-800">
                                ${status.text}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tanggal Pengajuan:</span>
                            <span class="font-medium">${formatDate(request.created_at)}</span>
                        </div>
                        ${request.approved_at ? `
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tanggal Diproses:</span>
                            <span class="font-medium">${formatDate(request.approved_at)}</span>
                        </div>
                        ` : ''}
                    </div>
                </div>

                <div>
                    <h4 class="font-semibold text-gray-800 mb-2">Data Pemohon</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Nama:</span>
                            <span class="font-medium">${request.user_full_name || 'Tidak diketahui'}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">NIK:</span>
                            <span class="font-medium">${request.user_nik || '-'}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Email:</span>
                            <span class="font-medium">${request.user_email || '-'}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Request Data -->
            ${request.request_data ? `
            <div>
                <h4 class="font-semibold text-gray-800 mb-2">Detail Permohonan</h4>
                <div class="bg-gray-50 p-4 rounded-lg">
                    ${formatRequestData(request.request_data)}
                </div>
            </div>
            ` : ''}

            <!-- PDF Preview -->
            ${request.status === 'approved' && request.generated_file ? `
            <div>
                <h4 class="font-semibold text-gray-800 mb-2">Preview Surat</h4>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-center">
                        <iframe src="${BASE_URL}/api/requests/${request.id}/preview"
                                class="w-full h-96 border border-gray-300 rounded-lg"
                                style="min-height: 600px;">
                        </iframe>
                        <div class="mt-4">
                            <button onclick="downloadRequest(${request.id})"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                                📄 Download PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            ` : request.status === 'approved' ? `
            <div class="bg-yellow-50 p-4 rounded-lg">
                <p class="text-yellow-800">
                    ⚠️ Surat sedang diproses. PDF akan tersedia dalam beberapa saat.
                </p>
            </div>
            ` : ''}

            <!-- Admin Notes -->
            ${request.admin_notes ? `
            <div>
                <h4 class="font-semibold text-gray-800 mb-2">Catatan Admin</h4>
                <div class="bg-blue-50 p-4 rounded-lg">
                    <p class="text-blue-800">${request.admin_notes}</p>
                </div>
            </div>
            ` : ''}
        </div>
    `;

    modalContent.innerHTML = html;
}

// Show error modal
function showErrorModal(message) {
    const modalContent = document.getElementById('modalContent');

    if (modalContent) {
        modalContent.innerHTML = `
            <div class="text-center py-8">
                <div class="text-red-500 text-6xl mb-4">⚠️</div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Terjadi Kesalahan</h3>
                <p class="text-gray-600">${message}</p>
                <button onclick="closeModal()"
                        class="mt-4 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                    Tutup
                </button>
            </div>
        `;
    }
}

// Helper function to format date
function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Helper function to format request data
function formatRequestData(requestData) {
    try {
        const data = typeof requestData === 'string' ? JSON.parse(requestData) : requestData;
        let html = '<div class="space-y-2">';

        for (const [key, value] of Object.entries(data)) {
            const label = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
            html += `
                <div class="flex justify-between">
                    <span class="text-gray-600">${label}:</span>
                    <span class="font-medium">${value || '-'}</span>
                </div>
            `;
        }

        html += '</div>';
        return html;
    } catch (e) {
        return '<p class="text-gray-500">Data tidak dapat diparsing</p>';
    }
}


