document.getElementById('loginForm').addEventListener('submit', function (e) {
    e.preventDefault(); // Prevent default form submission

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    // Basic form validation
    if (!username || !password) {
        alert('Please enter both username and password.');
        return;
    }

    // Show loading indicator
    const loader = document.getElementById('loader');
    loader.style.display = 'block';

    const formData = new FormData(this);

    fetch('/login', {
        method: 'POST',
        body: formData,
    })
        .then((response) => response.json())
        .then((data) => {
            loader.style.display = 'none'; // Hide loader after response

            if (data.status === 'success') {
                alert(data.message);
                window.location.href = 'dashboard.php'; // Redirect to dashboard
            } else {
                alert(data.message); // Show login error message
            }
        })
        .catch((error) => {
            loader.style.display = 'none'; // Hide loader in case of error
            console.error('Error:', error);
            alert('There was an error processing your request. Please try again later.');
        });

    // Clear form fields after submission (optional)
    document.getElementById('username').value = '';
    document.getElementById('password').value = '';
});
