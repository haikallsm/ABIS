<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login ABIS</title>
    <link href="/public/assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white p-8 rounded shadow w-full max-w-md">
    <h2 class="text-xl font-bold mb-6 text-center">Login ABIS</h2>

    <form method="post" action="login-process.php" class="space-y-4">
        <div>
            <label>Username</label>
            <input type="text" name="username" class="w-full border px-3 py-2 rounded" required>
        </div>

        <div>
            <label>Password</label>
            <input type="password" name="password" class="w-full border px-3 py-2 rounded" required>
        </div>

        <button class="w-full bg-blue-600 text-white py-2 rounded">
            Login
        </button>
    </form>
</div>

</body>
</html>
