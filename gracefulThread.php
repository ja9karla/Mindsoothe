<?php
session_start();
include("connect.php");

// Ensure the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: Login.html");
    exit();
}

// Get the user's information
$email = $_SESSION['email'];
$query = mysqli_query($conn, "SELECT firstName, lastName, profile_image FROM usersacc WHERE email='$email'");
$user = mysqli_fetch_assoc($query);
$fullName = $user['firstName'] . ' ' . $user['lastName'];

// Determine which profile image to display
$profileImage = $user['profile_image'] ? $user['profile_image'] : 'images/blueuser.svg';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graceful Thread</title>
</head>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #f5f5f5;
    }

    .container {
        display: flex;
    }

    .sidebar {
        width: 250px;
        background-color: #f4f4f4;
        transition: width 0.3s;
        overflow: hidden;
        position: relative;
        height: 100vh;
        display: flex;
        flex-direction: column;
        border-right: 1px solid #ddd;
    }

    .sidebar.collapsed {
        width: 60px;
    }

    .toggle-btn {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        padding: 10px;
        text-align: left;
        transition: transform 0.3s;
    }

    .sidebar.collapsed .toggle-btn {
        transform: rotate(180deg);
    }

    .menu-content {
        border-top: 1px solid #ddd;
        display: flex;
        flex-direction: column;
    }

    .menu-item {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        text-decoration: none;
        color: #333;
        transition: background-color 0.3s, padding-left 0.3s;
        font-size: 16px;
    }

    .menu-item:hover {
        background-color: #e2e2e2;
    }

    .menu-item.active {
        background-color: #d0e4f5; /* Highlight color for active item */
        border-left: 4px solid #007bff; /* A left border to indicate active section */
    }

    .menu-icon {
        width: 24px;
        height: 24px;
    }

    .menu-text {
        margin-left: 15px;
        transition: opacity 0.3s, margin-left 0.3s;
    }

    .sidebar.collapsed .menu-text {
        opacity: 0;
        margin-left: -60px;
        pointer-events: none;
    }

    .user-profile {
        display: flex;
        align-items: center;
        padding: 20px;
        background-color: #f4f4f4;
        border-top: 1px solid #ddd;
        cursor: pointer;
        text-decoration: none;
        color: #333;
    }

    .user-avatar {
        border-radius: 50%;
        width: 40px;
        height: 40px;
        margin-right: 15px;
    }

    .username {
        font-size: 16px;
        transition: opacity 0.3s, margin-left 0.3s;
    }

    .sidebar.collapsed .username {
        opacity: 0;
        margin-left: -60px;
        pointer-events: none;
    }

    .Logout {
        display: block;
        padding: 15px 20px;
        color: #333;
        text-decoration: none;
        text-align: center;
        transition: background-color 0.3s;
    }

    .Logout:hover {
        background-color: #e2e2e2;
    }

    .UserAcc {
        margin-top: auto;
        display: flex;
        flex-direction: column;
    }
</style>
<body>
    <div class="container">
        <!-- Left Sidebar -->
        <div id="sidebar" class="sidebar">
            <button id="toggleBtn" class="toggle-btn">
                <img id="toggleIcon" src="images/collaps.svg" alt="Toggle Menu" class="toggle-icon">
            </button>
            <div class="menu-content">
                <a href="#" class="menu-item" id="gracefulThreadItem">
                    <img src="images/gracefulThread.svg" alt="Graceful Thread" class="menu-icon">
                    <span class="menu-text">Graceful-thread</span> 
                </a>
                <a href="#" class="menu-item" id="sereneMomentsItem">
                    <img src="images/Vector.svg" alt="Mental Health Professional" class="menu-icon">
                    <span class="menu-text">Mental Health Professional</span>  
                </a>
            </div>
            <div class="UserAcc">
                <a href="#" class="user-profile">
                    <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="Profile Image" class="user-avatar">
                    <span class="username"><?php echo htmlspecialchars($fullName); ?></span>
                </a>
                <a href="logout.php" class="Logout">Logout</a>
            </div>
        </div>
    </div>
</body>
</html>
