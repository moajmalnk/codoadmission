document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const loginButton = document.getElementById('loginButton');
    const errorAlert = document.getElementById('errorAlert');
    const errorMessage = document.getElementById('errorMessage');

    // Check if user is already logged in with valid token
    const token = localStorage.getItem('authToken');
    const userData = localStorage.getItem('userData');
    
    if (token && userData) {
        try {
            // Verify token format and user data
            const user = JSON.parse(userData);
            if (user && user.username && user.role) {
                // Only redirect if we're actually on the login page
                if (window.location.pathname.includes('login.html')) {
                    window.location.replace('dashboard.html');
                }
                return;
            }
        } catch (e) {
            // Invalid token or user data, clear storage
            localStorage.removeItem('authToken');
            localStorage.removeItem('userData');
        }
    }

    // Hide error alert when user starts typing
    document.querySelectorAll('input').forEach(input => {
        input.addEventListener('input', () => {
            errorAlert.style.display = 'none';
        });
    });

    loginForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Reset error message
        errorAlert.style.display = 'none';
        
        // Get form data
        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value.trim();

        // Validate input
        if (!username || !password) {
            errorMessage.textContent = 'Please enter both username and password.';
            errorAlert.style.display = 'block';
            return;
        }

        // Update button state
        loginButton.disabled = true;
        loginButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Signing in...';

        try {
            const response = await fetch('http://localhost/codoadmission/admissionbackend/api/login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ username, password })
            });

            let data;
            try {
                data = await response.json();
            } catch (e) {
                console.error('JSON parsing error:', e);
                throw new Error('Invalid response from server');
            }

            if (response.ok && data.success) {
                // Store the token and user data
                localStorage.setItem('authToken', data.token);
                localStorage.setItem('userData', JSON.stringify(data.user));
                
                // Show success state
                loginButton.innerHTML = '<i class="fas fa-check me-2"></i>Success!';
                loginButton.classList.remove('btn-primary');
                loginButton.classList.add('btn-success');
                
                // Redirect to dashboard after a brief delay
                setTimeout(() => {
                    window.location.replace('dashboard.html');
                }, 500);
            } else {
                // Show error message
                errorMessage.textContent = data.error || 'Login failed. Please check your credentials.';
                errorAlert.style.display = 'block';
                
                // Reset button
                loginButton.disabled = false;
                loginButton.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i>Sign In';
            }
        } catch (error) {
            console.error('Login error:', error);
            errorMessage.textContent = 'Server error. Please try again later.';
            errorAlert.style.display = 'block';
            
            // Reset button
            loginButton.disabled = false;
            loginButton.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i>Sign In';
        }
    });
}); 