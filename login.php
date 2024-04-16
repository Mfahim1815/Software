<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.1/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-200 flex justify-center items-center min-h-screen">
    <div class="w-full max-w-xs">
        <form action="./backend/login.php" method="post" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <h1 class="text-lg font-bold text-center mb-4">Login Page</h1>
            <?php if (isset($_SESSION['authFailure'])): ?>
                <p class="text-red-500 text-sm text-center">Incorrect email or password. Please try again.</p>
            <?php unset($_SESSION['authFailure']); endif; ?>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                <input type="text" id="email" name="email" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password:</label>
                <input type="password" id="password" name="password" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="flex items-center justify-between">
                <input type="submit" value="Login" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline cursor-pointer">
                <button onclick="window.location.href='register.php'" type="button" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Register
                </button>
            </div>
        </form>
    </div>
</body>
</html>
