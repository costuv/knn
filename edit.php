<?php
include 'config.php';
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if(isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    $query = "SELECT * FROM posts WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();
    $stmt->close();
}

if(isset($_POST['update_post'])) {
    $id = $conn->real_escape_string($_POST['post_id']);
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $category = $conn->real_escape_string($_POST['category']);
    $is_breaking = isset($_POST['is_breaking']) ? 1 : 0;
    
    $query = "UPDATE posts SET title=?, content=?, category=?, is_breaking=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssii", $title, $content, $category, $is_breaking, $id);
    if($stmt->execute()) {
        header("Location: admin.php");
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Article - KNN Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-red-600 text-white px-4 py-3">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">Edit Article</h1>
            <div class="space-x-4">
                <a href="admin.php" class="hover:text-gray-200">Back to Admin</a>
                <a href="index.php" class="hover:text-gray-200">View Site</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <form method="post">
                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Title</label>
                    <input type="text" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" 
                           class="w-full p-2 border rounded focus:border-blue-500 focus:outline-none" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Category</label>
                    <select name="category" class="w-full p-2 border rounded focus:border-blue-500 focus:outline-none" required>
                        <?php
                        $categories = ['World', 'Technology', 'Sports', 'Entertainment', 'Politics'];
                        foreach($categories as $category) {
                            $selected = ($category == $post['category']) ? 'selected' : '';
                            echo "<option value='$category' $selected>$category</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Content</label>
                    <textarea name="content" class="w-full p-2 border rounded h-48 focus:border-blue-500 focus:outline-none" required>
                        <?php echo htmlspecialchars($post['content']); ?>
                    </textarea>
                </div>

                <div class="mb-4">
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="is_breaking" class="form-checkbox text-red-600 rounded"
                               <?php echo $post['is_breaking'] ? 'checked' : ''; ?>>
                        <span class="text-gray-700">Mark as Breaking News</span>
                    </label>
                </div>

                <div class="flex justify-between">
                    <button type="submit" name="update_post" 
                            class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700">
                        Update Article
                    </button>
                    <a href="admin.php" class="bg-gray-600 text-white px-6 py-2 rounded hover:bg-gray-700">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
