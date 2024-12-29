document.getElementById('registerForm').addEventListener('submit', function (e) {
    e.preventDefault(); // Prevent default form submission

    const formData = new FormData(this);

    fetch('/register', {
        method: 'POST',
        body: formData,
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.status === 'success') {
                alert(data.message);
                window.location.href = 'login.php';
            } else {
                alert(data.message);
            }
        })
        .catch((error) => {
            console.error('Error:', error);
        });
});
