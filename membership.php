<?php
include('config.php');

// Fetch membership plans without trainer join
$sql = "SELECT Membership_Name, Duration, Price FROM memberships";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Membership Plans - FitPro Gym</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a2e0e6da3c.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@600&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
       <style>
        body {
            font-family: 'Roboto Slab', serif;
            background-color: #f0f4f8;
        }
        .fade-in {
            animation: fadeInUp 1s ease-out both;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .bounce-hover:hover {
            animation: bounce 0.6s;
        }
        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-8px);
            }
        }
    </style>
</head>
<body>
   <!-- Header / Navbar -->
    <header class="bg-white shadow">
        <div class="container mx-auto flex justify-between items-center p-4">
            <a href="index.php" class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-dumbbell mr-2 text-purple-600"></i> FitPro Gym
            </a>
            <nav class="hidden md:flex space-x-6 text-gray-700 font-medium">
                <a href="index.php" class="hover:text-purple-600">Home</a>
                <a href="classes.php" class="hover:text-purple-600 ">Classes</a>
                <a href="trainers.php" class="hover:text-purple-600">Trainers</a>
                <a href="membership.php" class="hover:text-purple-600">Membership</a>
                <a href="contact.php" class="hover:text-purple-600">Contact</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="logout.php" class="hover:text-purple-600"><i class="fas fa-sign-out-alt mr-1"></i>Logout</a>
                <?php else: ?>
                    <a href="login.php" class="hover:text-purple-600"><i class="fas fa-user-circle mr-1"></i>Login</a>
                <?php endif; ?>
            </nav>
            <button class="md:hidden text-gray-700 focus:outline-none" id="menu-btn">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>
        <div class="md:hidden hidden bg-white shadow" id="mobileMenu">
            <a href="index.php" class="hover:text-purple-600 ">Home</a>
            <a href="classes.php" class="hover:text-purple-600">Classes</a>
            <a href="trainers.php" class="hover:text-purple-600 font-bold ">Trainers</a>
            <a href="membership.php" class="hover:text-purple-600 ">Membership</a>
            <a href="contact.php" class="hover:text-purple-600 ">Contact</a>
            <a href="login.php" class="hover:text-purple-600 "><i class="fas fa-user-circle mr-1"></i>Login</a>
        </div>
    </header>

<main class="container mx-auto px-6 py-10 fade-in">
    <h2 class="text-4xl font-bold text-center text-gray-800 mb-10">Membership Plans</h2>

    <div class="grid md:grid-cols-3 sm:grid-cols-2 gap-8">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="bg-white shadow-xl rounded-lg p-6 border border-purple-200 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                <h3 class="text-2xl font-bold text-purple-700 mb-2"><?= htmlspecialchars($row['Membership_Name']) ?></h3>
                <p class="text-lg text-gray-600 mb-1">⏳ Duration: <span class="font-semibold"><?= htmlspecialchars($row['Duration']) ?></span></p>
                <p class="text-lg text-gray-600 mb-4">💰 Price: <span class="font-semibold">Rs.<?= htmlspecialchars($row['Price']) ?></span></p>
            </div>
        <?php endwhile; ?>
    </div>
</main>

<!-- Footer -->
<footer class="bg-gray-800 text-white py-8 mt-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <h3 class="text-xl font-bold mb-4 flex items-center">
                    <i class="fas fa-dumbbell mr-2"></i> FitPro Gym
                </h3>
                <p class="text-gray-400">Helping you achieve your fitness goals since 2025.</p>
                <div class="flex space-x-4 mt-4">
                    <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                <ul class="space-y-2">
                    <li><a href="index.php" class="text-gray-400 hover:text-white">Home</a></li>
                    <li><a href="classes.php" class="text-gray-400 hover:text-white">Classes</a></li>
                    <li><a href="trainers.php" class="text-gray-400 hover:text-white">Trainers</a></li>
                    <li><a href="membership.php" class="text-gray-400 hover:text-white">Membership</a></li>
                    <li><a href="contact.php" class="text-gray-400 hover:text-white">Contact</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4">Contact Us</h4>
                <ul class="space-y-2 text-gray-400">
                    <li class="flex items-center"><i class="fas fa-map-marker-alt mr-2"></i> 123 Fitness St, City</li>
                    <li class="flex items-center"><i class="fas fa-phone-alt mr-2"></i> (555) 123-4567</li>
                    <li class="flex items-center"><i class="fas fa-envelope mr-2"></i> info@fitprogym.com</li>
                </ul>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4">Opening Hours</h4>
                <ul class="space-y-2 text-gray-400">
                    <li>Monday - Friday: 5:00 AM - 11:00 PM</li>
                    <li>Saturday: 6:00 AM - 9:00 PM</li>
                    <li>Sunday: 7:00 AM - 8:00 PM</li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-700 mt-8 pt-6 text-center text-gray-400">
            <p>&copy; 2025 FitPro Gym. All rights reserved.</p>
        </div>
    </div>
</footer>
</body>
</html>

<?php $conn->close(); ?>
