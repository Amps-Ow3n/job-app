document.addEventListener('DOMContentLoaded', function () {
    const jobListingsContainer = document.getElementById('jobListings');
    const paginationControls = document.getElementById('paginationControls');

    let currentPage = 1;
    const jobsPerPage = 5; // Change this as needed

    // Function to fetch jobs
    function fetchJobs(page) {
        fetch(`/getJobs?page=${page}&limit=${jobsPerPage}`)
            .then(response => response.json())
            .then(data => {
                renderJobs(data.jobs);
                renderPagination(data.totalPages, data.currentPage);
            })
            .catch(error => console.error('Error fetching jobs:', error));
    }

    // Function to render jobs
    function renderJobs(jobs) {
        jobListingsContainer.innerHTML = ''; // Clear previous jobs

        if (jobs.length === 0) {
            jobListingsContainer.innerHTML = '<p>No jobs found.</p>';
            return;
        }

        jobs.forEach(job => {
            const jobCard = document.createElement('div');
            jobCard.classList.add('jobCard');
            jobCard.innerHTML = `
                <h3>${job.title}</h3>
                <p><strong>Location:</strong> ${job.location}</p>
                <p><strong>Category:</strong> ${job.category}</p>
                <p><strong>Company:</strong> ${job.company}</p>
                <p>${job.description}</p>
            `;
            jobListingsContainer.appendChild(jobCard);
        });
    }

    // Function to render pagination controls
    function renderPagination(totalPages, currentPage) {
        paginationControls.innerHTML = ''; // Clear previous controls

        for (let i = 1; i <= totalPages; i++) {
            const pageButton = document.createElement('button');
            pageButton.textContent = i;
            pageButton.classList.add('pageButton');
            if (i === currentPage) {
                pageButton.classList.add('active');
            }
            pageButton.addEventListener('click', function () {
                fetchJobs(i); // Fetch jobs for the clicked page
            });
            paginationControls.appendChild(pageButton);
        }
    }

    // Initial fetch
    fetchJobs(currentPage);
});
