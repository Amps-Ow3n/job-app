document.getElementById('searchForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const keyword = document.getElementById('keyword').value;
    const category = document.getElementById('category').value;
    const location = document.getElementById('location').value;

    // Show loading indicator
    const loader = document.getElementById('loader');
    loader.style.display = 'block';

    const formData = new FormData();
    if (keyword) formData.append('keyword', keyword);
    if (category) formData.append('category', category);
    if (location) formData.append('location', location);

    fetch('/searchJobs', {
        method: 'POST',
        body: formData,
    })
        .then((response) => response.json())
        .then((data) => {
            const jobResults = document.getElementById('jobResults');
            jobResults.innerHTML = ''; // Clear previous results

            if (data.length > 0) {
                data.forEach((job) => {
                    const jobCard = document.createElement('div');
                    jobCard.classList.add('jobCard');
                    jobCard.innerHTML = `
                        <h3>${job.title}</h3>
                        <p>${job.description}</p>
                        <p><strong>Category:</strong> ${job.category}</p>
                        <p><strong>Location:</strong> ${job.location}</p>
                        <a href="/jobDetails.html?id=${job.id}">View Details</a>
                    `;
                    jobResults.appendChild(jobCard);
                });
            } else {
                jobResults.innerHTML = '<p>No jobs found with the specified filters.</p>';
            }

            // Hide loader after data is loaded
            loader.style.display = 'none';
        })
        .catch((error) => {
            console.error('Error:', error);
            const jobResults = document.getElementById('jobResults');
            jobResults.innerHTML = '<p>There was an error processing your request. Please try again later.</p>';
            loader.style.display = 'none'; // Hide loader
        });

    // Clear the form fields after search
    document.getElementById('keyword').value = '';
    document.getElementById('category').value = '';
    document.getElementById('location').value = '';
});
