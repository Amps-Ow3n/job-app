<?php
class Application
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Method to apply for a job
    public function applyForJob($applicantName, $email, $phone, $resumePath, $jobId, $userId)
{
    try {
        // Prepare the SQL query
        $query = "INSERT INTO applications (job_id, user_id, applicant_name, email, phone, resume_path, created_at) 
                  VALUES (:job_id, :user_id, :applicant_name, :email, :phone, :resume_path, NOW())";
        $stmt = $this->db->prepare($query);

        // Bind values to the query
        $stmt->bindValue(':job_id', $jobId, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':applicant_name', $applicantName);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':phone', $phone);
        $stmt->bindValue(':resume_path', $resumePath);

        // Execute the query
        if ($stmt->execute()) {
            return true;
        } else {
            // Log the error if query execution fails
            $errorInfo = $stmt->errorInfo();
            error_log("SQL Error in applyForJob: " . $errorInfo[2]);
            return false;
        }
    } catch (Exception $e) {
        // Log exception message if there's an issue
        error_log("Exception in applyForJob: " . $e->getMessage());
        return false;
    }
}

    public function getApplicationsByJobId($jobId)
    {
        $query = "SELECT * FROM applications WHERE job_id = :jobId";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':jobId', $jobId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all applications for a specific user (applicant)
    public function getApplicationsByUserId($userId)
    {
        $query = "SELECT * FROM applications WHERE user_id = :userId";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update application status (approved, rejected)
    public function updateApplicationStatus($applicationId, $status)
    {
        try {
            $query = "UPDATE applications SET status = :status WHERE id = :applicationId";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':status', $status);
            $stmt->bindValue(':applicationId', $applicationId, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            // Log the error or return an error message
            error_log($e->getMessage());
            return false;
        }
    }

    // Delete application
    public function deleteApplication($applicationId)
    {
        try {
            $query = "DELETE FROM applications WHERE id = :applicationId";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':applicationId', $applicationId, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            // Log the error or return an error message
            error_log($e->getMessage());
            return false;
        }
    }

    // Get a specific application by user and job (to check if user already applied)
    public function getApplicationByUserJob($userId, $jobId)
    {
        $query = "SELECT * FROM applications WHERE user_id = :userId AND job_id = :jobId";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':jobId', $jobId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

?>
