<?php
include 'config.php';
header('Content-Type: application/json');

if(isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    $query = "SELECT * FROM posts WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($article = $result->fetch_assoc()) {
        echo json_encode([
            'success' => true,
            'article' => [
                'title' => $article['title'],
                'content' => $article['content'],
                'category' => $article['category'],
                'date' => date('F j, Y', strtotime($article['created_at'])),
                'is_breaking' => $article['is_breaking']
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Article not found']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'No article ID provided']);
}
