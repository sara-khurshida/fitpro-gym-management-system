<?php
session_start();
include('config.php');

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $stmt = $conn->prepare("SELECT user_id, name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($user_id, $name, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION["user_id"] = $user_id;
            $_SESSION["name"] = $name;
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No user found with that email.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - FitPro Gym</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-100">

<!-- Header -->
<header class="bg-white shadow-md py-4">
    <div class="container mx-auto px-4 flex justify-between items-center">
        <a href="index.php" class="text-2xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-dumbbell text-purple-600 mr-2"></i> FitPro Gym
        </a>
<nav class="hidden md:flex space-x-6 text-gray-700 font-medium">
    <a href="index.php" class="hover:text-purple-600 text-xl font-bold flex items-center">
        <i class="fas fa-home mr-2"></i> Home
    </a>
</nav>

    </div>
</header>

<!-- Login Form -->
<main class="flex items-center justify-center min-h-[calc(100vh-80px)] px-4">
    <div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md">
        <div class="flex items-center justify-center mb-6">
            <i class="fas fa-user-circle text-purple-600 text-3xl mr-2"></i>
            <h1 class="text-2xl font-bold text-gray-800">User Login</h1>
        </div>

        <?php if (!empty($error)): ?>
            <div class="mb-4 bg-red-100 text-red-700 px-4 py-2 rounded">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-700 font-medium mb-1">Email</label>
                <input type="email" name="email" required
                       class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-purple-600">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Password</label>
                <input type="password" name="password" required
                       class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-purple-600">
            </div>

            <button type="submit"
                    class="w-full bg-purple-600 text-white py-2 rounded hover:bg-purple-700 transition font-semibold">
                Login
            </button>
        </form>

        <div class="mt-4 text-center text-sm text-gray-600">
            New user? <a href="register.php" class="text-purple-600 hover:underline font-medium">Register here</a>
        </div>
    </div>
</main>

</body>
</html>
