/**
 * Admin Export Page JavaScript
 * Handles export functionality, filtering, and data display
 */

// Global variable to store current data
let currentData = [];

// Toggle sidebar collapsed state
function toggleSidebar() {
    const app = document.getElementById('appLayout');
    if (!app) return;

    app.classList.toggle('sidebar-collapsed');

    // Simpan state sidebar di localStorage
    if (app.classList.contains('sidebar-collapsed')) {
        localStorage.setItem('sidebar-collapsed', 'true');
    } else {
        localStorage.setItem('sidebar-collapsed', 'false');
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Cek state sidebar saat halaman dimuat
    if (localStorage.getItem('sidebar-collapsed') === 'true') {
        document.getElementById('appLayout')?.classList.add('sidebar-collapsed');
    }

    // Set tanggal hari ini sebagai default
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('dari_tanggal').value = today;
    document.getElementById('sampai_tanggal').value = today;

    // Initialize event listeners
    initializeEventListeners();

    console.log('Admin export page initialized');
});

function initializeEventListeners() {
    // Tampilkan data button
    const btnTampilkan = document.getElementById('btn-tampilkan');
    if (btnTampilkan) {
        btnTampilkan.addEventListener('click', handleTampilkanData);
    }

    // Export buttons
    const btnExportExcel = document.getElementById('btn-export-excel');
    if (btnExportExcel) {
        btnExportExcel.addEventListener('click', () => handleExport('excel'));
    }

    const btnExportPdf = document.getElementById('btn-export-pdf');
    if (btnExportPdf) {
        btnExportPdf.addEventListener('click', () => handleExport('pdf'));
    }

    // Rows per page selector
    const rowsPerPageSelect = document.getElementById('rows-per-page');
    if (rowsPerPageSelect) {
        rowsPerPageSelect.addEventListener('change', handleRowsPerPageChange);
    }

    // Month selector auto-fill dates
    const monthSelect = document.getElementById('pilih_bulan');
    if (monthSelect) {
        monthSelect.addEventListener('change', handleMonthSelection);
    }
}

function handleTampilkanData() {
    const dataResult = document.getElementById('data-result');
    const dataTableBody = document.getElementById('data-table-body');
    const totalCount = document.getElementById('total-count');
    const btnTampilkan = document.getElementById('btn-tampilkan');

    if (!dataResult || !dataTableBody || !totalCount) return;

    console.log('Starting data loading...');

    // Show loading state
    const originalText = btnTampilkan.innerHTML;
    btnTampilkan.disabled = true;
    btnTampilkan.innerHTML = `
        <svg class="w-5 h-5 animate-spin mr-2" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Memuat Data...
    `;

    // Clear existing data
    dataTableBody.innerHTML = '';

    // Fetch data from API - always fetch, even with no filters
    fetchExportData()
        .then(data => {
            console.log('Data loaded successfully:', data.length, 'records');
            currentData = data;

            // Update total count
            totalCount.textContent = currentData.length;

            // Populate table with data
            if (currentData.length > 0) {
                currentData.forEach((item, index) => {
                    const row = createTableRow(item, index + 1);
                    dataTableBody.appendChild(row);
                });
            } else {
                // Show empty state
                const emptyRow = document.createElement('tr');
                emptyRow.innerHTML = `
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-lg font-medium">Tidak ada data ditemukan</p>
                        <p class="text-sm">Coba ubah filter atau tambahkan data baru</p>
                    </td>
                `;
                dataTableBody.appendChild(emptyRow);
            }

            // Show results with animation
            dataResult.classList.remove('hidden');
            dataResult.classList.add('fade-in');

            // Scroll to results
            dataResult.scrollIntoView({ behavior: 'smooth', block: 'start' });
        })
        .catch(error => {
            console.error('Error loading data:', error);
            showNotification('Gagal memuat data. Silakan coba lagi.', 'error');

            // Show error in table
            const errorRow = document.createElement('tr');
            errorRow.innerHTML = `
                <td colspan="5" class="px-6 py-8 text-center text-red-500">
                    <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-lg font-medium">Gagal memuat data</p>
                    <p class="text-sm">Periksa koneksi internet dan coba lagi</p>
                </td>
            `;
            dataTableBody.appendChild(errorRow);
            dataResult.classList.remove('hidden');
        })
        .finally(() => {
            // Reset button
            btnTampilkan.disabled = false;
            btnTampilkan.innerHTML = originalText;
        });
}

function fetchExportData() {
    // Get filter values
    const dariTanggal = document.getElementById('dari_tanggal').value;
    const sampaiTanggal = document.getElementById('sampai_tanggal').value;

    // Build query parameters
    const params = new URLSearchParams();
    if (dariTanggal) params.append('dari_tanggal', dariTanggal);
    if (sampaiTanggal) params.append('sampai_tanggal', sampaiTanggal);

    const url = `${window.baseUrl}/admin/api/export-data?${params.toString()}`;
    console.log('Fetching data from:', url);

    // Make API request
    return fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('API response:', data);
        if (data.success) {
            return data.data;
        } else {
            throw new Error(data.message || 'Failed to fetch data');
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        throw error;
    });
}

function filterData() {
    // Note: Filtering is now done on the server side
    // This function is kept for backward compatibility with export functions
    return currentData;
}

function createTableRow(item, index) {
    const row = document.createElement('tr');
    row.className = 'table-row-hover';

    const statusClasses = {
        'approved': 'bg-green-100 text-green-700',
        'pending': 'bg-primary/20 text-primary',
        'rejected': 'bg-red-100 text-red-700'
    };

    const statusLabels = {
        'approved': 'Disetujui',
        'pending': 'Dalam Proses',
        'rejected': 'Ditolak'
    };

    row.innerHTML = `
        <td class="px-6 py-4 text-center font-medium text-gray-800">${index}</td>
        <td class="px-6 py-4">${item.tanggal}</td>
        <td class="px-6 py-4">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-${item.jenisColor}"></div>
                ${item.jenisSurat}
            </div>
        </td>
        <td class="px-6 py-4 font-medium">${item.pemohon}</td>
        <td class="px-6 py-4 font-mono text-sm">${item.nik || '-'}</td>
        <td class="px-6 py-4 text-center">
            <span class="px-3 py-1.5 ${statusClasses[item.status]} font-medium rounded-full text-xs border ${
                item.status === 'approved' ? 'border-green-200' :
                item.status === 'pending' ? 'border-primary/50' :
                'border-red-200'
            }">
                ${statusLabels[item.status]}
            </span>
        </td>
    `;

    return row;
}

function createMobileCard(item, index) {
    const card = document.createElement('div');
    card.className = 'bg-white rounded-lg border border-gray-200 p-4 shadow-sm hover:shadow-md transition-shadow';

    const statusClasses = {
        'approved': 'bg-green-100 text-green-700 border-green-200',
        'pending': 'bg-primary/20 text-primary border-primary/50',
        'rejected': 'bg-red-100 text-red-700 border-red-200'
    };

    const statusLabels = {
        'approved': 'Disetujui',
        'pending': 'Dalam Proses',
        'rejected': 'Ditolak'
    };

    card.innerHTML = `
        <div class="flex items-start justify-between mb-3">
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 bg-primary/10 text-primary rounded-full flex items-center justify-center text-sm font-bold">${index}</span>
                <div>
                    <h4 class="font-medium text-gray-900">${item.pemohon}</h4>
                    <p class="text-sm text-gray-500">${item.tanggal}</p>
                </div>
            </div>
            <span class="px-2 py-1 ${statusClasses[item.status]} font-medium rounded-full text-xs border">
                ${statusLabels[item.status]}
            </span>
        </div>
        <div class="space-y-2">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-${item.jenisColor}"></div>
                <span class="text-sm font-medium text-gray-700">${item.jenisSurat}</span>
            </div>
            <div class="text-sm">
                <span class="font-medium text-gray-600">NIK:</span>
                <span class="font-mono ml-2">${item.nik || '-'}</span>
            </div>
        </div>
    `;

    return card;
}

function handleExport(format) {
    // Handle different export formats
    if (format === 'pdf') {
        alert('Fitur Export PDF massal belum tersedia. Gunakan download PDF individual untuk setiap surat.');
        return;
    }

    if (format === 'excel') {
        // Get filter values from form inputs
        const dariTanggal = document.getElementById('dari_tanggal').value;
        const sampaiTanggal = document.getElementById('sampai_tanggal').value;

        // Build URL with parameters
        let baseUrl = window.baseUrl || window.BASE_URL || '';
        if (!baseUrl) {
            alert('Error: Base URL tidak ditemukan. Silakan refresh halaman.');
            return;
        }

        // Ensure baseUrl doesn't end with slash for proper concatenation
        baseUrl = baseUrl.replace(/\/$/, '');

        let url = `${baseUrl}/admin/export/excel`;
        const params = new URLSearchParams();

        if (dariTanggal) {
            params.append('dari_tanggal', dariTanggal);
        }
        if (sampaiTanggal) {
            params.append('sampai_tanggal', sampaiTanggal);
        }

        if (params.toString()) {
            url += '?' + params.toString();
        }

        console.log('Base URL:', baseUrl);
        console.log('Exporting Excel with URL:', url);
        console.log('Filter params - Dari:', dariTanggal, 'Sampai:', sampaiTanggal);

        // Trigger download by redirecting to the export endpoint
        try {
            window.location.href = url;
        } catch (error) {
            console.error('Error redirecting:', error);
            alert('Terjadi kesalahan saat mengakses export. Silakan coba lagi.');
        }
    } else {
        alert(`Format export ${format.toUpperCase()} belum didukung.`);
    }
}

function handleRowsPerPageChange() {
    const newLimit = this.value;
    console.log(`Rows per page changed to: ${newLimit}`);
    // In real implementation, this would trigger a new data fetch
}

function handleMonthSelection() {
    const selectedMonth = this.value;
    if (!selectedMonth) return;

    const currentYear = new Date().getFullYear();
    const startDate = new Date(currentYear, selectedMonth - 1, 1);
    const endDate = new Date(currentYear, selectedMonth, 0);

    document.getElementById('dari_tanggal').value = startDate.toISOString().split('T')[0];
    document.getElementById('sampai_tanggal').value = endDate.toISOString().split('T')[0];
}

// Utility functions
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
}

function showNotification(message, type = 'info') {
    // Simple notification - in production, use a proper notification system
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 ${
        type === 'success' ? 'bg-green-500' :
        type === 'error' ? 'bg-red-500' :
        'bg-blue-500'
    }`;
    notification.textContent = message;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 3000);
}

