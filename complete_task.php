<?php
session_start();

if (!isset($_SESSION["userId"])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['taskId'])) {
    require_once './backend/dbconfig.php';

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $taskId = $_POST['taskId'];

        $stmt = $conn->prepare("UPDATE task_assignments SET task_status = 'Completed' WHERE id = ?");
        $stmt->execute([$taskId]);

        header("Location: supervisor.php");
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: supervisor.php");
    exit;
}
?>
