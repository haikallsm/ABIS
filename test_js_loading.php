<?php
// Test script to verify JavaScript loading
echo "<!DOCTYPE html>
<html>
<head>
    <title>Test JS Loading</title>
</head>
<body>
    <h1>Testing JavaScript Loading</h1>
    <p>Open browser console to see loaded scripts</p>

    <!-- Load admin-requests.js -->
    <script src=\"/public/assets/js/admin-requests.js\"></script>

    <script>
        // Test if functions are available
        setTimeout(function() {
            console.log('Testing JavaScript functions:');
            console.log('approveRequest function:', typeof approveRequest);
            console.log('rejectRequest function:', typeof rejectRequest);
            console.log('showNotification function:', typeof showNotification);
            console.log('Swal object:', typeof Swal);

            // Test notification
            if (typeof showNotification !== 'undefined') {
                showNotification('Test notification - if you see this, SweetAlert2 is working!', 'success');
            } else {
                alert('showNotification function not found!');
            }
        }, 2000); // Wait 2 seconds for async loading
    </script>
</body>
</html>";
?>