<?php
session_start();
include("connect.php");

// Ensure the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: Login.html");
    exit();
}

// Get the user's information based on their email
$email = $_SESSION['email'];
$query = mysqli_query($conn, "SELECT id, firstName, lastName, profile_image FROM User_Acc WHERE email='$email'");

// Check if the query returned any results
if (mysqli_num_rows($query) > 0) {
    $user = mysqli_fetch_assoc($query);
    $userId = $user['id']; // This is the user_id you will use for the posts
    $fullName = $user['firstName'] . ' ' . $user['lastName'];
    $profileImage = $user['profile_image'] ? $user['profile_image'] : 'images/blueuser.svg';
} else {
    echo "<p>Error: User not found.</p>";
    exit();
}
// Handle post submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['postText'])) {
    $content = mysqli_real_escape_string($conn, $_POST['postText']);
    
    if (!empty($content)) {
        // Insert the post into the Graceful_Thread table
        $insertPost = "INSERT INTO Graceful_Thread (user_id, content) VALUES ('$userId', '$content')";
        
        if (mysqli_query($conn, $insertPost)) {
            // Display success alert
            echo "<script>alert('Post added successfully!');</script>";
        } else {
            // Display error alert with the MySQL error
            echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
        }
    } else {
        // Display alert for empty content
        echo "<script>alert('Post content cannot be empty!');</script>";
    }
    
}
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
        position: fixed; /* Make the sidebar fixed on the left */
        top: 0; /* Stick to the top */
        left: 0; /* Stick to the left */
        height: 100vh; /* Full height of the viewport */
        display: flex;
        flex-direction: column;
        border-right: 1px solid #ddd;
        overflow-y: auto; /* Add scrolling if the sidebar content overflows */
        z-index: 1000; /* Ensure it's on top of other content */
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
        margin-left: 250px; /* Adjusted to match the sidebar width */
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

    .post-header {
        display: flex;
        align-items: center;
        margin-bottom: 3px;
    }

    .post-header .username {
        margin-right: 10px; /* Add space between username and time */
        font-size: 14px;
    }

    .post-header .time {
        color: gray; /* You can also style the time differently */
        font-size: 14px; /* Optional: make the time a bit smaller */
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
            <form method="POST" action="">
                <div class="post-input">
                    <input type="text" id="postText" name="postText" placeholder="What are you grateful for?">
                    <button type="submit" id="postButton">Post</button>
                </div>
            </form>
            <div class="posts" id="timeline">
                <!-- Posts will be appended here -->
                <?php
                // Fetch posts from Graceful_Thread
                $fetchPosts = mysqli_query($conn, "SELECT GT.content, GT.created_at, UA.firstName, UA.lastName, UA.profile_image 
                                                    FROM Graceful_Thread GT 
                                                    INNER JOIN User_Acc UA ON GT.user_id = UA.id 
                                                    ORDER BY GT.created_at DESC");

                if (mysqli_num_rows($fetchPosts) > 0) {
                    while ($post = mysqli_fetch_assoc($fetchPosts)) {
                        $postUser = $post['firstName'] . ' ' . $post['lastName'];
                        $postImage = $post['profile_image'] ? $post['profile_image'] : 'images/blueuser.svg';
                        $postContent = htmlspecialchars($post['content']);
                        $postTime = date('F j, Y, g:i a', strtotime($post['created_at']));
                ?>
                <div class="post">
                    <div class="post-header">
                        <img src="<?php echo htmlspecialchars($postImage); ?>" alt="User Avatar" class="user-avatar">
                        <span class="username"><?php echo htmlspecialchars($postUser); ?></span> 
                        <span class="time"><?php echo htmlspecialchars($postTime); ?></span>
                    </div>
                    <div class="post-content">
                        <p><?php echo $postContent; ?></p>
                    </div>
                </div>
                <?php
                    }
                } else {
                    echo "<p>No posts yet. Be the first to post!</p>";
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
