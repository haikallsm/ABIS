/**
 * Admin Export Page JavaScript
 * Handles export functionality, filtering, and data display
 */

// Sample data for demonstration
const sampleData = [
    {
        id: 1,
        tanggal: '27/07/2025',
        jenisSurat: 'Surat Pengantar SKCK',
        pemohon: 'Ahmad',
        status: 'approved',
        jenisColor: 'primary'
    },
    {
        id: 2,
        tanggal: '26/07/2025',
        jenisSurat: 'Surat Keterangan Domisili',
        pemohon: 'Siti',
        status: 'pending',
        jenisColor: 'secondary'
    },
    {
        id: 3,
        tanggal: '25/07/2025',
        jenisSurat: 'Surat Keterangan Tidak Mampu',
        pemohon: 'Budi',
        status: 'waiting',
        jenisColor: 'accent'
    }
];

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

    if (!dataResult || !dataTableBody || !totalCount) return;

    // Clear existing data
    dataTableBody.innerHTML = '';

    // Filter data based on current filters
    const filteredData = filterData();

    // Update total count
    totalCount.textContent = filteredData.length;

    // Populate table with filtered data
    filteredData.forEach((item, index) => {
        const row = createTableRow(item, index + 1);
        dataTableBody.appendChild(row);
    });

    // Show results with animation
    dataResult.classList.remove('hidden');
    dataResult.classList.add('fade-in');

    // Scroll to results
    dataResult.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function filterData() {
    // Get filter values
    const dariTanggal = document.getElementById('dari_tanggal').value;
    const sampaiTanggal = document.getElementById('sampai_tanggal').value;
    const jenisKeterangan = document.getElementById('jenis_keterangan').checked;
    const jenisPengantar = document.getElementById('jenis_pengantar').checked;
    const jenisLainnya = document.getElementById('jenis_lainnya').checked;

    return sampleData.filter(item => {
        // Date filtering (simplified - in real app would compare dates properly)
        if (dariTanggal && sampaiTanggal) {
            // Basic date validation - in production, use proper date comparison
        }

        // Type filtering
        if (jenisKeterangan && item.jenisSurat.includes('Keterangan')) return true;
        if (jenisPengantar && item.jenisSurat.includes('Pengantar')) return true;
        if (jenisLainnya && (!item.jenisSurat.includes('Keterangan') && !item.jenisSurat.includes('Pengantar'))) return true;

        // If no type filters selected, show all
        if (!jenisKeterangan && !jenisPengantar && !jenisLainnya) return true;

        return false;
    });
}

function createTableRow(item, index) {
    const row = document.createElement('tr');
    row.className = 'table-row-hover';

    const statusClasses = {
        'approved': 'bg-green-100 text-green-700',
        'pending': 'bg-primary/20 text-primary',
        'waiting': 'bg-red-100 text-red-700'
    };

    const statusLabels = {
        'approved': 'Disetujui',
        'pending': 'Dalam Proses',
        'waiting': 'Menunggu'
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
        <td class="px-6 py-4 text-center">
            <span class="px-3 py-1.5 bg-${statusClasses[item.status]} font-medium rounded-full text-xs border border-${item.status === 'approved' ? 'green' : item.status === 'pending' ? 'primary' : 'red'}-200">
                ${statusLabels[item.status]}
            </span>
        </td>
    `;

    return row;
}

function handleExport(format) {
    const filteredData = filterData();

    if (filteredData.length === 0) {
        alert('Tidak ada data untuk diekspor. Silakan pilih filter terlebih dahulu.');
        return;
    }

    // Show loading state
    const button = document.getElementById(`btn-export-${format}`);
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = `
        <svg class="w-5 h-5 animate-spin mr-2" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Mengexport...
    `;

    // Simulate export process
    setTimeout(() => {
        alert(`Data berhasil diekspor dalam format ${format.toUpperCase()}!\n\nJumlah data: ${filteredData.length}`);

        // Reset button
        button.disabled = false;
        button.innerHTML = originalText;
    }, 2000);
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
