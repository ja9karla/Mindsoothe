<?php
session_start();

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "_Mindsoothe";
$conn = new mysqli($host, $user, $pass, $db);

// Set content type to JSON
header('Content-Type: application/json');

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle GET request (fetch time slots)
if ($method === 'GET') {
    $query = "
        SELECT id, day_of_week, 
               TIME_FORMAT(start_time, '%H:%i') as start_time, 
               TIME_FORMAT(end_time, '%H:%i') as end_time
        FROM time_slots 
        ORDER BY 
            CASE 
                WHEN day_of_week = 'Monday' THEN 1
                WHEN day_of_week = 'Tuesday' THEN 2
                WHEN day_of_week = 'Wednesday' THEN 3
                WHEN day_of_week = 'Thursday' THEN 4
                WHEN day_of_week = 'Friday' THEN 5
            END,
            start_time
    ";
    
    $result = $conn->query($query);
    
    if (!$result) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to fetch time slots']);
        exit;
    }
    
    $timeSlots = [];
    while ($row = $result->fetch_assoc()) {
        $timeSlots[] = $row;
    }
    
    echo json_encode($timeSlots);
}

// Handle POST request (add new time slot)
else if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['day']) || !isset($data['start_time']) || !isset($data['end_time'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }
    
    $stmt = $conn->prepare("INSERT INTO time_slots (day_of_week, start_time, end_time, user_id) VALUES (?, ?, ?, ?)");
    $userId = 1; // Default user ID for testing
    $stmt->bind_param("sssi", $data['day'], $data['start_time'], $data['end_time'], $userId);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'id' => $conn->insert_id]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to save time slot']);
    }
    $stmt->close();
}

// Handle DELETE request
else if ($method === 'DELETE') {
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing ID parameter']);
        exit;
    }
    
    $stmt = $conn->prepare("DELETE FROM time_slots WHERE id = ?");
    $stmt->bind_param("i", $_GET['id']);
    
    if ($stmt->execute() && $stmt->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Time slot not found']);
    }
    $stmt->close();
}

$conn->close();
?>