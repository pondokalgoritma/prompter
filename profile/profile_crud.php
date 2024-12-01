<?php
include '../dbconnection.php';
header('Content-Type: application/json');
session_start();

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"));

try {
    switch ($method) {
        case 'GET':
            $stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
            $stmt->execute([':id' => $_SESSION['user']->id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                http_response_code(200); // OK

                echo json_encode([
                    'success' => true,
                    'message' => 'Data load successfully',
                    'user' => $user
                ]);

            } else {
                http_response_code(400); // Bad Request

                echo json_encode([
                    'success' => false,
                    'message' => 'Data not found!'
                ]);
            }
            
            break;

        case 'PUT':
            $stmt = $db->prepare("
                UPDATE users 
                SET 
                    full_name = :full_name, 
                    mobile_number = :mobile_number
                WHERE id = :id
            ");

            $stmt->bindParam(':id', $_SESSION['user']->id);
            $stmt->bindParam(':full_name', ucwords(strtolower($data->full_name)));
            $stmt->bindParam(':mobile_number', $data->mobile_number);
            $stmt->execute();

            http_response_code(200); // OK
            echo json_encode([
                'success' => true, 
                'message' => 'Your profile updated successfully'
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