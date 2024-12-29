document.addEventListener('DOMContentLoaded', function () {
    fetchApplications();

    function fetchApplications() {
        const loader = document.getElementById('loader'); // Add a loader element to indicate loading
        loader.style.display = 'block'; // Show loading indicator

        fetch('/getApplications', {
            method: 'GET'
        })
            .then(response => response.json())
            .then(data => {
                loader.style.display = 'none'; // Hide loading indicator
                const applicationResults = document.getElementById('applicationResults');
                applicationResults.innerHTML = ''; // Clear previous results

                if (data.length > 0) {
                    const fragment = document.createDocumentFragment(); // Use DocumentFragment to optimize DOM updates

                    data.forEach((application) => {
                        const applicationCard = document.createElement('div');
                        applicationCard.classList.add('applicationCard');
                        applicationCard.innerHTML = `
                            <h3>Job Title: ${application.job_title}</h3>
                            <p>Applicant: ${application.applicant_name}</p>
                            <p>Resume: <a href="${application.resume_url}" target="_blank">View Resume</a></p>
                            <p>Status: <strong id="status-${application.id}">${application.status}</strong></p>
                            <button class="approveBtn" data-id="${application.id}">Approve</button>
                            <button class="rejectBtn" data-id="${application.id}">Reject</button>
                        `;
                        fragment.appendChild(applicationCard);
                    });

                    applicationResults.appendChild(fragment);

                    // Add event listeners to approve/reject buttons
                    document.querySelectorAll('.approveBtn').forEach(button => {
                        button.addEventListener('click', function () {
                            updateApplicationStatus(this.dataset.id, 'approved', this);
                        });
                    });

                    document.querySelectorAll('.rejectBtn').forEach(button => {
                        button.addEventListener('click', function () {
                            updateApplicationStatus(this.dataset.id, 'rejected', this);
                        });
                    });
                } else {
                    applicationResults.innerHTML = '<p>No applications found.</p>';
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                loader.style.display = 'none'; // Hide loading indicator on error
                alert('Failed to load applications. Please try again later.');
            });
    }

    function updateApplicationStatus(applicationId, status, button) {
        const formData = new FormData();
        formData.append('application_id', applicationId);
        formData.append('status', status);

        button.disabled = true; // Disable the button after click to prevent multiple submissions

        fetch('/updateApplicationStatus', {
            method: 'POST',
            body: formData,
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    alert(`Application ${status} successfully!`);
                    const statusElement = document.getElementById(`status-${applicationId}`);
                    statusElement.textContent = status.charAt(0).toUpperCase() + status.slice(1); // Update status dynamically
                } else {
                    alert('Failed to update application status.');
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                alert('Failed to update application status. Please try again later.');
            })
            .finally(() => {
                button.disabled = false; // Re-enable button after the request is done
            });
    }
});
