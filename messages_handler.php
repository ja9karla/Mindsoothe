<?php
// messages_handler.php
include("auth.php");

// Function to get chat history
function getChatHistory($student_id, $mhp_id) {
    global $conn;
    
    $sql = "SELECT * FROM Messages 
            WHERE (sender_id = ? AND sender_type = 'student' AND receiver_id = ? AND receiver_type = 'MHP')
            OR (sender_id = ? AND sender_type = 'MHP' AND receiver_id = ? AND receiver_type = 'student')
            ORDER BY timestamp ASC";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $student_id, $mhp_id, $mhp_id, $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    
    return $messages;
}

// Function to save new message
function saveMessage($sender_id, $receiver_id, $message) {
    global $conn;
    
    $sql = "INSERT INTO Messages (sender_id, sender_type, receiver_id, receiver_type, message) 
            VALUES (?, 'student', ?, 'MHP', ?)";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $sender_id, $receiver_id, $message);
    
    return $stmt->execute();
}

// API endpoint for getting chat history
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_history') {
    $mhp_id = $_GET['mhp_id'] ?? null;
    $student_id = $_SESSION['user_id'] ?? null;
    
    if ($mhp_id && $student_id) {
        $messages = getChatHistory($student_id, $mhp_id);
        echo json_encode(['success' => true, 'messages' => $messages]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Missing parameters']);
    }
    exit;
}

// API endpoint for sending message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'send_message') {
    $mhp_id = $_POST['mhp_id'] ?? null;
    $message = $_POST['message'] ?? null;
    $student_id = $_SESSION['user_id'] ?? null;
    
    if ($mhp_id && $message && $student_id) {
        $success = saveMessage($student_id, $mhp_id, $message);
        echo json_encode(['success' => $success]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Missing parameters']);
    }
    exit;
}
?>