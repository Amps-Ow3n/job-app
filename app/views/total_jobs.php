<!-- total_jobs.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Total Jobs</title>
</head>
<body>
    <div class="container">
        <h1>Total Jobs Available: <?php echo htmlspecialchars($totalJobs); ?></h1>
        <p><a href="paginated_jobs.php">View All Jobs</a></p>
    </div>
</body>
</html>
