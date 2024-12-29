<!-- paginated_jobs.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Listings</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Job Listings</h1>
        <ul class="job-list">
            <?php foreach ($jobs as $job): ?>
                <li>
                    <h2><?php echo htmlspecialchars($job['title']); ?></h2>
                    <p><?php echo htmlspecialchars($job['description']); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
                    <p><strong>Category:</strong> <?php echo htmlspecialchars($job['category']); ?></p>
                    <a href="job_details.php?id=<?php echo $job['id']; ?>">View Details</a>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="<?php echo $i == $currentPage ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
    </div>
</body>
</html>
