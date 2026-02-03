
<?php
session_start();
include('config.php');

// Handle "Choose Membership Plan" button - save form data then redirect to select_plan.php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'choose_plan') {
    // Save current form data to session
    $_SESSION['reg_name'] = $_POST['name'] ?? '';
    $_SESSION['reg_email'] = $_POST['email'] ?? '';
    $_SESSION['reg_password'] = $_POST['password'] ?? '';
    // Keep membership_id if selected already
    $_SESSION['membership_id'] = $_SESSION['membership_id'] ?? null;

    header("Location: select_plan.php");
    exit();
}

// Handle final registration submit
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['action'])) {
    // Validate form inputs including membership plan selection
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $membership_id = $_SESSION['membership_id'] ?? null;

    $errors = [];

    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required.";
    }
    if (empty($password)) {
        $errors[] = "Password is required.";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }
    if (!$membership_id) {
        $errors[] = "Please select a membership plan.";
    }

    if (empty($errors)) {
        // Hash password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Insert into database
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, membership_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $name, $email, $password_hash, $membership_id);

        if ($stmt->execute()) {
            // Clear registration session data on success
            unset($_SESSION['reg_name'], $_SESSION['reg_email'], $_SESSION['reg_password'], $_SESSION['membership_id']);
            $_SESSION['success_message'] = "Registration successful! You can now login.";
            header("Location: login.php");
            exit();
        } else {
            $errors[] = "Error during registration: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Load form values from session or empty if first visit
$name = $_SESSION['reg_name'] ?? '';
$email = $_SESSION['reg_email'] ?? '';
$password = $_SESSION['reg_password'] ?? '';
$confirm_password = $password; // keep confirm same as password for UX

// Get selected membership plan name for display
$selectedPlanId = $_SESSION['membership_id'] ?? null;
$membershipName = '';

if ($selectedPlanId) {
    $stmt = $conn->prepare("SELECT Membership_Name FROM memberships WHERE Membership_ID = ?");
    $stmt->bind_param("i", $selectedPlanId);
    $stmt->execute();
    $stmt->bind_result($membershipName);
    $stmt->fetch();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Register - FitPro Gym</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a2e0e6da3c.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@600&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Roboto Slab', serif;
            background-color: #f0f4f8;
        }
        .fade-in {
            animation: fadeInUp 1s ease-out both;
        }
        @keyframes fadeInUp {
            from {opacity: 0; transform: translateY(20px);}
            to {opacity: 1; transform: translateY(0);}
        }
        .error { color: #dc2626; }
        .bounce-hover:hover { animation: bounce 0.6s; }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
    </style>
</head>
<body>
<header class="bg-purple-700 text-white py-8 shadow-lg">
    <div class="container mx-auto flex flex-col items-center justify-center px-6 space-y-2">
        <div class="text-5xl"><i class="fas fa-dumbbell"></i></div>
        <h1 class="text-3xl font-bold tracking-wider">FitPro Gym</h1>
    </div>
</header>

<main class="container mx-auto px-6 py-10 fade-in max-w-lg">
    <h2 class="text-4xl font-bold text-center text-gray-800 mb-10">Create Your Account</h2>

    <?php if (!empty($errors)): ?>
        <div class="mb-6 p-4 bg-red-100 border border-red-400 rounded text-red-700">
            <ul class="list-disc list-inside">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="register.php" class="bg-white shadow-xl rounded-lg p-8 border border-purple-200">
        <label class="block mb-3 font-semibold text-gray-700" for="name">Name</label>
        <input
            class="w-full p-3 mb-6 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-600"
            type="text"
            id="name"
            name="name"
            value="<?= htmlspecialchars($name) ?>"
            required
        />

        <label class="block mb-3 font-semibold text-gray-700" for="email">Email</label>
        <input
            class="w-full p-3 mb-6 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-600"
            type="email"
            id="email"
            name="email"
            value="<?= htmlspecialchars($email) ?>"
            required
        />

        <label class="block mb-3 font-semibold text-gray-700" for="password">Password</label>
        <input
            class="w-full p-3 mb-6 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-600"
            type="password"
            id="password"
            name="password"
            value="<?= htmlspecialchars($password) ?>"
            required
        />
        <label class="block mb-3 font-semibold text-gray-700" for="confirm_password">Confirm Password</label>
<input
    class="w-full p-3 mb-6 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-600"
    type="password"
    id="confirm_password"
    name="confirm_password"
    required
/>


        <div class="mb-6">
            <label class="block font-semibold mb-1">Membership Plan</label>
            <?php if ($selectedPlanId && $membershipName): ?>
                <p class="text-green-600 font-medium"><?= htmlspecialchars($membershipName) ?></p>
            <?php else: ?>
                <p class="text-red-500 font-medium">No plan selected</p>
            <?php endif; ?>

            <button
                type="submit"
                name="action"
                value="choose_plan"
                class="text-purple-600 underline text-sm mt-2 hover:text-purple-800"
            >
                Choose a Membership Plan
            </button>
        </div>

        <button
            type="submit"
            class="w-full bg-purple-700 text-white py-3 rounded font-bold hover:bg-purple-900 transition-all duration-300 bounce-hover"
        >
            Register
        </button>
        <p class="mt-4 text-sm text-center text-gray-600">
    Already registered?
    <a href="login.php" class="text-purple-600 hover:underline font-semibold">Login here</a>
</p>

    </form>
</main>
<script>
document.querySelector("form").addEventListener("submit", function (e) {
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirm_password").value;

    if (password !== confirmPassword) {
        e.preventDefault();
        alert("Passwords do not match.");
    }
});
</script>

</body>
</html>
