<?php

class Job
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Create a new job listing
    public function createJob($userId, $title, $description, $category, $location)
    {
        try {
            $query = "INSERT INTO jobs (user_id, title, description, category, location) 
                      VALUES (:user_id, :title, :description, :category, :location)";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindValue(':title', $title, PDO::PARAM_STR);
            $stmt->bindValue(':description', $description, PDO::PARAM_STR);
            $stmt->bindValue(':category', $category, PDO::PARAM_STR);
            $stmt->bindValue(':location', $location, PDO::PARAM_STR);

            if ($stmt->execute()) {
                return true;
            } else {
                $errorInfo = $stmt->errorInfo(); // Capture error info
                error_log("SQL Error in createJob: " . $errorInfo[2]); // Log error
                return false;
            }
        } catch (Exception $e) {
            error_log("Exception in createJob: " . $e->getMessage());
            return false;
        }
    }

    // Fetch a job by its ID
    public function getJobById($jobId)
    {
        try {
            $query = "SELECT * FROM jobs WHERE id = :jobId";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':jobId', $jobId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Exception in getJobById: " . $e->getMessage());
            return false;
        }
    }

    // Fetch all jobs created by a specific user (employer)
    public function getJobsByUserId($userId)
    {
        try {
            $query = "SELECT * FROM jobs WHERE user_id = :userId";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Exception in getJobsByUserId: " . $e->getMessage());
            return false;
        }
    }

    // Fetch all jobs
    public function getAllJobs()
    {
        try {
            $query = "SELECT * FROM jobs";
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Exception in getAllJobs: " . $e->getMessage());
            return false;
        }
    }

    // Update job details
    public function updateJob($jobId, $title, $description, $category, $location)
    {
        try {
            $query = "UPDATE jobs SET title = :title, description = :description, category = :category, location = :location
                      WHERE id = :jobId";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':title', $title, PDO::PARAM_STR);
            $stmt->bindValue(':description', $description, PDO::PARAM_STR);
            $stmt->bindValue(':category', $category, PDO::PARAM_STR);
            $stmt->bindValue(':location', $location, PDO::PARAM_STR);
            $stmt->bindValue(':jobId', $jobId, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return true;
            } else {
                $errorInfo = $stmt->errorInfo(); // Capture error info
                error_log("SQL Error in updateJob: " . $errorInfo[2]); // Log error
                return false;
            }
        } catch (Exception $e) {
            error_log("Exception in updateJob: " . $e->getMessage());
            return false;
        }
    }

    // Delete a job
    public function deleteJob($jobId)
    {
        try {
            $query = "DELETE FROM jobs WHERE id = :jobId";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':jobId', $jobId, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return true;
            } else {
                $errorInfo = $stmt->errorInfo(); // Capture error info
                error_log("SQL Error in deleteJob: " . $errorInfo[2]); // Log error
                return false;
            }
        } catch (Exception $e) {
            error_log("Exception in deleteJob: " . $e->getMessage());
            return false;
        }
    }
}
?>
