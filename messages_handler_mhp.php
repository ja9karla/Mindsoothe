<?php
include("auth.php");
include("config.php");
require __DIR__ . '/vendor/autoload.php'; // Pusher library

use Pusher\Pusher;

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to send a JSON response
function sendResponse($success, $data = null, $error = null) {
    echo json_encode([
        'success' => $success,
        'data' => $data,
        'error' => $error
    ]);
    exit;
}
$pusher = new Pusher('561b69476711bf54f56f', '10b81fe10e9b7efc75ff', '1927783', [
    'cluster' => 'ap1',
    'useTLS' => true
]);

// Trigger Pusher event for real-time messaging
$pusher->trigger("chat_$receiver_id", 'new-message', [
    'sender_id' => $mhp_id,
    'receiver_id' => $receiver_id,
    'message' => $message,
    'timestamp' => date('Y-m-d H:i:s')
]);

try {
    // Check if database connection is valid
    if (!isset($conn) || $conn->connect_error) {
        throw new Exception("Database connection failed: " . ($conn->connect_error ?? 'Unknown error'));
    }

    // Ensure MHP is logged in
    if (!isset($_SESSION['doctor_id'])) {
        sendResponse(false, null, 'Not authenticated');
    }

    $mhp_id = $_SESSION['doctor_id']; // Get MHP ID

    // Get input from client-side
    $input = json_decode(file_get_contents('php://input'), true);
    $action = $input['action'] ?? '';

    if ($action === 'send_message') {
        // Get the receiver (student) ID and the message content
        $receiver_id = isset($input['student_id']) ? (int)$input['student_id'] : null;
        $message = isset($input['message']) ? trim($input['message']) : '';

        // Validate input
        if (!$receiver_id || empty($message)) {
            sendResponse(false, null, 'Missing required parameters');
        }

        // Insert the message into the database
        $stmt = $conn->prepare("INSERT INTO Messages (sender_id, sender_type, receiver_id, receiver_type, message) 
                               VALUES (?, 'MHP', ?, 'student', ?)");
        $stmt->bind_param("iis", $mhp_id, $receiver_id, $message);

        if ($stmt->execute()) {
            // Initialize Pusher for real-time messaging
            $pusher = new Pusher('561b69476711bf54f56f', '10b81fe10e9b7efc75ff', '1927783', [
                'cluster' => 'ap1',
                'useTLS' => true
            ]);

            // Broadcast message to the correct channel (chat_{receiver_id})
            $pusher->trigger("chat_$receiver_id", 'new-message', [
                'sender_id' => $mhp_id,
                'receiver_id' => $receiver_id,
                'message' => $message,
                'timestamp' => date('Y-m-d H:i:s') // Include timestamp for the message
            ]);

            // Send response back to frontend
            sendResponse(true, [
                'message_id' => $conn->insert_id,
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        } else {
            sendResponse(false, null, 'Failed to send message');
        }
    }
} catch (Exception $e) {
    sendResponse(false, null, $e->getMessage());
}
?>
