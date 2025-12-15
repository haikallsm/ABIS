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

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Add any initialization code here if needed
    console.log('Admin dashboard initialized');
});