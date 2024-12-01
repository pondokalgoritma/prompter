<?php
header('Content-Type: application/json');

include('../../dbconnection.php'); 

$data = json_decode(file_get_contents("php://input"));

if (isset($data->user_name) && isset($data->password)) {
    try {
        $stmt = $db->prepare("SELECT * FROM users WHERE (user_name = :user_name) OR (mobile_number = :user_name)");
        $stmt->execute([':user_name' => strtolower($data->user_name)]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($data->password, $user['password'])) {
            
            // Authentication successful
            
            session_start();
            $_SESSION['user'] = (object) $user;

            http_response_code(200); // OK
            echo json_encode([
                'success' => true,
                'message' => 'User authenticated successfully.'
            ]);

        } else {
            
            // Authentication failed
            http_response_code(401); // Unauthorized
            echo json_encode([
                'success' => false,
                'message' => 'Invalid username or password.'
            ]);
        }

    } catch (Exception $e) {
        http_response_code(500); // Internal Server Error
        echo json_encode([
            'success' => false,
            'message' => 'An unexpected error occurred.'
        ]);
    }
    
} else {
    http_response_code(400); // Bad Request
    echo json_encode([
        'success' => false,
        'message' => 'Missing username or password.'
    ]);
}