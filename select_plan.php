<?php
session_start();
include('config.php');

// Fetch membership plans
$plans = [];
$sql = "SELECT Membership_ID, Membership_Name, Duration, Price FROM memberships";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $plans[] = $row;
    }
}

// Handle plan selection
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['membership_id'])) {
    $_SESSION['membership_id'] = $_POST['membership_id'];
    // Redirect back to register.php, keeping user data saved in session
    header("Location: register.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Membership Plan - FitPro Gym</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a2e0e6da3c.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@600&display=swap" rel="stylesheet">
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
        .bounce-hover:hover {
            animation: bounce 0.6s;
        }
        @keyframes bounce {
            0%, 100% {transform: translateY(0);}
            50% {transform: translateY(-8px);}
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

<main class="container mx-auto px-6 py-10 fade-in">
    <h2 class="text-4xl font-bold text-center text-gray-800 mb-10">🎯 Choose Your Membership Plan</h2>

    <div class="grid md:grid-cols-3 sm:grid-cols-2 gap-8">
        <?php foreach ($plans as $plan): ?>
            <form method="POST" class="bg-white shadow-xl rounded-lg p-6 border border-purple-200 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                <input type="hidden" name="membership_id" value="<?= htmlspecialchars($plan['Membership_ID']) ?>">
                <div class="text-center">
                    <h3 class="text-2xl font-bold text-purple-700 mb-2"><?= htmlspecialchars($plan['Membership_Name']) ?></h3>
                    <p class="text-lg text-gray-600 mb-1">⏳ Duration: <span class="font-semibold"><?= htmlspecialchars($plan['Duration']) ?></span></p>
                    <p class="text-lg text-gray-600 mb-4">💰 Price: <span class="font-semibold">Rs.<?= htmlspecialchars($plan['Price']) ?></span></p>
                    <button type="submit" class="mt-4 bg-purple-600 text-white px-5 py-2 rounded hover:bg-purple-800 transition-all duration-300 ease-in-out bounce-hover">
                        Select Plan
                    </button>
                </div>
            </form>
        <?php endforeach; ?>
    </div>

    <div class="mt-10 text-center">
        <a href="register.php" class="text-purple-600 underline hover:text-purple-800">Back to Registration</a>
    </div>
</main>
</body>
</html>
