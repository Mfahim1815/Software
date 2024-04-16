<?php
session_start();

if (!isset($_SESSION["userId"])) {
    header("Location: login.php");
    exit;
}

require_once './backend/dbconfig.php';

$taskStatus = $progress = "";
$taskId = isset($_GET['taskId']) ? $_GET['taskId'] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $taskId = $_POST['taskId'];
    $taskStatus = $_POST['taskStatus'];
    $progress = $_POST['progress'];
    $date = date('Y-m-d');

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "UPDATE task_assignments SET task_status = ?, progress = ?, review_date = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$taskStatus, $progress, $date, $taskId]);

        header("Location: assigned_tasks.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    if (!empty($taskId)) {
        try {
            $conn = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT task_status, progress FROM task_assignments WHERE id = ?");
            $stmt->execute([$taskId]);
            $task = $stmt->fetch(PDO::FETCH_ASSOC);

            $taskStatus = $task['task_status'];
            $progress = $task['progress'];
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Task</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-xl mx-auto px-4 py-2 mt-6 bg-white shadow-md">
        <h2 class="text-lg font-bold mb-4">Edit Task Status and Progress</h2>
        <form action="edit_task.php" method="post" class="space-y-4">
            <input type="hidden" name="taskId" value="<?php echo htmlspecialchars($taskId); ?>">
            <div>
                <label for="taskStatus" class="block">Status</label>
                <select name="taskStatus" required class="w-full p-2 border border-gray-300 rounded">
                    <option value="Pending" <?php echo ($taskStatus == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                    <option value="In Progress" <?php echo ($taskStatus == 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                </select>
            </div>

            <div>
                <label for="progress" class="block">Progress (%)</label>
                <input type="number" name="progress" value="<?php echo htmlspecialchars($progress); ?>" required min="0" max="100" class="w-full p-2 border border-gray-300 rounded">
            </div>

            <div>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">Update Task</button>
            </div>
        </form>
    </div>
</body>
</html>
