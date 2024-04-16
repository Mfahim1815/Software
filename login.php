<?php

session_start();
require_once 'dbconfig.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($email == "admin" && $password == "admin") {
        $_SESSION['userId'] = 0;
        header("Location: ../admin_dashboard.php");
        exit;
    }

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT * FROM Users WHERE email = ? AND password = ?");
        $stmt->execute([$email, $password]);

        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $userId = $user['id'];
            $role = $user['role'];

            if ($role == 0){
                
                $_SESSION['userId'] = $userId;
                
                $stmt = $conn->prepare("SELECT access_level FROM task_assignments WHERE user_id = ?");
                
                $stmt->execute([$userId]);
                
                sleep(1);
                
                if ($stmt->rowCount() > 0) {
                    $firstRow = $stmt->fetch(PDO::FETCH_ASSOC);
    
                    $secondRow = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($secondRow !== false) {
                        $accessLevel = $secondRow['access_level'];
                        $_SESSION['accessLevel'] = $accessLevel;
                    } else {
                        $accessLevel = $firstRow['access_level'];
                        $_SESSION['accessLevel'] = $accessLevel;
                        echo "There is no second row in the result set.";
                    }             
                                
                    switch ($accessLevel) {
                        
                        case 0:
                            header("Location: ../assigned_tasks.php");
                            break;
                        
                        case 1:
                            header("Location: ../no_task_assigned.php");
                            break;
                        
                        default:
                            header("Location: ../no_task_assigned.php");
                            break;
                    }
                    exit;
                } else {
                    
                    header("Location: ../no_task_assigned.php");
                    exit;
                }
            }
            else {
                $_SESSION['userId'] = $userId;
                header("Location: ../supervisor.php");
                exit;
            }
            
        } else {
            
            $_SESSION['authFailure'] = true;
            header("Location: ../login.php");
            exit;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
