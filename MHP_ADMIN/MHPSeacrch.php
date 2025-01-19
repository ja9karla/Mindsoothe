<?php
session_start();

// Database connection
include("connect.php");

// Check if doctor is logged in
if (!isset($_SESSION['doctor_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// Get all users
function getAllUsers($conn) {
    $sql = "SELECT id, firstName, lastName FROM Users";
    $result = $conn->query($sql);
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    return $users;
}

if (isset($_GET['fetchUsers'])) {
    $users = getAllUsers($conn);
    echo json_encode($users);
    exit();
}
?>