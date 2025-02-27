<?php
include 'config.php';
header('Content-Type: application/json');

$search = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';
$results = [];

if($search) {
    $query = "SELECT * FROM posts WHERE 
              title LIKE ? OR 
              content LIKE ? OR 
              category LIKE ? 
              ORDER BY created_at DESC 
              LIMIT 5";
    $stmt = $conn->prepare($query);
    $searchTerm = "%$search%";
    $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while($row = $result->fetch_assoc()) {
        $results[] = [
            'id' => $row['id'],
            'title' => $row['title'],
            'category' => $row['category'],
            'date' => date('M j, Y', strtotime($row['created_at']))
        ];
    }
}

echo json_encode($results);
