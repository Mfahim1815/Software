<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>No Tasks Assigned</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            text-align: center;
        }
        h2 {
            color: #333;
        }
        .login-button {
            margin-top: 20px;
        }
                input[type="button"] {
            width: 100%;
            padding: 10px;
            background-color: #006bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="button"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>No Tasks Assigned</h2>
        <p>No tasks have been assigned at the moment.</p>
        <div class="login-button">
            <input type="button" value="Back" onclick="window.location.href='login.jsp'">
        </div>
    </div>
</body>
</html>
