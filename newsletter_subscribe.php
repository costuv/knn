<?php
include 'config.php';

header('Content-Type: application/json');
$response = ['success' => false, 'message' => ''];

if(isset($_POST['email'])) {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    
    if($email) {
        $check_query = "SELECT * FROM subscribers WHERE email = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        
        if($check_stmt->get_result()->num_rows > 0) {
            $response['message'] = 'This email is already subscribed to our newsletter!';
            $check_stmt->close();
        } else {
            $check_stmt->close();
    
            $stmt = $conn->prepare("INSERT INTO subscribers (email) VALUES (?)");
            $stmt->bind_param("s", $email);
            
            if($stmt->execute()) {
                $response = [
                    'success' => true,
                    'message' => 'Thank you for subscribing to our newsletter!'
                ];
            } else {
                $response['message'] = 'Subscription failed. Please try again.';
            }
            $stmt->close();
        }
    } else {
        $response['message'] = 'Please enter a valid email address.';
    }
} else {
    $response['message'] = 'Email is required.';
}

echo json_encode($response);
