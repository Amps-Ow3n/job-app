document.getElementById('profileForm').addEventListener('submit', function (e) {
    e.preventDefault(); // Prevent default form submission

    // Validate required fields
    const username = document.getElementById('username').value;
    const email = document.getElementById('email').value;
    
    if (!username || !email) {
        alert('Please fill in all required fields.');
        return;
    }

    // Show loading indicator
    const loader = document.getElementById('loader');
    loader.style.display = 'block';

    const formData = new FormData(this);

    fetch('/updateProfile', {
        method: 'POST',
        body: formData,
    })
        .then((response) => response.json())
        .then((data) => {
            loader.style.display = 'none'; // Hide loader after response

            if (data.status === 'success') {
                alert(data.message);
                // Optional: Clear the form fields
                document.getElementById('profileForm').reset();
            } else {
                alert('Error updating profile. Please try again.');
            }
        })
        .catch((error) => {
            loader.style.display = 'none'; // Hide loader in case of error
            console.error('Error:', error);
            alert('There was an error updating your profile. Please try again later.');
        });
});
