<?php
include '../dbconnection.php';
header('Content-Type: application/json');
ob_start(); // Mulai output buffering untuk memastikan tidak ada output lain

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"));

try {
    switch ($method) {
        case 'GET':
            // Query untuk mendapatkan data studio dan jumlah prompts serta hosts
            $queryStr = "SELECT s.id, s.name, 
                            COUNT(DISTINCT p.id) AS prompts, 
                            COUNT(DISTINCT CASE WHEN u.role = 'manager' THEN u.id END) AS managers,
                            COUNT(DISTINCT CASE WHEN u.role = 'host' THEN u.id END) AS hosts
                         FROM studios AS s
                         LEFT JOIN prompts AS p ON s.id = p.studio_id
                         LEFT JOIN users AS u ON s.id = u.studio_id
                         GROUP BY s.id";

            $stmt = $db->query($queryStr);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $count = count($results);
            $message = $count === 0 ? 'No data available' : "$count records loaded successfully";

            ob_end_clean(); // Hapus semua output buffering sebelum mengirim JSON
            http_response_code(200); // OK
            echo json_encode([
                'success' => true,
                'message' => $message,
                'count' => $count,
                'results' => $results
            ]);
            break;

        case 'POST':
            if (isset($data->name)) {
                
                // Buat studio

                $stmt = $db->prepare("
                    INSERT INTO studios (name) 
                    VALUES (:name)
                ");
                $stmt->bindParam(':name', ucwords(strtolower($data->name)));
                $stmt->execute();

                // ID Studio yang baru dibuat
                
                $studioID = $db->lastInsertId();


                // Otomatis tambahkan studio manager

                $userName = "manager$studioID";
                $fullName = "{$data->name} Manager";
                $mobileNumber = generateRandomMobileNumber();

                $stmt = $db->prepare("
                    INSERT INTO users (user_name, full_name, mobile_number, role, password, studio_id)
                    VALUES (:user_name, :full_name, :mobile_number, :role, :password, :studio_id)
                ");

                $stmt->bindParam(':user_name', $userName);
                $stmt->bindParam(':full_name', $fullName);
                $stmt->bindParam(':mobile_number', $mobileNumber);
                $stmt->bindParam(':studio_id', $studioID);
                $stmt->bindValue(':role', 'manager');
                $stmt->bindValue(':password', password_hash('123', PASSWORD_DEFAULT));
                
                $stmt->execute();


                // Otomatis tambahkan streamer host

                $userName = "host{$studioID}";
                $fullName = "{$data->name} Host";
                $mobileNumber = generateRandomMobileNumber();

                $stmt = $db->prepare("
                    INSERT INTO users (user_name, full_name, mobile_number, role, password, studio_id)
                    VALUES (:user_name, :full_name, :mobile_number, :role, :password, :studio_id)
                ");

                $stmt->bindParam(':user_name', $userName);
                $stmt->bindParam(':full_name', $fullName);
                $stmt->bindParam(':mobile_number', $mobileNumber);
                $stmt->bindParam(':studio_id', $studioID);
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
                throw new Exception('Invalid input: name is required');
            }
            break;

        case 'PUT':
            if (isset($data->id, $data->name)) {
                $stmt = $db->prepare("
                    UPDATE studios 
                    SET name = :name 
                    WHERE id = :id
                ");
                $stmt->bindParam(':id', $data->id);
                $stmt->bindParam(':name', ucwords(strtolower($data->name)));
                $stmt->execute();

                ob_end_clean();
                http_response_code(200); // OK
                echo json_encode([
                    'success' => true, 
                    'message' => 'Record updated successfully'
                ]);
            } else {
                throw new Exception('Invalid input: id and name are required');
            }
            break;

        case 'DELETE':
            if (isset($data->id)) {
                $stmt = $db->prepare("
                    DELETE FROM studios 
                    WHERE id = :id
                ");
                $stmt->bindParam(':id', $data->id);
                $stmt->execute();

                ob_end_clean();
                http_response_code(200); // OK
                echo json_encode([
                    'success' => true, 
                    'message' => 'Record deleted successfully'
                ]);
            } else {
                throw new Exception('Invalid input: id is required');
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

function generateRandomMobileNumber()
{
    $mobileNumber = '0813';

    for ($i = 1; $i < 9; $i++) {
        $mobileNumber .= mt_rand(0, 9);
    }
    return $mobileNumber;
}
