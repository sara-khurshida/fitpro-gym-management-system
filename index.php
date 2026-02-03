
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Home - FitPro Gym</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a2e0e6da3c.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@600&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
</head>

<body class="bg-gray-100 text-gray-900">

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
            <a href="trainers.php" class="hover:text-purple-600  ">Trainers</a>
            <a href="membership.php" class="hover:text-purple-600 ">Membership</a>
            <a href="contact.php" class="hover:text-purple-600 ">Contact</a>
            <a href="login.php" class="hover:text-purple-600 "><i class="fas fa-user-circle mr-1"></i>Login</a>
        </div>
    </header>

    <!-- Main Content -->
<!--<main class="container mx-auto p-6">
    <section class="mb-12 pt-10"> 
    <h1 class="text-6xl font-extrabold leading-tight text-purple-700">
      Welcome to FitPro Gym
    </h1>
<p class="text-xl md:text-2xl font-bold text-gray-600 mb-8 max-w-xl animate-fade-in">
  Your journey to fitness starts here! Explore our classes, meet our trainers, and find the perfect membership plan.
</p>
  </section> -->
  <main class="container mx-auto p-6">
  <section class="mb-12 pt-10">
    <h1 class="text-6xl font-extrabold leading-tight text-purple-700">
      Welcome to FitPro Gym
    </h1>

    <p class="text-xl md:text-2xl font-bold text-gray-600 mb-8 max-w-xl animate-fade-in">
      Your journey to fitness starts here! Explore our classes, meet our trainers, and find the perfect membership plan.
    </p>

    <!-- Bigger Dumbbell Icon with Bounce -->
    <div class="mt-4">
      <i class="fas fa-dumbbell text-8xl text-purple-700 hover:text-purple-900 transition duration-500 hover:animate-bounce cursor-pointer"></i>
    </div>
  </section>
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
                        <li class="flex items-center"><i class="fas fa-phone-alt mr-2"></i> +91 8431984564 </li>
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

    <script>
        // Mobile menu toggle
        document.getElementById('menu-btn').addEventListener('click', function () {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        });
    </script>
<style>
  @keyframes fadeIn {
    0% {
      opacity: 0;
      transform: translateY(20px);
    }
    100% {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .animate-fade-in {
    animation: fadeIn 1s ease-out forwards;
  }
</style>

</body>

</html>
