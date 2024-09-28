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
$query = mysqli_query($conn, "SELECT firstName, lastName, profile_image FROM User_Acc WHERE email='$email'");
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
    
    .menu-content {
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
    .logo {
        padding: 10px;
        display: flex;
        justify-content: space-evenly;
        align-items: center;
    }

    .logo img {
        width: 235px; /* Adjust the size of the logo */
        height: 80px;
        border-bottom: 1px solid #ddd;
    }
    
    .main-content {
        flex-grow: 1;
        padding: 20px;
        margin-left: 20px; /* Adjusted to match the sidebar width */
        transition: margin-left 0.3s;
    }

    .post-input {
        display: flex;
        gap: 10px;
    }

    .post-input input {
        flex-grow: 1;
        padding: 15px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    .post-input button {
        padding: 15px 20px;
        border: none;
        border-radius: 5px;
        background-color: #1cabe3;
        color: white;
        cursor: pointer;
        font-weight: bold;
    }

    .post-input button:hover {
        background-color: #ffffff;
        color: #1cabe3;
    }

    .posts {
        margin-top: 10px;
    }

    .post {
        background-color: #fff;
        padding: 15px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        margin-bottom: 15px;
        max-width: 1000px; /* Match this width with the text box */
        box-sizing: border-box;
    }
</style>
<body>
    <div class="container">
        <!-- Left Sidebar -->
        <div id="sidebar" class="sidebar">
            <div class="logo">
                <img src="image/Mindsoothe (1).svg" alt="Logo" srcset="">
            </div>
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

         <!-- Main Content Area -->
         <div class="main-content">
            <div class="post-input">
                <input type="text" id="postText" placeholder="What are you grateful for?">
                <button id="postButton">Post</button>
            </div>
            <div class="posts" id="timeline">
                <!-- Posts will be appended here -->
            </div>
        </div>
    </div>
</body>
</html>
