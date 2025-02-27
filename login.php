<?php
include 'config.php';
session_start();

$error = '';

if(isset($_POST['login'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if(password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            header("Location: index.php");
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "Username not found!";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>KNN - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-pattern {
            background-color: #f0f2f5;
            background-image: 
                radial-gradient(at 47% 33%, hsl(0, 100%, 93%) 0, transparent 59%), 
                radial-gradient(at 82% 65%, hsl(0, 80%, 96%) 0, transparent 55%);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="bg-pattern min-h-screen flex items-center justify-center">
    <div class="glass-effect p-8 rounded-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-red-600">Login to KNN</h2>
        <?php if($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form method="post" class="space-y-4">
            <div>
                <input type="text" name="username" placeholder="Username" required
                       class="w-full p-2 border rounded focus:border-red-500 focus:outline-none">
            </div>
            <div>
                <input type="password" name="password" placeholder="Password" required
                       class="w-full p-2 border rounded focus:border-red-500 focus:outline-none">
            </div>
            <button type="submit" name="login" 
                    class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700 transition">
                Login
            </button>
        </form>
        <p class="mt-4 text-center text-gray-600">
            Don't have an account? 
            <a href="register.php" class="text-red-600 hover:text-red-700">Register</a>
        </p>
    </div>
</body>
</html>
