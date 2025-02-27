<?php
function getCategoryCounts($conn) {
    $counts = [];
    $query = "SELECT category, COUNT(*) as count FROM posts GROUP BY category";
    $result = $conn->query($query);
    
    if($result) {
        while($row = $result->fetch_assoc()) {
            $counts[$row['category']] = $row['count'];
        }
    }
    return $counts;
}
?>
