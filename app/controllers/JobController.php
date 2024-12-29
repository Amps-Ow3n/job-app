<?php

class JobController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db; // Database instance
    }

    // Fetch job listings with filters (keyword, category, location)
    public function searchJobs()
    {
        $keyword = isset($_POST['keyword']) ? $_POST['keyword'] : '';
        $category = isset($_POST['category']) ? $_POST['category'] : '';
        $location = isset($_POST['location']) ? $_POST['location'] : '';

        // Construct SQL query
        $query = "SELECT * FROM jobs WHERE 1=1";
        if (!empty($keyword)) {
            $query .= " AND (title LIKE :keyword OR description LIKE :keyword)";
        }
        if (!empty($category)) {
            $query .= " AND category = :category";
        }
        if (!empty($location)) {
            $query .= " AND location = :location";
        }

        $stmt = $this->db->prepare($query);
        if (!empty($keyword)) {
            $stmt->bindValue(':keyword', '%' . $keyword . '%');
        }
        if (!empty($category)) {
            $stmt->bindValue(':category', $category);
        }
        if (!empty($location)) {
            $stmt->bindValue(':location', $location);
        }

        $stmt->execute();
        $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($jobs); // Return filtered job listings as JSON
    }

    // Fetch paginated jobs
    public function getPaginatedJobs($page, $limit)
    {
        $offset = ($page - 1) * $limit; // Calculate offset for pagination

        $query = "SELECT j.id, j.title, j.location, j.category, j.description, u.name AS company 
                  FROM jobs j
                  JOIN users u ON j.user_id = u.id
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch total job count (to calculate pages)
    public function getTotalJobs()
    {
        $query = "SELECT COUNT(*) as total FROM jobs";
        $stmt = $this->db->query($query);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // Fetch job details by ID
    public function getJobById($jobId)
    {
        $query = "SELECT j.id, j.title, j.location, j.category, j.description, u.name AS company 
                  FROM jobs j
                  JOIN users u ON j.user_id = u.id
                  WHERE j.id = :jobId";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':jobId', $jobId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Apply for a job (Job Application logic)
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
            // Notify employer about the new application
            $this->notifyEmployerOfNewApplication($jobId, $userId);
            return "Application submitted successfully.";
        } else {
            $errorInfo = $stmt->errorInfo();
            error_log("SQL Error: " . $errorInfo[2]);
            return "Failed to submit the application. Please try again later.";
        }
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

    // Notify the employer about a new job application
    private function notifyEmployerOfNewApplication($jobId, $applicantId)
    {
        // Fetch employer email and job details
        $query = "
            SELECT users.email, jobs.title 
            FROM jobs
            JOIN users ON jobs.user_id = users.id
            WHERE jobs.id = :jobId";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':jobId', $jobId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $to = $result['email'];
            $jobTitle = $result['title'];
            $subject = "New Application for $jobTitle";
            $body = "You have received a new application for the job '$jobTitle'. Log in to review the application.";
            EmailHelper::sendEmail($to, $subject, $body);
        }
    }
}

?>
