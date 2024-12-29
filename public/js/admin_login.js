document.getElementById('adminLoginForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const errorMessage = document.getElementById('errorMessage');
    const loginButton = document.getElementById('loginButton');  // Assuming there's a login button for disabling during submission
    const loader = document.getElementById('loader');  // Assuming you have a loader element for indicating loading state

    // Basic client-side validation
    if (!email || !password) {
        errorMessage.textContent = 'Email and password are required.';
        return;
    }

    // Show loading indicator
    loader.style.display = 'block';
    loginButton.disabled = true;  // Disable the login button to prevent multiple submissions

    // Send login request
    fetch('/adminLogin', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password })
    })
        .then(response => response.json())
        .then(data => {
            loader.style.display = 'none';  // Hide loading indicator
            loginButton.disabled = false;  // Enable the login button again

            if (data.success) {
                // Redirect to the admin dashboard
                window.location.href = '/adminDashboard.html';
            } else {
                // Display error message from server
                errorMessage.textContent = data.error || 'Login failed. Please check your credentials.';
            }
        })
        .catch(error => {
            loader.style.display = 'none';  // Hide loading indicator
            loginButton.disabled = false;  // Enable the login button again
            console.error('Error:', error);
            errorMessage.textContent = 'An error occurred. Please try again later.';
        });
});
