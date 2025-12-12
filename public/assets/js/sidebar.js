// Sidebar functionality for admin dashboard

document.addEventListener('DOMContentLoaded', function() {
    initSidebar();
    initSidebarNavigation();
    initResponsiveSidebar();
});

function initSidebar() {
    // Highlight active sidebar link based on current URL
    const currentPath = window.location.pathname;
    const sidebarLinks = document.querySelectorAll('#sidebar-nav .sidebar-link');

    sidebarLinks.forEach(link => {
        link.classList.remove('active');
        const href = link.getAttribute('href');

        if (href && (currentPath === href || currentPath.startsWith(href + '/'))) {
            link.classList.add('active');
        }
    });
}

function initSidebarNavigation() {
    // Add click handlers for sidebar links
    const sidebarLinks = document.querySelectorAll('#sidebar-nav .sidebar-link');

    sidebarLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Remove active class from all links
            sidebarLinks.forEach(l => l.classList.remove('active'));

            // Add active class to clicked link
            this.classList.add('active');

            // Store active link in localStorage
            localStorage.setItem('activeSidebarLink', this.getAttribute('href'));
        });
    });

    // Restore active link from localStorage
    const storedActiveLink = localStorage.getItem('activeSidebarLink');
    if (storedActiveLink) {
        const activeLink = document.querySelector(`#sidebar-nav .sidebar-link[href="${storedActiveLink}"]`);
        if (activeLink) {
            sidebarLinks.forEach(l => l.classList.remove('active'));
            activeLink.classList.add('active');
        }
    }
}

function initResponsiveSidebar() {
    // Create overlay for mobile
    const overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay';
    document.body.appendChild(overlay);

    // Mobile menu toggle (if hamburger button exists)
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const sidebar = document.querySelector('.sidebar');

    if (mobileMenuBtn && sidebar) {
        mobileMenuBtn.addEventListener('click', function() {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
        });

        // Close sidebar when clicking overlay
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
        });
    }

    // Auto-hide sidebar on mobile when clicking a link
    const sidebarLinks = document.querySelectorAll('#sidebar-nav .sidebar-link');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth < 1024) { // lg breakpoint
                if (sidebar) sidebar.classList.remove('open');
                overlay.classList.remove('show');
            }
        });
    });

    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 1024) {
            if (sidebar) sidebar.classList.remove('open');
            overlay.classList.remove('show');
        }
    });
}

// Utility functions for sidebar
function setActiveSidebarLink(href) {
    const sidebarLinks = document.querySelectorAll('#sidebar-nav .sidebar-link');
    sidebarLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === href) {
            link.classList.add('active');
        }
    });
    localStorage.setItem('activeSidebarLink', href);
}

function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.sidebar-overlay');

    if (sidebar) {
        sidebar.classList.toggle('open');
    }
    if (overlay) {
        overlay.classList.toggle('show');
    }
}

// Keyboard navigation for accessibility
document.addEventListener('keydown', function(e) {
    // ESC key to close sidebar on mobile
    if (e.key === 'Escape') {
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.querySelector('.sidebar-overlay');

        if (sidebar && sidebar.classList.contains('open')) {
            sidebar.classList.remove('open');
        }
        if (overlay && overlay.classList.contains('show')) {
            overlay.classList.remove('show');
        }
    }
});

// Export functions for global use
window.sidebarUtils = {
    setActiveLink: setActiveSidebarLink,
    toggleSidebar: toggleSidebar
};
