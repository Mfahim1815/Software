<?php
session_start();

if (!isset($_SESSION["userId"])) {
    header("Location: login.php");
    exit;
}

require_once 'dbconfig.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=tasks.csv');

$output = fopen('php://output', 'w');

fputcsv($output, array('Task ID', 'Task Name', 'Description', 'Due Date', 'Status', 'Progress','Staff Member'));

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT id, task_name, task_description, due_date, task_status, progress, staff_member FROM task_assignments");
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, $row);
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

fclose($output);
exit();
?>
