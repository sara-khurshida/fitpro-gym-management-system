<?php
session_start();
include('config.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['workout_id'])) {
    $workout_id = intval($_POST['workout_id']);

    // Check if already added
    $check_sql = "SELECT * FROM user_workouts WHERE user_id = ? AND workout_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ii", $user_id, $workout_id);
    $stmt->execute();
    $result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Insert into user_workouts
    $insert_sql = "INSERT INTO user_workouts (user_id, workout_id) VALUES (?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("ii", $user_id, $workout_id);
    $insert_stmt->execute();
    // Redirect with success
    header("Location: dashboard.php?added=1");
    exit();
} else {
    // Redirect with duplicate message (optional)
    header("Location: dashboard.php?added=duplicate");
    exit();
}

}

// Fetch all workouts with trainer info
$sql = "SELECT w.workout_id, w.workout_name, w.duration, w.schedule, t.name AS trainer_name FROM workouts w JOIN trainers t ON w.trainer_id = t.trainer_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Workout - FitPro Gym</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
  <header class="bg-white shadow">
    <div class="container mx-auto flex justify-between items-center p-4">
      <a href="dashboard.php" class="text-2xl font-bold text-gray-800 flex items-center">
        <i class="fas fa-dumbbell mr-2 text-purple-600"></i> FitPro Gym
      </a>
    </div>
  </header>

  <main class="container mx-auto p-6">
    <h1 class="text-4xl font-bold text-purple-700 mb-6">Add a Workout</h1>

    <?php if (isset($message)): ?>
      <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
        <?php echo htmlspecialchars($message); ?>
      </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
  <?php while ($row = $result->fetch_assoc()): ?>
    <form method="POST" class="bg-white rounded-2xl shadow-md p-6 max-w-sm transform transition duration-300 hover:shadow-lg hover:scale-105">
      <input type="hidden" name="workout_id" value="<?php echo $row['workout_id']; ?>">

      <h3 class="text-xl font-bold text-purple-700 mb-2"><?php echo htmlspecialchars($row['workout_name']); ?></h3>
      
      <p class="text-gray-600 mb-1"><strong>Duration:</strong> <?php echo htmlspecialchars($row['duration']); ?></p>
      
      <p class="text-gray-600 mb-1"><strong>Schedule:</strong> <?php echo htmlspecialchars($row['schedule']); ?></p>
      
      <p class="text-gray-600 mb-4"><strong>Trainer:</strong> <?php echo htmlspecialchars($row['trainer_name']); ?></p>

      <!-- Add Workout Button -->
      <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 transition">
        Add Workout
      </button>
    </form>
  <?php endwhile; ?>
</div>

  </main>
</body>
</html>

<?php $conn->close(); ?>
