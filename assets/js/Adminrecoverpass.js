document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);

    // 1. If recovery was successful
    if (urlParams.has('recovered')) {
        const email = urlParams.get('email');
        const pass = urlParams.get('pass');
        alert(`Account Found!\n\nEmail: ${email}\nPassword: ${pass}`);
    }

    // 2. If recovery failed (Account Not Found)
    if (urlParams.get('error') === 'not_found') {
        
        // Check if the current user profile context is a doctor or receptionist
        if (window.userRole === 'doctor' || window.userRole === 'receptionist') {
            alert("Error: No account found matched with these credentials.");
            window.location.href = "../../index.php"; // Redirect back to homepage login
        } else {
            // Only patients see the registration prompt
            alert("Invalid Information. Please register first.");
            window.location.href = "register.php";
        }
    }

    // 3. Security Alert for Admin
    if (urlParams.get('error') === 'admin_denied') {
        alert("Security Alert: Admin passwords cannot be recovered via this form. Contact System Support.");
        window.location.href = "login.php?role=admin";
    }
});