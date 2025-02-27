<?php
include 'config.php';

$error = '';
$success = '';

if(isset($_POST['register'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    if(strlen($username) < 3) {
        $error = "Username must be at least 3 characters long!";
    }
    else if(!$email) {
        $error = "Please enter a valid email address!";
    }
    else if(strlen($password) < 6) {
        $error = "Password must be at least 6 characters long!";
    }
    else {
        $check_query = "SELECT * FROM users WHERE username=? OR email=?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("ss", $username, $email);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if($result->num_rows > 0) {
            $existing_user = $result->fetch_assoc();
            if($existing_user['username'] === $username) {
                $error = "Username already exists!";
            } else {
                $error = "Email already registered!";
            }
        } else {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sss", $username, $email, $password);
            
            if($stmt->execute()) {
                $success = "Registration successful! You can now login.";
            } else {
                $error = "Registration failed! Please try again.";
            }
            $stmt->close();
        }
        $check_stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>KNN - Register</title>
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
        <h2 class="text-2xl font-bold mb-6 text-center text-red-600">Register for KNN</h2>
        <?php if($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <?php if($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php echo $success; ?>
                <p class="mt-2">
                    <a href="login.php" class="text-green-700 font-bold hover:underline">Click here to login</a>
                </p>
            </div>
        <?php endif; ?>
        <form method="post" class="space-y-4">
            <div>
                <input type="text" name="username" placeholder="Username" required
                       class="w-full p-2 border rounded focus:border-red-500 focus:outline-none">
            </div>
            <div>
                <input type="email" name="email" placeholder="Email Address" required
                       class="w-full p-2 border rounded focus:border-red-500 focus:outline-none">
            </div>
            <div>
                <input type="password" name="password" placeholder="Password" required
                       class="w-full p-2 border rounded focus:border-red-500 focus:outline-none">
            </div>
            <button type="submit" name="register" 
                    class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700 transition">
                Register
            </button>
        </form>
        <p class="mt-4 text-center text-gray-600">
            Already have an account? 
            <a href="login.php" class="text-red-600 hover:text-red-700">Login</a>
        </p>
    </div>
</body>
</html>
