document.addEventListener('DOMContentLoaded', function () {
    loadStats();
    loadUsers();
    loadJobs();
});

// Fetch and display application statistics
function loadStats() {
    fetch('/getApplicationStats')
        .then(response => response.json())
        .then(stats => {
            document.getElementById('pendingUsers').textContent = stats.pending_users;
            document.getElementById('pendingJobs').textContent = stats.pending_jobs;
            document.getElementById('totalApplications').textContent = stats.total_applications;
        });
}

// Fetch and display users for management
function loadUsers() {
    fetch('/getUsers') // Assumes you have a route to fetch users
        .then(response => response.json())
        .then(users => {
            const userManagement = document.getElementById('userManagement');
            userManagement.innerHTML = '';
            users.forEach(user => {
                const div = document.createElement('div');
                div.innerHTML = `
                    <p>${user.name} (${user.email}) - Status: ${user.status}</p>
                    <button onclick="manageUser(${user.id}, 'approve')">Approve</button>
                    <button onclick="manageUser(${user.id}, 'reject')">Reject</button>
                `;
                userManagement.appendChild(div);
            });
        });
}

// Fetch and display jobs for management
function loadJobs() {
    fetch('/getJobs') // Assumes you have a route to fetch jobs
        .then(response => response.json())
        .then(jobs => {
            const jobManagement = document.getElementById('jobManagement');
            jobManagement.innerHTML = '';
            jobs.forEach(job => {
                const div = document.createElement('div');
                div.innerHTML = `
                    <p>${job.title} at ${job.company} - Status: ${job.status}</p>
                    <button onclick="manageJob(${job.id}, 'approve')">Approve</button>
                    <button onclick="manageJob(${job.id}, 'reject')">Reject</button>
                `;
                jobManagement.appendChild(div);
            });
        });
}

// Approve or reject a user
function manageUser(userId, action) {
    fetch('/manageUser', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ userId, action })
    }).then(() => loadUsers());
}

// Approve or remove a job
function manageJob(jobId, action) {
    fetch('/manageJob', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ jobId, action })
    }).then(() => loadJobs());
}

function updateApplicationStatus(applicationId, status) {
    fetch('/updateApplicationStatus', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ applicationId, status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Application status updated and notification sent.');
            loadApplications(); // Refresh application list
        }
    });
}

