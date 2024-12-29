<?php

class ApplicationsController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Check if a job exists
    private function jobExists($jobId)
    {
        $query = "SELECT id FROM jobs WHERE id = :jobId";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':jobId', $jobId, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            return true;
        } else {
            return false;
        }
    }

    // Apply for a job
    public function applyForJob($userId, $jobId)
    {
        // Check if the job exists
        if (!$this->jobExists($jobId)) {
            return "Invalid job ID. The job does not exist.";
        }

        // Insert application into the database
        $query = "INSERT INTO applications (user_id, job_id) VALUES (:userId, :jobId)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':jobId', $jobId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return "Application submitted successfully.";
        } else {
            $errorInfo = $stmt->errorInfo();
            error_log("SQL Error: " . $errorInfo[2]);
            return "Failed to submit the application. Please try again later.";
        }
    }

    // Display the job application form
    public function showJobApplication($jobId)
    {
        // Check if the job exists
        if ($this->jobExists($jobId)) {
            // Fetch job details to pass to the view (optional)
            $query = "SELECT * FROM jobs WHERE id = :jobId";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':jobId', $jobId, PDO::PARAM_INT);
            $stmt->execute();
            $job = $stmt->fetch(PDO::FETCH_ASSOC);

            // Include the view and pass job data
            require_once 'app/views/job_application.php';
        } else {
            // If job doesn't exist, show an error message
            $errorMessage = "The job you're applying for doesn't exist.";
            require_once 'app/views/job_application.php';
        }
    }

    // Handle file uploads (e.g., resumes)
    public function moveUploadedFile($file, $uploadDir = 'app/uploads/resumes/')
    {
        $fullUploadDir = __DIR__ . '/../../' . $uploadDir;
        if (!is_dir($fullUploadDir)) {
            mkdir($fullUploadDir, 0755, true); // Create the directory if it doesn't exist
        }

        if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
            $fileName = basename($file['name']);
            $fileTmpPath = $file['tmp_name'];
            $uniqueFileName = uniqid() . '_' . $fileName;
            $filePath = $fullUploadDir . $uniqueFileName;

            if (move_uploaded_file($fileTmpPath, $filePath)) {
                return $uploadDir . $uniqueFileName;
            } else {
                error_log("Failed to move uploaded file.");
            }
        } else {
            error_log("File upload error: " . $file['error']);
        }

        return false;
    }

    // Update the status of a job application
    public function updateApplicationStatus($applicationId, $status)
    {
        // Update application status in the database
        $query = "UPDATE applications SET status = :status, notified = 'no' WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        $stmt->bindValue(':id', $applicationId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Fetch the user's email and job details to send a notification email
            $query = "
                SELECT users.email, jobs.title 
                FROM applications
                JOIN users ON applications.user_id = users.id
                JOIN jobs ON applications.job_id = jobs.id
                WHERE applications.id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $applicationId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $to = $result['email'];
                $jobTitle = $result['title'];
                $subject = "Application Status Update for $jobTitle";
                $body = "Your application for the job '$jobTitle' has been updated to '$status'.";
                if (EmailHelper::sendEmail($to, $subject, $body)) {
                    // Mark notification as sent
                    $updateQuery = "UPDATE applications SET notified = 'yes' WHERE id = :id";
                    $updateStmt = $this->db->prepare($updateQuery);
                    $updateStmt->bindValue(':id', $applicationId, PDO::PARAM_INT);
                    $updateStmt->execute();
                }
            }
        } else {
            $errorInfo = $stmt->errorInfo();
            error_log("SQL Error while updating status: " . $errorInfo[2]);
        }
    }
}

?>
