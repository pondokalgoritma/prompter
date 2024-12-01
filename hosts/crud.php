<?php
include '../dbconnection.php';
header('Content-Type: application/json');
ob_start(); // Mulai output buffering untuk mencegah output tidak diinginkan

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"));

try {
    switch ($method) {
        case 'GET':
            if (isset($_GET['action']) && $_GET['action'] === 'getStudios') {
                $queryStr = "SELECT * FROM studios ORDER BY name";
            } else {
                $queryStr = "SELECT u.*, s.name AS studio 
                             FROM users AS u 
                             LEFT JOIN studios AS s ON s.id = u.studio_id
                             WHERE role = 'host'
                             ORDER BY s.name, u.user_name";
            }

            $stmt = $db->query($queryStr);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $count = count($results);
            $message = $count === 0 ? 'No data available' : "$count records loaded successfully";

            ob_end_clean(); // Hapus semua output buffering sebelum mengirim JSON
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => $message,
                'count' => $count,
                'results' => $results
            ]);
            break;

        case 'POST':
            if (isset($data->user_name, $data->full_name, $data->mobile_number, $data->studio_id)) {
                $stmt = $db->prepare("
                    INSERT INTO users (user_name, full_name, mobile_number, role, password, studio_id)
                    VALUES (:user_name, :full_name, :mobile_number, :role, :password, :studio_id)
                ");

                $stmt->bindParam(':user_name', strtolower($data->user_name));
                $stmt->bindParam(':full_name', ucwords(strtolower($data->full_name)));
                $stmt->bindParam(':mobile_number', $data->mobile_number);
                $stmt->bindParam(':studio_id', $data->studio_id);
                $stmt->bindValue(':role', 'host');
                $stmt->bindValue(':password', password_hash('123', PASSWORD_DEFAULT));
                
                $stmt->execute();

                ob_end_clean();
                http_response_code(201); // Created
                echo json_encode([
                    'success' => true, 
                    'message' => 'Record created successfully'
                ]);
            } else {
                throw new Exception('Invalid input: All fields are required');
            }
            break;

        case 'PUT':
            if (isset($data->id)) {
                if (isset($data->action) && $data->action === 'resetPassword') {
                    $stmt = $db->prepare("
                        UPDATE users 
                        SET password = :password 
                        WHERE id = :id
                    ");
                    $stmt->bindValue(':password', password_hash('123', PASSWORD_DEFAULT));
                    $stmt->bindParam(':id', $data->id);
                    $stmt->execute();

                    $message = 'Password reset successfully';
                } else {
                    if (isset($data->user_name, $data->full_name, $data->mobile_number, $data->studio_id)) {
                        $stmt = $db->prepare("
                            UPDATE users 
                            SET user_name = :user_name, 
                                full_name = :full_name, 
                                mobile_number = :mobile_number, 
                                studio_id = :studio_id 
                            WHERE id = :id
                        ");

                        $stmt->bindParam(':id', $data->id);
                        $stmt->bindParam(':user_name', strtolower($data->user_name));
                        $stmt->bindParam(':full_name', ucwords(strtolower($data->full_name)));
                        $stmt->bindParam(':mobile_number', $data->mobile_number);
                        $stmt->bindParam(':studio_id', $data->studio_id);
                        $stmt->execute();

                        $message = 'Record updated successfully';
                    } else {
                        throw new Exception('Invalid input: All fields are required for updating user');
                    }
                }

                ob_end_clean();
                http_response_code(200); // OK
                echo json_encode([
                    'success' => true, 
                    'message' => $message
                ]);
            } else {
                throw new Exception('Invalid input: User ID is required');
            }
            break;

        case 'DELETE':
            if (isset($data->id)) {
                $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
                $stmt->bindParam(':id', $data->id);
                $stmt->execute();

                ob_end_clean();
                http_response_code(200); // OK
                echo json_encode([
                    'success' => true, 
                    'message' => 'Record deleted successfully'
                ]);
            } else {
                throw new Exception('Invalid input: User ID is required for deletion');
            }
            break;

        default:
            throw new Exception('Invalid request method');
    }

} catch (Exception $e) {
    ob_end_clean(); // Hapus semua output buffering sebelum mengirim respons error
    http_response_code(500); // Internal Server Error
    echo json_encode([
        'success' => false, 
        'message' => 'An error occurred: ' . $e->getMessage()
    ]);
}
