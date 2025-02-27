<?php
include 'config.php';
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>About Us - KNN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <header class="bg-white shadow-md">
        <div class="container mx-auto py-4 px-4">
            <div class="flex justify-between items-center">
                <a href="index.php" class="flex items-center">
                    <div>
                        <h1 class="text-4xl font-bold text-red-600">KNN</h1>
                        <p class="text-gray-600">Kaustuv News Network</p>
                    </div>
                </a>
                <div class="flex space-x-4">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="admin.php" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">Admin Panel</a>
                        <a href="logout.php" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">Login</a>
                        <a href="register.php" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>
    <main class="container mx-auto px-4 py-8 flex-grow">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl font-bold text-gray-800 mb-8">About KNN</h1>
            <section class="bg-white rounded-lg shadow-lg p-8 mb-8">
                <h2 class="text-2xl font-bold text-red-600 mb-4">Our Mission</h2>
                <p class="text-gray-600 leading-relaxed mb-4">
                    Kaustuv News Network (KNN) is dedicated to delivering accurate, timely, and unbiased news to our readers. 
                    Our mission is to inform, educate, and empower our audience with comprehensive coverage of local and global events.
                </p>
            </section>
            <section class="bg-white rounded-lg shadow-lg p-8 mb-8">
                <h2 class="text-2xl font-bold text-red-600 mb-6">Our Core Values</h2>
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-check text-red-600 text-2xl"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-2">Accuracy</h3>
                        <p class="text-gray-600">Committed to factual and precise reporting</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-balance-scale text-red-600 text-2xl"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-2">Integrity</h3>
                        <p class="text-gray-600">Unbiased and honest journalism</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-bolt text-red-600 text-2xl"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-2">Speed</h3>
                        <p class="text-gray-600">Quick delivery of breaking news</p>
                    </div>
                </div>
            </section>
            <section class="bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-2xl font-bold text-red-600 mb-6">Contact Us</h2>
                <div class="grid md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="font-bold text-gray-800 mb-4">Get in Touch</h3>
                        <ul class="space-y-3 text-gray-600">
                            <li class="flex items-center">
                                <i class="fas fa-envelope w-6 text-red-600"></i>
                                <span class="ml-2">kaustuvdhungel@gmail.com</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-phone w-6 text-red-600"></i>
                                <span class="ml-2">+977 9845660999</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-map-marker-alt w-6 text-red-600"></i>
                                <span class="ml-2">Kathmandu, Nepal</span>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 mb-4">Follow Us</h3>
                        <div class="flex space-x-4">
                            <a href="https://github.com/costuv" class="bg-red-100 p-3 rounded-full text-red-600 hover:bg-red-200 transition">
                                <i class="fab fa-github"></i>
                            </a>
                            <a href="https://youtube.com/@kaustuvdhungel" class="bg-red-100 p-3 rounded-full text-red-600 hover:bg-red-200 transition">
                                <i class="fab fa-youtube"></i>
                            </a>
                            <a href="https://instagram.com/costuvdhungel" class="bg-red-100 p-3 rounded-full text-red-600 hover:bg-red-200 transition">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
    <footer class="bg-gray-800 text-white py-8 mt-auto">
        <div class="container mx-auto px-4">
            <div class="text-center">
                <p class="text-gray-400">&copy; <?php echo date('Y'); ?> Kaustuv News Network. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
