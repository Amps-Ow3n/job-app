<?php
require_once '../models/ReportModel.php';
class AdminController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Authenticate admin
    public function login($email, $password)
    {
        $query = "SELECT id, name, password FROM users WHERE email = :email AND is_admin = 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['password'])) {
            // Set session for admin
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];
            return true;
        }

        return false;
    }
    
     // Approve or reject user accounts
     public function manageUser($userId, $action)
     {
         $status = $action === 'approve' ? 'approved' : 'rejected';
         $query = "UPDATE users SET status = :status WHERE id = :id";
         $stmt = $this->db->prepare($query);
         $stmt->bindValue(':status', $status, PDO::PARAM_STR);
         $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
         return $stmt->execute();
     }
 
     // Approve or remove job postings
     public function manageJob($jobId, $action)
     {
         $status = $action === 'approve' ? 'approved' : 'rejected';
         $query = "UPDATE jobs SET status = :status WHERE id = :id";
         $stmt = $this->db->prepare($query);
         $stmt->bindValue(':status', $status, PDO::PARAM_STR);
         $stmt->bindValue(':id', $jobId, PDO::PARAM_INT);
         return $stmt->execute();
     }
     
    // Fetch all reports for the admin
public function getAllReports()
{
    $query = "
        SELECT 
            r.id, 
            r.report_type, 
            r.description, 
            r.status, 
            r.created_at, 
            u.name AS reported_by
        FROM 
            reports r
        LEFT JOIN 
            users u ON r.user_id = u.id
        ORDER BY 
            r.created_at DESC";
    
    $stmt = $this->db->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Update report status
public function updateReportStatus($reportId, $status)
{
    $query = "UPDATE reports SET status = :status WHERE id = :id";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':status', $status, PDO::PARAM_STR);
    $stmt->bindValue(':id', $reportId, PDO::PARAM_INT);
    return $stmt->execute();
}

     // Fetch application statistics
     public function getApplicationStats()
     {
         $query = "
             SELECT
                 (SELECT COUNT(*) FROM users WHERE status = 'pending') AS pending_users,
                 (SELECT COUNT(*) FROM jobs WHERE status = 'pending') AS pending_jobs,
                 (SELECT COUNT(*) FROM applications) AS total_applications
             ";
         $stmt = $this->db->query($query);
         return $stmt->fetch(PDO::FETCH_ASSOC);
     }
    // Check if admin is logged in
    public function isLoggedIn()
    {
        return isset($_SESSION['admin_id']);
    }

    // Logout admin
    public function logout()
    {
        session_unset();
        session_destroy();
    }
}
?>