<?php
class ReportModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Fetch reports from the database
    public function getReports() {
        $query = "SELECT * FROM reports";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get a report by ID
    public function getReportById($id) {
        $query = "SELECT * FROM reports WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update report status
    public function updateReportStatus($id, $status) {
        $query = "UPDATE reports SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
}
