document.addEventListener('DOMContentLoaded', function () {
    // Registration Form Validation
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function (event) {
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const userType = document.getElementById('user_type').value;

            if (!name || !email || !password || !userType) {
                alert('Please fill in all fields!');
                event.preventDefault();
            } else {
                if (!validateEmail(email)) {
                    alert('Invalid email format!');
                    event.preventDefault();
                }
            }
        });
    }

    // Login Form Validation
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function (event) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            if (!email || !password) {
                alert('Please enter your email and password!');
                event.preventDefault();
            } else {
                if (!validateEmail(email)) {
                    alert('Invalid email format!');
                    event.preventDefault();
                }
            }
        });
    }

    // Job Creation Form Validation (Employer Dashboard)
    const jobForm = document.getElementById('jobForm');
    if (jobForm) {
        jobForm.addEventListener('submit', function (event) {
            const title = document.getElementById('title').value;
            const description = document.getElementById('description').value;
            const location = document.getElementById('location').value;

            if (!title || !description || !location) {
                alert('Please fill in all fields!');
                event.preventDefault();
            }
        });
    }

    // Email validation function
    function validateEmail(email) {
        const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        return emailPattern.test(email);
    }
});
