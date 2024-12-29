document.addEventListener('DOMContentLoaded', function () {
    const jobDetailsContainer = document.getElementById('jobDetails');
    const backButton = document.getElementById('backButton');
    const jobId = new URLSearchParams(window.location.search).get('id'); // Get job ID from URL
    const loader = document.getElementById('loader'); // Add a loader element to indicate loading

    // Show the loading indicator while fetching job details
    loader.style.display = 'block';

    // Function to fetch job details
    function fetchJobDetails(jobId) {
        fetch(`/jobDetails?id=${jobId}`)
            .then(response => response.json())
            .then(job => {
                loader.style.display = 'none'; // Hide loading indicator
                if (job.error) {
                    jobDetailsContainer.innerHTML = `<p>${job.error}</p>`;
                } else {
                    renderJobDetails(job);
                }
            })
            .catch(error => {
                console.error('Error fetching job details:', error);
                loader.style.display = 'none'; // Hide loading indicator
                jobDetailsContainer.innerHTML = '<p>Failed to load job details.</p>';
            });
    }

    // Function to render job details
    function renderJobDetails(job) {
        jobDetailsContainer.innerHTML = `
            <h2>${job.title}</h2>
            <p><strong>Company:</strong> ${job.company}</p>
            <p><strong>Location:</strong> ${job.location}</p>
            <p><strong>Category:</strong> ${job.category}</p>
            <p><strong>Description:</strong> ${job.description}</p>
            <button id="applyButton">Apply for this Job</button>
        `;

        // Add event listener to "Apply" button
        const applyButton = document.getElementById('applyButton');
        applyButton.addEventListener('click', () => applyForJob(job.id));
    }

    // Function to handle job application
    function applyForJob(jobId) {
        const applyButton = document.getElementById('applyButton');
        applyButton.disabled = true; // Disable the button after application

        fetch('/applyJob', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ jobId: jobId }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Successfully applied for the job!');
                } else {
                    alert(data.error || 'Failed to apply for the job.');
                }
            })
            .catch(error => {
                console.error('Error applying for job:', error);
                alert('An error occurred while applying for the job.');
            })
            .finally(() => {
                applyButton.disabled = false; // Re-enable button after response
            });
    }

    // Back button functionality
    if (backButton) {
        backButton.addEventListener('click', () => {
            window.location.href = '/jobListings.html';
        });
    }

    // Initial fetch
    if (jobId) {
        fetchJobDetails(jobId);
    } else {
        loader.style.display = 'none'; // Hide loader if no jobId is provided
        jobDetailsContainer.innerHTML = '<p>No job ID provided.</p>';
    }
});
