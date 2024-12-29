<?php
// update_application_status.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $application_id = $_POST['application_id'];
    $status = $_POST['status'];

    $jobApplicationController = new JobApplicationController($db);
    $success = $jobApplicationController->updateApplicationStatus($application_id, $status);

    if ($success) {
        echo "Application status updated.";
    } else {
        echo "Failed to update application status.";
    }
}
?>