/**
 * Admin Dashboard JavaScript
 * Handles sidebar functionality and UI interactions
 */

// Toggle sidebar collapsed state
function toggleSidebar() {
    var app = document.getElementById('appLayout');
    if (!app) return;
    app.classList.toggle('sidebar-collapsed');
}

// Indonesian day names
const hariIndo = {
    'Sunday': 'Minggu',
    'Monday': 'Senin',
    'Tuesday': 'Selasa',
    'Wednesday': 'Rabu',
    'Thursday': 'Kamis',
    'Friday': 'Jumat',
    'Saturday': 'Sabtu'
};

// Indonesian month names (full names for header)
const bulanIndo = {
    'January': 'Januari',
    'February': 'Februari',
    'March': 'Maret',
    'April': 'April',
    'May': 'Mei',
    'June': 'Juni',
    'July': 'Juli',
    'August': 'Agustus',
    'September': 'September',
    'October': 'Oktober',
    'November': 'November',
    'December': 'Desember'
};

// Indonesian month abbreviations (for activities)
const bulanIndoShort = {
    'Jan': 'Jan', 'Feb': 'Feb', 'Mar': 'Mar', 'Apr': 'Apr',
    'May': 'Mei', 'Jun': 'Jun', 'Jul': 'Jul', 'Aug': 'Ags',
    'Sep': 'Sep', 'Oct': 'Okt', 'Nov': 'Nov', 'Dec': 'Des'
};

// Live clock update function
function updateClock() {
    const now = new Date();

    // Get Indonesian day name
    const hari = hariIndo[now.toLocaleDateString('en-US', { weekday: 'long' })];

    // Get Indonesian month name
    const bulan = bulanIndo[now.toLocaleDateString('en-US', { month: 'long' })];

    // Get date and year
    const tanggal = now.getDate();
    const tahun = now.getFullYear();

    // Get time
    const jam = String(now.getHours()).padStart(2, '0');
    const menit = String(now.getMinutes()).padStart(2, '0');
    const detik = String(now.getSeconds()).padStart(2, '0');

    // Format: "Senin, 20 Desember 2025 10:30:45"
    const waktuLengkap = `${hari}, ${tanggal} ${bulan} ${tahun} ${jam}:${menit}:${detik}`;

    // Update the clock element
    const clockElement = document.getElementById('live-clock');
    if (clockElement) {
        clockElement.textContent = waktuLengkap;
    }
}

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Start the live clock
    updateClock(); // Update immediately
    setInterval(updateClock, 1000); // Update every second

    console.log('Admin dashboard initialized with live clock');
});