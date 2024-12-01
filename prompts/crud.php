<?php
include '../dbconnection.php';
ob_start(); // Mulai output buffering untuk mencegah output tidak terduga
header('Content-Type: application/json');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"));

try {
    switch ($method) {
        case 'GET':
            
            if (isset($_GET['action']) && $_GET['action'] === 'getStudios') {
                if ($_SESSION['user']->role === 'admin') {
                    $queryStr = "SELECT * FROM studios ORDER BY name";
                } else {
                    $queryStr = "SELECT * FROM studios WHERE id = :studio_id";
                }


            } else {
                if ($_SESSION['user']->role === 'admin') {
                    $queryStr = "SELECT p.*, s.name AS studio 
                                 FROM prompts AS p 
                                 LEFT JOIN studios AS s ON s.id = p.studio_id
                                 ORDER BY s.name, p.showcase, p.title";
                } else {
                    
                    $queryStr = "SELECT p.*, s.name AS studio 
                                 FROM prompts AS p 
                                 LEFT JOIN studios AS s ON s.id = p.studio_id
                                 WHERE s.id = :studio_id
                                 ORDER BY s.name, p.showcase, p.title";
                }
            }

            $stmt = $db->prepare($queryStr);
            
            if ($_SESSION['user']->role !== 'admin') {
                $studio_id = $_SESSION['user']->studio_id;
                $stmt->bindParam(':studio_id', $studio_id, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            ob_end_clean(); // Hapus semua output buffering sebelum mengirim respons JSON
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => count($results) > 1 ? count($results) . ' records loaded successfully' : '1 record loaded successfully',
                'count' => count($results),
                'results' => $results
            ]);
            break;

        case 'POST':
            if (isset($data->title, $data->content, $data->showcase, $data->studio_id)) {
                $stmt = $db->prepare("
                    INSERT INTO prompts (title, content, showcase, studio_id)
                    VALUES (:title, :content, :showcase, :studio_id)
                ");
                $stmt->bindParam(':title', ucwords(strtolower($data->title)));
                $stmt->bindParam(':content', $data->content);
                $stmt->bindParam(':showcase', $data->showcase);
                $stmt->bindParam(':studio_id', $data->studio_id);
                $stmt->execute();
                
                ob_end_clean();
                http_response_code(201);
                echo json_encode([
                    'success' => true, 
                    'message' => 'Record created successfully'
                ]);
            } else {
                throw new Exception('Invalid input');
            }
            break;

        case 'PUT':
            if (isset($data->id, $data->title, $data->content, $data->showcase, $data->studio_id)) {
                $stmt = $db->prepare("
                    UPDATE prompts 
                    SET title = :title, content = :content, showcase = :showcase, studio_id = :studio_id 
                    WHERE id = :id
                ");
                $stmt->bindParam(':id', $data->id);
                $stmt->bindParam(':title', ucwords(strtolower($data->title)));
                $stmt->bindParam(':content', $data->content);
                $stmt->bindParam(':showcase', $data->showcase);
                $stmt->bindParam(':studio_id', $data->studio_id);
                $stmt->execute();

                ob_end_clean();
                http_response_code(200);
                echo json_encode([
                    'success' => true, 
                    'message' => 'Record updated successfully'
                ]);
            } else {
                throw new Exception('Invalid input');
            }
            break;

        case 'DELETE':
            if (isset($data->id)) {
                $stmt = $db->prepare("DELETE FROM prompts WHERE id = :id");
                $stmt->bindParam(':id', $data->id);
                $stmt->execute();

                ob_end_clean();
                http_response_code(200);
                echo json_encode([
                    'success' => true, 
                    'message' => 'Record deleted successfully'
                ]);
            } else {
                throw new Exception('Invalid input');
            }
            break;
    }

} catch (Exception $e) {
    ob_end_clean(); // Hapus output buffering sebelum mengirim respons JSON untuk error
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'An error occurred: ' . $e->getMessage()
    ]);
}
