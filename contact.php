<?php
require_once 'init_session.php';
require_once 'db.php';

function enforceInquiryLimit($pdo, $limit = 10) {
    try {
        // Count current inquiries
        $countStmt = $pdo->query("SELECT COUNT(*) FROM messages");
        $current_count = (int) $countStmt->fetchColumn();

        if ($current_count > $limit) {
            $rows_to_delete = $current_count - $limit;

            // Delete the oldest inquiries. 
            // IMPORTANT: This assumes 'id' is an auto-incrementing primary key.
            // If you have a 'created_at' or 'submission_date' timestamp column, 
            // it's better to use that for ordering: ORDER BY your_timestamp_column ASC
            $deleteSql = "DELETE FROM messages ORDER BY created_at ASC LIMIT :rows_to_delete";
            $deleteStmt = $pdo->prepare($deleteSql);
            $deleteStmt->bindParam(':rows_to_delete', $rows_to_delete, PDO::PARAM_INT);
            $deleteStmt->execute();
            
            // Optional: Log that deletion occurred
            // error_log($rows_to_delete . " old inquiries deleted to maintain limit of " . $limit);
        }
    } catch (PDOException $e) {
        // Log error, but don't let it break the main contact form submission flow
        error_log('Error in enforceInquiryLimit: ' . $e->getMessage());
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF token check
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        error_log('CSRF token mismatch. SESSION: ' . ($_SESSION['csrf_token'] ?? 'not set') . ' POST: ' . ($_POST['csrf_token'] ?? 'not set'));
        die('Invalid CSRF token');
    }

    // Sanitize and validate inputs
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $business_type = trim($_POST['business_type'] ?? '');
    $capstone_type = trim($_POST['capstone_type'] ?? ''); // Optional
    $language = trim($_POST['language'] ?? ''); // Optional
    $dev_focus = trim($_POST['dev_focus'] ?? ''); // Optional
    $db_type = trim($_POST['db_type'] ?? ''); // Optional

    // Device data (user_agent only)
    // $latitude = filter_input(INPUT_POST, 'latitude', FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_FRACTION); // Removed
    // $longitude = filter_input(INPUT_POST, 'longitude', FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_FRACTION); // Removed
    $user_agent = trim($_POST['user_agent'] ?? '');

    // Basic validation
    if (empty($name) || empty($email) || empty($message) || empty($business_type)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid input: Name, Email, Message, and Business Type are required.']);
        exit;
    }
    // Validate user_agent
    if (empty($user_agent)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid input: User agent is required.']);
        exit;
    }
    // Latitude/longitude specific validation removed
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid email format.']);
        exit;
    }

    // Conditional validation for capstone and language
    if ($business_type === 'Capstone') {
        if (empty($capstone_type)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid input: Capstone Type is required for Capstone business type.']);
            exit;
        }
        if (empty($language)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid input: Language/Framework is required for Capstone.']);
            exit;
        }
    } else if (($business_type === 'Project' || $business_type === 'Activity') && empty($language)){
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid input: Language/Framework is required for Project/Activity.']);
        exit;
    }

    // Conditional validation for dev_focus and db_type
    // These are only submitted if their respective groups are visible, which JS handles.
    // So, if they are present, they should have a value.
    if (!empty($language) && empty($dev_focus)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid input: Development Focus is required when a language is selected.']);
        exit;
    }

    if (($dev_focus === 'Backend' || $dev_focus === 'Both') && empty($db_type)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid input: Database Type is required for Backend or Both focus.']);
        exit;
    }
    if ($dev_focus === 'Frontend' && !empty($db_type)){
        $db_type = null; 
    }

    $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
    $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
    $business_type = htmlspecialchars($business_type, ENT_QUOTES, 'UTF-8');
    $capstone_type = $capstone_type ? htmlspecialchars($capstone_type, ENT_QUOTES, 'UTF-8') : null;
    $language = $language ? htmlspecialchars($language, ENT_QUOTES, 'UTF-8') : null;
    $dev_focus = $dev_focus ? htmlspecialchars($dev_focus, ENT_QUOTES, 'UTF-8') : null;
    $db_type = $db_type ? htmlspecialchars($db_type, ENT_QUOTES, 'UTF-8') : null;
    $user_agent_sanitized = htmlspecialchars($user_agent, ENT_QUOTES, 'UTF-8');

    try {
        $sql = "INSERT INTO messages (name, email, message, business_type, capstone_type, language, dev_focus, db_type, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(1, $name);
        $stmt->bindParam(2, $email);
        $stmt->bindParam(3, $message);
        $stmt->bindParam(4, $business_type);
        $stmt->bindParam(5, $capstone_type, $capstone_type ? PDO::PARAM_STR : PDO::PARAM_NULL);
        $stmt->bindParam(6, $language, $language ? PDO::PARAM_STR : PDO::PARAM_NULL);
        $stmt->bindParam(7, $dev_focus, $dev_focus ? PDO::PARAM_STR : PDO::PARAM_NULL);
        $stmt->bindParam(8, $db_type, $db_type ? PDO::PARAM_STR : PDO::PARAM_NULL);
        // Latitude and Longitude parameters removed
        $stmt->bindParam(9, $user_agent_sanitized);
        
        $stmt->execute();
        $lastId = $pdo->lastInsertId();

        // Enforce the inquiry limit after successful insertion
        enforceInquiryLimit($pdo); 
        
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'message_id' => $lastId, 'message' => 'Inquiry submitted successfully.']);

    } catch (PDOException $e) {
        error_log('Database error: ' . $e->getMessage());
        header('Content-Type: application/json', true, 500);
        echo json_encode(['status' => 'error', 'message' => 'An error occurred while processing your request.']);
    }

} else {
    http_response_code(405); // Method Not Allowed
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

