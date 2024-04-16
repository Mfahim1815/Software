<?php
session_start();


if (!isset($_SESSION["userId"])) {
    header("Location: login.php");
    exit;
}

require_once './backend/dbconfig.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addTask'])) {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $taskName = $_POST['taskName'];
    $taskDescription = $_POST['taskDescription'];
    $dueDate = $_POST['dueDate'];
    $username = $_POST['username'];
    $taskstatus = "Pending";
    $accessLevel = 0;

    try {
        $userStmt = $conn->prepare("SELECT id FROM Users WHERE username = ?");
        $userStmt->execute([$username]);
        $user = $userStmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $userId = $user['id'];
            $taskStmt = $conn->prepare("INSERT INTO task_assignments (staff_member,task_status,task_name, task_description, due_date, user_id, access_level) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $taskStmt->execute([$username,$taskstatus,$taskName, $taskDescription, $dueDate, $userId,$accessLevel]);
            echo "<script>alert('Task added successfully');</script>";
        } else {
            echo "<script>alert('User does not exist');</script>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['taskId'])) {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $taskId = $_POST['taskId'];

    try {
        $stmt = $conn->prepare("DELETE FROM task_assignments WHERE id = ?");
        $stmt->execute([$taskId]);
        echo "<script>alert('Task deleted successfully');</script>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $taskNameFilter = isset($_POST['taskName']) ? $_POST['taskName'] : '';
    $taskStatusFilter = isset($_POST['taskStatus']) ? $_POST['taskStatus'] : '';
    $dueDateFilter = isset($_POST['dueDate']) ? $_POST['dueDate'] : '';

    $query = "SELECT * FROM task_assignments WHERE 1=1";
    $params = [];

    if (!empty($taskNameFilter)) {
        $query .= " AND task_name LIKE :taskName";
        $params[':taskName'] = "%$taskNameFilter%";
    }
    if (!empty($taskStatusFilter)) {
        $query .= " AND task_status = :taskStatus";
        $params[':taskStatus'] = $taskStatusFilter;
    }
    if (!empty($dueDateFilter)) {
        $query .= " AND due_date = :dueDate";
        $params[':dueDate'] = $dueDateFilter;
    }

    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
</head>
<script>
        function toggleModal() {
            document.getElementById('addTaskModal').classList.toggle('hidden');
        }
    </script>
<body class="bg-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg p-4">
            <div class="flex justify-between">
                <h2 class="text-2xl leading-6 font-medium text-gray-900">Admin Dashboard</h2>
                <button onclick="toggleModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Task</button>
               <a href="./backend/export.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Export</a>
                <form action="./backend/logout.php" method="post">
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Logout</button>
                </form>
            </div>
            <div class="mt-4 mb-4">
                <form action="admin_dashboard.php" method="post" class="flex flex-col md:flex-row md:items-end">
                    <div class="md:flex md:items-center mb-6 md:mb-0">
                        <div class="md:w-1/3">
                            <label for="taskName" class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                                Task Name:
                            </label>
                        </div>
                        <div class="md:w-2/3">
                            <input type="text" id="taskName" name="taskName" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500">
                        </div>
                    </div>
                    <div class="md:flex md:items-center mb-6 md:mb-0">
                        <div class="md:w-1/3">
                            <label for="taskStatus" class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                                Status:
                            </label>
                        </div>
                        <div class="md:w-2/3">
                            <select id="taskStatus" name="taskStatus" class="bg-gray-200 border-2 border-gray-200 rounded leading-tight focus:outline-none focus:bg-white focus:border-purple-500 text-gray-700 py-2 px-4">
                                <option value="">All</option>
                                <option value="Pending">Pending</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="md:flex md:items-center mb-6 md:mb-0">
                        <div class="md:w-1/3">
                            <label for="dueDate" class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                                Due Date:
                            </label>
                        </div>
                        <div class="md:w-2/3">
                            <input type="date" id="dueDate" name="dueDate" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500">
                        </div>
                    </div>
                    <div class="md:flex md:items-center">
                        <div class="md:w-1/3"></div>
                        <div class="md:w-2/3">
                            <button type="submit" class="shadow bg-purple-500 hover:bg-purple-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded" type="button">
                                Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Task Name
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Description
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Due Date
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Progress %
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tasks as $task): ?>
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <?php echo htmlspecialchars($task['task_name']); ?>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <?php echo htmlspecialchars($task['task_description']); ?>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <?php echo htmlspecialchars($task['due_date']); ?>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <?php echo htmlspecialchars($task['progress']); ?> %
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <?php echo htmlspecialchars($task['task_status']); ?>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <form action="admin_dashboard.php" method="post">
                                <input type="hidden" name="taskId" value="<?php echo htmlspecialchars($task['id']); ?>">
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div id="addTaskModal" class="hidden fixed z-10 inset-0 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                    <form action="admin_dashboard.php" method="post" class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                                    Add New Task
                                </h3>
                                <div class="mt-2">
                                    <div class="mt-2">
                                        <input type="text" name="taskName" placeholder="Task Name" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                    <div class="mt-2">
                                        <textarea name="taskDescription" placeholder="Description" rows="3" required class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"></textarea>
                                    </div>
                                    <div class="mt-2">
                                        <input type="date" name="dueDate" placeholder="Due Date" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                    <div class="mt-2">
                                        <input type="text" name="username" placeholder="Username" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" name="addTask" value="1" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Add Task
                            </button>
                            <button type="button" onclick="toggleModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</body>
</html>
