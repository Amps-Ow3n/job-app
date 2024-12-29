<?php

class JobApplicationController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Fetch all applications for the employer
    public function getApplications($filter = null) {
        $query = "
           SELECT 
    a.application_id AS application_id, 
    u.name AS applicant_name, 
    u.email, 
    a.phone, 
    j.title AS job_title, 
    a.status, 
    a.resume_path, 
    a.created_at AS created_at
FROM 
    applications a
JOIN 
    users u ON a.user_id = u.id
JOIN 
    jobs j ON a.job_id = j.id
";
        
        if ($filter) {
            $query .= " WHERE " . $filter;
        }
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Update application status (approve or reject)
    public function updateApplicationStatus($applicationId, $newStatus) {
        try {
            $query = "UPDATE applications SET status = :status WHERE application_id = :application_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':status', $newStatus, PDO::PARAM_STR);
            $stmt->bindParam(':application_id', $applicationId, PDO::PARAM_INT);
    
            return $stmt->execute();
        } catch (PDOException $e) {
            // Log the error or handle it as needed
            error_log("Error updating application status: " . $e->getMessage());
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

        // Prevent duplicate applications
        if ($this->hasAlreadyApplied($userId, $jobId)) {
            return "You have already applied for this job.";
        }

        // Insert application with "pending" status
        $query = "INSERT INTO applications (user_id, job_id, status) VALUES (:userId, :jobId, 'pending')";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':jobId', $jobId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Notify the employer about the new application
            $jobController = new JobController($this->db);
            $jobController->notifyEmployerOfNewApplication($jobId, $userId);

            return "Application submitted successfully.";
        } else {
            return "Failed to submit application. Please try again later.";
        }
    }

    // Check if a job exists
    private function jobExists($jobId)
    {
        $query = "SELECT id FROM jobs WHERE id = :jobId";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':jobId', $jobId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }

    // Check if the user has already applied for the job
    private function hasAlreadyApplied($userId, $jobId)
    {
        $query = "SELECT id FROM applications WHERE user_id = :userId AND job_id = :jobId";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':jobId', $jobId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }

    // Fetch all applications for a specific user (applicant)
    public function getUserApplications($userId)
    {
        $query = "SELECT a.id, j.title AS job_title, a.status, a.resume_url 
                  FROM applications a
                  JOIN jobs j ON a.job_id = j.id
                  WHERE a.user_id = :userId";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Returns all applications for the user
    }

    // Check if an application exists
    private function applicationExists($applicationId)
    {
        $query = "SELECT id FROM applications WHERE id = :applicationId";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':applicationId', $applicationId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }
}
?>
