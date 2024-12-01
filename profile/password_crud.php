<?php
include '../dbconnection.php';
header('Content-Type: application/json');
session_start();

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"));

try {
    switch ($method) {
        case 'PUT':
            if (empty($data->current_password) || empty($data->new_password) || empty($data->confirm_password)) {
                http_response_code(400); // Bad Request
                echo json_encode([
                    'success' => false,
                    'message' => 'All fields are required.'
                ]);
                exit;
            }

            // Ambil ID dan password dari database
            $stmt = $db->prepare("SELECT password FROM users WHERE id = :id");
            $stmt->bindParam(':id', $_SESSION['user']->id);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                http_response_code(404); // Not Found
                echo json_encode([
                    'success' => false,
                    'message' => 'User not found.'
                ]);
                exit;
            }

            if (!password_verify($data->current_password, $user['password'])) {
                http_response_code(403); // Forbidden
                echo json_encode([
                    'success' => false,
                    'message' => 'Current password is incorrect.'
                ]);
                exit;
            }

            if ($data->new_password !== $data->confirm_password) {
                http_response_code(400); // Bad Request
                echo json_encode([
                    'success' => false,
                    'message' => 'New password and confirm password do not match.'
                ]);
                exit;
            }

            $newPasswordHash = password_hash($data->new_password, PASSWORD_BCRYPT);

            $stmt = $db->prepare("
                UPDATE users 
                SET password = :new_password 
                WHERE id = :id
            ");
            $stmt->bindParam(':id', $data->id);
            $stmt->bindParam(':new_password', $newPasswordHash);
            $stmt->execute();

            http_response_code(200); // OK
            echo json_encode([
                'success' => true,
                'message' => 'Your password updated successfully.'
            ]);
            break;

        default:
            http_response_code(400); // Bad Request
            echo json_encode([
                'success' => false,
                'message' => 'Method not defined!'
            ]);
    }

} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode([
        'success' => false, 
        'message' => 'An error occurred: ' . $e->getMessage()
    ]);
}
