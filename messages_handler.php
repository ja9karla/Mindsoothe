<?php
include("auth.php");
include("config.php");

// Consistent error handling
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

// Function to send JSON response
function sendResponse($success, $data = null, $error = null) {
    echo json_encode([
        'success' => $success,
        'data' => $data,
        'error' => $error
    ]);
    exit;
}

try {
    // Verify database connection
    if (!isset($conn) || $conn->connect_error) {
        throw new Exception("Database connection failed: " . ($conn->connect_error ?? 'Unknown error'));
    }

    // Verify authentication
    if (!isset($_SESSION['email'])) {
        sendResponse(false, null, 'Not authenticated');
    }

    // Get student ID
    $email = $_SESSION['email'];
    $stmt = $conn->prepare("SELECT id FROM Users WHERE email = ?");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $sender_id = $row['id'];
    } else {
        sendResponse(false, null, 'Student not found in database');
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $action = $_POST['action'] ?? '';

        if ($action === 'send_message') {
            $receiver_id = isset($_POST['mhp_id']) ? (int)$_POST['mhp_id'] : null;
            $message = isset($_POST['message']) ? trim($_POST['message']) : '';

            // Log the attempt for debugging
            error_log("Message attempt - Sender ID: $sender_id, Receiver ID: $receiver_id, Message: $message");

            if (!$receiver_id || empty($message)) {
                sendResponse(false, null, 'Missing required parameters');
            }

            $sql = "INSERT INTO Messages (sender_id, sender_type, receiver_id, receiver_type, message) 
                    VALUES (?, 'student', ?, 'MHP', ?)";
            
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            $stmt->bind_param("iis", $sender_id, $receiver_id, $message);
            
            if ($stmt->execute()) {
                sendResponse(true, [
                    'message_id' => $conn->insert_id,
                    'timestamp' => date('Y-m-d H:i:s'),
                    'sender_id' => $sender_id
                ]);
            } else {
                throw new Exception("Execute failed: " . $stmt->error);
            }
        } elseif ($action === 'get_history') {
            $receiver_id = isset($_POST['mhp_id']) ? (int)$_POST['mhp_id'] : null;

            if (!$receiver_id) {
                sendResponse(false, null, 'Missing required parameters');
            }

            $sql = "SELECT sender_id, receiver_id, message, sender_type, created_at 
                    FROM Messages 
                    WHERE (sender_id = ? AND receiver_id = ?) 
                       OR (sender_id = ? AND receiver_id = ?)
                    ORDER BY created_at ASC";
            
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            $stmt->bind_param("iiii", $sender_id, $receiver_id, $receiver_id, $sender_id);
            $stmt->execute();
            $result = $stmt->get_result();

            $messages = [];
            while ($row = $result->fetch_assoc()) {
                $messages[] = [
                    'message' => $row['message'],
                    'sender_type' => $row['sender_type'],
                    'timestamp' => $row['created_at']
                ];
            }

            sendResponse(true, ['messages' => $messages]);
        } else {
            sendResponse(false, null, 'Invalid action');
        }
    } else {
        sendResponse(false, null, 'Invalid request method');
    }
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    sendResponse(false, null, 'Server error occurred. Please try again later.');
}
?>