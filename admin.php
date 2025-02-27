<?php
include 'config.php';
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if(isset($_POST['add_post'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $category = $conn->real_escape_string($_POST['category']);
    $is_breaking = isset($_POST['is_breaking']) ? 1 : 0;
    
    $query = "INSERT INTO posts (title, content, category, is_breaking) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $title, $content, $category, $is_breaking);
    $stmt->execute();
    $stmt->close();
}

if(isset($_POST['delete_post'])) {
    $id = $_POST['post_id'];
    $query = "DELETE FROM posts WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>KNN Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <nav class="bg-red-600 text-white px-4 py-3">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">KNN Admin</h1>
            <div class="space-x-4">
                <a href="index.php" class="hover:text-gray-200">View Site</a>
                <a href="logout.php" class="hover:text-gray-200">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8 flex-grow">
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold mb-6 text-red-600">Create New Article</h2>
            <form method="post">
                <div class="mb-4">
                    <input type="text" name="title" placeholder="Article Title" 
                           class="w-full p-2 border rounded focus:border-blue-500 focus:outline-none" required>
                </div>
                <div class="mb-4">
                    <select name="category" class="w-full p-2 border rounded focus:border-blue-500 focus:outline-none" required>
                        <option value="">Select Category</option>
                        <?php
                        $categories = ['World', 'Technology', 'Sports', 'Entertainment', 'Politics'];
                        foreach($categories as $category) {
                            echo "<option value='$category'>$category</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-4">
                    <textarea name="content" placeholder="Article Content" 
                              class="w-full p-2 border rounded h-48 focus:border-blue-500 focus:outline-none" required></textarea>
                </div>
                <div class="mb-4">
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="is_breaking" class="form-checkbox text-red-600 rounded">
                        <span class="text-gray-700">Mark as Breaking News</span>
                    </label>
                </div>
                <button type="submit" name="add_post" 
                        class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                    Publish Article
                </button>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold mb-6 text-red-600">Manage Articles</h2>
            <div class="space-y-4">
                <?php
                $query = "SELECT * FROM posts ORDER BY created_at DESC";
                $result = $conn->query($query);
                
                while($row = $result->fetch_assoc()) {
                    echo '<div class="border-b pb-4 flex justify-between items-center">';
                    echo '<div class="flex-grow">';
                    echo '<h3 class="text-xl font-semibold">' . htmlspecialchars($row['title']) . '</h3>';
                    echo '<p class="text-gray-600 text-sm">' . $row['created_at'] . '</p>';
                    echo '</div>';
                    echo '<div class="flex space-x-2">';
                    echo '<a href="edit.php?id=' . $row['id'] . '" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Edit</a>';
                    echo '<form method="post" class="inline">';
                    echo '<input type="hidden" name="post_id" value="' . $row['id'] . '">';
                    echo '<button type="submit" name="delete_post" class="bg-gray-600 text-white px-3 py-1 rounded hover:bg-gray-700">Delete</button>';
                    echo '</form>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
