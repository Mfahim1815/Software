<?php
session_start();

if (!isset($_SESSION["userId"])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>No Tasks Assigned</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold mb-2">No Tasks Assigned</h2>
            <p class="mb-4">You currently have no tasks assigned to you.</p>
            <a href="./backend/logout.php" class="inline-block bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700">Go to Login Page</a>
        </div>
    </div>
</body>
</html>
