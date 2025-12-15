/**
 * User Dashboard JavaScript
 * Handles user dashboard functionality and UI interactions
 */

// Toggle sidebar collapsed state
function toggleSidebar() {
    var app = document.getElementById('appLayout');
    if (!app) return;
    app.classList.toggle('sidebar-collapsed');
}

// Buat Surat function (placeholder for now)
function buatSurat(typeCode) {
    console.log('Buat surat:', typeCode);
    // Redirect to create request page
    window.location.href = '/requests/create';
}

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Add any initialization code here if needed
    console.log('User dashboard initialized');
});

