<?php
session_start();
include('config.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user's name and membership info
$username = '';
$membership_id = null;

$stmt = $conn->prepare("SELECT name, membership_id FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $membership_id);
$stmt->fetch();
$stmt->close();

// Fetch membership plan details if exists
$membership_name = $membership_duration = $membership_price = '';
if ($membership_id) {
    $membership_sql = "SELECT Membership_Name, Duration, Price FROM memberships WHERE membership_id = ?";
    $membership_stmt = $conn->prepare($membership_sql);
    $membership_stmt->bind_param("i", $membership_id);
    $membership_stmt->execute();
    $membership_result = $membership_stmt->get_result();
    if ($membership_result->num_rows > 0) {
        $membership = $membership_result->fetch_assoc();
        $membership_name = $membership['Membership_Name'];
        $membership_duration = $membership['Duration'];
        $membership_price = $membership['Price'];
    }
    $membership_stmt->close();
}

// Handle delete request
$delete_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_workout_id'])) {
    $delete_id = intval($_POST['delete_workout_id']);
    $del_stmt = $conn->prepare("DELETE FROM user_workouts WHERE user_id = ? AND workout_id = ?");
    $del_stmt->bind_param("ii", $user_id, $delete_id);
    $del_stmt->execute();
    $del_stmt->close();
    $_SESSION['delete_success'] = true;
    header("Location: dashboard.php");
    exit();
}

// Show success message if workout was deleted
if (isset($_SESSION['delete_success'])) {
    $delete_message = "Workout removed successfully!";
    unset($_SESSION['delete_success']);
}

// Fetch added workouts with trainer info
$sql = "SELECT w.workout_id, w.workout_name, w.duration, w.schedule, t.name AS trainer_name, t.contact 
        FROM user_workouts uw 
        JOIN workouts w ON uw.workout_id = w.workout_id 
        JOIN trainers t ON w.trainer_id = t.trainer_id 
        WHERE uw.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - FitPro Gym</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a2e0e6da3c.js" crossorigin="anonymous"></script>
    <script>
        function confirmDelete(form) {
            if (confirm("Are you sure you want to remove this workout?")) {
                form.submit();
            }
        }
    </script>
</head>
<body class="bg-gray-100">

<!-- Header -->
<header class="bg-purple-700 text-white py-6 shadow-md">
    <div class="container mx-auto px-4 flex justify-between items-center">
        <h1 class="text-3xl font-bold flex items-center">
            <i class="fas fa-dumbbell mr-2"></i> FitPro Gym
        </h1>
        <a href="logout.php" class="bg-white text-purple-700 px-4 py-2 rounded hover:bg-purple-100 transition">Logout</a>
    </div>
</header>

<!-- Main Content -->
<main class="container mx-auto px-4 py-10">

    <!-- Welcome + Membership -->
    <div class="mb-8 p-6 bg-purple-100 rounded-lg shadow">
        <h2 class="text-3xl font-bold text-purple-700 mb-2">WELCOME BACK, <?= htmlspecialchars(strtoupper($username)) ?>!</h2>

        <?php if (!empty($membership_name)): ?>
            <div class="text-gray-700 text-lg">
                <p><strong>Membership Plan:</strong> <?= htmlspecialchars($membership_name) ?></p>
                <p><strong>Duration:</strong> <?= htmlspecialchars($membership_duration) ?></p>
                <p><strong>Price:</strong> ₹<?= htmlspecialchars($membership_price) ?></p>
            </div>
        <?php else: ?>
            <p class="text-gray-600">You do not have an active membership plan.</p>
        <?php endif; ?>
    </div>

    <!-- Workout Controls -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Your Workouts</h2>
        <a href="add_workout.php" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-800 transition">+ Add Workout</a>
    </div>

    <?php if (!empty($delete_message)): ?>
        <div class="mb-6 p-4 bg-green-100 text-green-800 rounded shadow">
            <?= htmlspecialchars($delete_message) ?>
        </div>
    <?php endif; ?>

    <!-- Workout Cards -->
<?php if ($result->num_rows > 0): ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-all duration-300">
                <h3 class="text-xl font-bold text-purple-700 mb-2"><?= htmlspecialchars($row['workout_name']) ?></h3>
                
                <p class="text-gray-600"><strong>Duration:</strong> <?= htmlspecialchars($row['duration']) ?></p>
                
                <p class="text-gray-600"><strong>Schedule:</strong> <?= htmlspecialchars($row['schedule']) ?></p>
                
                <p class="text-gray-600"><strong>Trainer:</strong> <?= htmlspecialchars($row['trainer_name']) ?></p>
                
                <p class="text-gray-600 mb-4"><strong>Contact:</strong> <?= htmlspecialchars($row['contact']) ?></p>
                
                <form method="POST" onsubmit="event.preventDefault(); confirmDelete(this);">
                    <input type="hidden" name="delete_workout_id" value="<?= $row['workout_id'] ?>">
                    <button type="submit" class="mt-3 bg-red-500 text-white px-3 py-1 rounded hover:bg-red-700 transition">
                        Remove
                    </button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <p class="text-gray-600">You haven't added any workouts yet.</p>
<?php endif; ?>

</main>

</body>
</html>

<?php $conn->close(); ?>
