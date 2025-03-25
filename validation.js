document.getElementById('admissionForm').addEventListener('submit', function(e) {
    let isValid = true;
    const email = document.getElementById('email').value;
    const phone = document.getElementById('phone').value;
    
    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert('Please enter a valid email address');
        isValid = false;
    }

    // Phone validation
    const phoneRegex = /^\d{10}$/;
    if (!phoneRegex.test(phone)) {
        alert('Please enter a valid 10-digit phone number');
        isValid = false;
    }

    if (!isValid) {
        e.preventDefault();
    }
}); 