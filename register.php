<?php

require_once 'dbconfig.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        
        $sql = "INSERT INTO Users (username, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username, $email, $password, $role]);

        if ($stmt->rowCount() > 0) {
            
            $userId = $conn->lastInsertId();

            
            $sql = "INSERT INTO task_assignments (user_id, access_level) VALUES (?, 1)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$userId]);

            if ($stmt->rowCount() > 0) {
                header("Location: ../registration_success.php"); 
                exit;
            } else {
                header("Location: ../registration_failure.php"); 
                exit;
            }
        } else {
            header("Location: ../registration_failure.php");
            exit;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        
    }
}
?>
