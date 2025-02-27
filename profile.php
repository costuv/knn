<?php
include 'config.php';
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$requested_id = isset($_GET['id']) ? (int)$_GET['id'] : $_SESSION['user_id'];
if($requested_id !== $_SESSION['user_id'] && $_SESSION['role'] !== 'admin') {
    header("Location: profile.php");
    exit();
}
$query = "SELECT id, username, email, created_at FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $requested_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if(!$user) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Profile - KNN</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold mb-6">User Profile</h1>
            <div class="space-y-4">
                <div>
                    <label class="font-bold">Username:</label>
                    <p><?php echo htmlspecialchars($user['username']); ?></p>
                </div>
                <div>
                    <label class="font-bold">Email:</label>
                    <p><?php echo htmlspecialchars($user['email']); ?></p>
                </div>
                <div>
                    <label class="font-bold">Member Since:</label>
                    <p><?php echo date('F j, Y', strtotime($user['created_at'])); ?></p>
                </div>
            </div>
            <div class="mt-6">
                <a href="index.php" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Back to Home</a>
            </div>
        </div>
    </div>
</body>
</html>
