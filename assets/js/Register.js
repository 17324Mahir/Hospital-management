document.getElementById('registrationForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // 1. GET ALL INPUT VALUES
    const firstName = document.getElementById('firstName').value.trim();
    const lastName = document.getElementById('lastName').value.trim();
    const dob = document.getElementById('dob').value;
    const phone = document.getElementById('phone').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const role = document.getElementById('role').value;

    // 2. VALIDATION RULES (REGULAR EXPRESSIONS)
    const nameRegex = /^[A-Za-z]+$/; 
    const phoneRegex = /^(?:\+8801|01)[3-9]\d{8}$/; 
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const passRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

    // 3. CHECK VALIDITY
    if (!nameRegex.test(firstName) || !nameRegex.test(lastName)) {
        alert("First and Last Name must contain only letters.");
        return;
    }

    const birthYear = new Date(dob).getFullYear();
    const currentYear = new Date().getFullYear();
    if (birthYear < 1900 || birthYear > currentYear) {
        alert(`Date of Birth must be between 1900 and ${currentYear}.`);
        return;
    }

    if (!phoneRegex.test(phone)) {
        alert("Please enter a valid Bangladesh mobile number.");
        return;
    }

    if (email !== "" && !emailRegex.test(email)) {
        alert("Please enter a valid email address format.");
        return;
    }

    if (!passRegex.test(password)) {
        alert("Password must be at least 8 characters long and include: Uppercase, Lowercase, Number, and Special Character.");
        return;
    }

    if (password !== confirmPassword) {
        alert("Passwords do not match!");
        return;
    }

    if (!role) {
        alert("Please select a role.");
        return;
    }

    // 4. SEND TO DATABASE
    const formData = new FormData();
    formData.append('firstName', firstName); 
    formData.append('lastName', lastName);
    formData.append('email', email);
    formData.append('phone', phone);
    formData.append('dob', dob);
    formData.append('password', password);
    formData.append('role', role);

    fetch('Registrationprocess.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) { throw new Error('Network response was not ok'); }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            // THE NEW MULTI-STEP REDIRECT LOGIC
            alert("Step 1 Complete! Redirecting to profile setup...");
            
            // This takes them to doctor_profile_form.php or patient_profile_form.php
            // AND attaches their new ID to the URL (e.g., ?user_id=15)
            window.location.href = data.redirect + "?user_id=" + data.user_id; 
            
        } else {
            // Shows your Core Logic Duplicate/Policy Errors
            alert("Registration Blocked: " + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("An error occurred. Check the Console (F12) for details.");
    });
});