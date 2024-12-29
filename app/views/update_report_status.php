<?php
session_start();
require_once 'db.php';
require_once 'AdminController.php';

$db = new PDO($dsn, $username, $password);
$adminController = new AdminController($db);

if (!$adminController->isLoggedIn()) {
    header("Location: user_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reportId = $_POST['report_id'] ?? null;
    $status = $_POST['status'] ?? null;

    if ($reportId && $status) {
        $adminController->updateReportStatus($reportId, $status);
    }

    header("Location: reports.php");
    exit();
}
