<?php
session_start();
require_once 'db.php'; 

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        http_response_code(403); 
        echo json_encode(['status' => 'error', 'message' => 'CSRF token validation failed.']);
        exit;
    }

    $message_id = filter_input(INPUT_POST, 'message_id', FILTER_VALIDATE_INT);
    $latitude = filter_input(INPUT_POST, 'latitude', FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $longitude = filter_input(INPUT_POST, 'longitude', FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $user_agent = trim($_POST['user_agent'] ?? '');

    if (!$message_id || $latitude === false || $longitude === false || empty($user_agent)) {
        http_response_code(400); // Bad Request
        echo json_encode(['status' => 'error', 'message' => 'Invalid input. All fields are required.']);
        exit;
    }


    $user_agent_sanitized = htmlspecialchars($user_agent, ENT_QUOTES, 'UTF-8');

    try {
        $sql = "UPDATE messages SET latitude = ?, longitude = ?, user_agent = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        
        $stmt->bindParam(1, $latitude);
        $stmt->bindParam(2, $longitude);
        $stmt->bindParam(3, $user_agent_sanitized);
        $stmt->bindParam(4, $message_id);
        
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Message details updated.']);
            } else {

                http_response_code(404);
                echo json_encode(['status' => 'error', 'message' => 'Message ID not found or no changes made.']);
            }
        } else {
            http_response_code(500); 
            error_log('Failed to execute update statement for message ID: ' . $message_id);
            echo json_encode(['status' => 'error', 'message' => 'Failed to update message details.']);
        }
    } catch (PDOException $e) {
        http_response_code(500); 
        error_log('Database error while updating message details: ' . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'A database error occurred.']);
    }

} else {
    http_response_code(405); 
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
