<?php
    include("auth.php");

    // Text moderation function
    function moderateText($text) {
    // List of words/phrases to be flagged
    $badWords = [
        'puta', 'bwisit', 'gago', 'tanga', 'bobo', 'salot', 'pekeng', 'bakla', 'gaga', 'putangina',
        'ang kapal mo', 'mamatay ka na', 'ugok', 'wag ka makialam', 'katangahan', 'loko', 'sira ulo', 
        'haliparot', 'putangina mo', 'tangina mo', 'chismis', 'mukhang pera', 'patay gutom', 
        'mang-uuto', 'kasama sa buhay', 'hipokrito', 'unano', 'aswang', 'mangkukulam', 'mayabang', 
        'malandi', 'hudas', 'maasim ang mukha', 'sugapa', 'maka-appeal', 'bulok', 'tanga ka', 
        'fuck', 'shit', 'asshole', 'bitch', 'damn', 'cunt', 'motherfucker', 'bastard', 'dick', 
        'pussy', 'nigga', 'whore', 'slut', 'cocksucker', 'retard', 'crackhead', 'twat', 'fag', 
        'kike', 'chink', 'gook', 'spic', 'raghead', 'sandnigger', 'dirty Jew', 'wog', 'kaffir', 
        'nazi', 'towelhead', 'beaner', 'polack', 'dago', 'yid', 'wop', 'cholo', 'gypo', 'prick', 
        'cunt', 'whore', 'pikey', 'inbred', 'hillbilly', 'redneck', 'mamatay'
    ];
    
    
    // Replace flagged words with '[redacted]' and set the detection flag
    foreach ($badWords as $word) {
        $pattern = '/\b' . preg_quote($word, '/') . '\b/i'; // Case insensitive word boundary matching
        if (preg_match($pattern, $text)) {
            $detected = true; // Set flag if bad word is found
            $text = preg_replace($pattern, '[redacted]', $text);
        }
    }

    return $text;
}

// Handle post submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['postText'])) {
    $content = mysqli_real_escape_string($conn, $_POST['postText']);
    
    // Moderate the post content
    $moderatedContent = moderateText($content);

    if (!empty($moderatedContent)) {
        // Insert the moderated post into the Graceful_Thread table
        $insertPost = "INSERT INTO GracefulThread (user_id, content) VALUES ('$userId', '$moderatedContent')";
        
        if (mysqli_query($conn, $insertPost)) {
            // Redirect to the same page to prevent form resubmission on page refresh
            header("Location: " . $_SERVER['PHP_SELF']);
            exit(); // Ensure no further code is executed
        } else {
            // Display error alert with the MySQL error
            echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
        }
    } else {
        // Display alert for empty content
        echo "<script>alert('Post content cannot be empty!');</script>";
    }
}
    // Handle like/unlike actions
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['like_button'])) {
        $postId = $_POST['post_id'];
    
        // Check if the user has already liked the post
        $checkLikeQuery = mysqli_query($conn, "SELECT * FROM post_likes WHERE user_id='$userId' AND post_id='$postId'");
    
        if (mysqli_num_rows($checkLikeQuery) > 0) {
            // User has already liked the post, so unlike it (delete the record)
            $deleteLikeQuery = "DELETE FROM post_likes WHERE user_id='$userId' AND post_id='$postId'";
            mysqli_query($conn, $deleteLikeQuery);
     } else {
            // User hasn't liked the post, so like it (insert a new record)
            $insertLikeQuery = "INSERT INTO post_likes (user_id, post_id) VALUES ('$userId', '$postId')";
            mysqli_query($conn, $insertLikeQuery);
        }

        // Redirect to prevent form resubmission on page refresh
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graceful Thread</title>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css'>
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
    .clicked {
        background: rgba(217, 217, 217, 0.45);
        box-shadow: 0px 4px 5px 0px rgba(0, 0, 0, 0.25) inset;
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
    .post-likes{
        color: gray; /* You can also style the time differently */
        font-size: 14px;
        margin: 10px 0px 0px;
        padding: 0;
        display: inline-block; 
    }
    .liked {
        color: #1cabe3;
    }

    .not-liked {
        color: gray;
    }

    .like-button {
        margin: 10px 0px 0px;
        border: none;
        background: none;
        cursor: pointer;
        padding: 0;
    }
    .post-actions {
        display: flex;
        align-items: inline; /* Vertically align the heart icon and like count */
        gap: 10px; /* Add space between the icon and the like count */
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
                <a href="#" class="menu-item" id="MentalWellness">
                    <img src="images/Vector.svg" alt="Mental Wellness Companion" class="menu-icon">
                    <span class="menu-text">Mental Wellness Companion</span>  
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
                    $fetchPosts = mysqli_query($conn, "SELECT GT.id, GT.content, GT.created_at, UA.firstName, UA.lastName, UA.profile_image 
                                                       FROM GracefulThread GT 
                                                       INNER JOIN Users UA ON GT.user_id = UA.id 
                                                       ORDER BY GT.created_at DESC");

                    if (mysqli_num_rows($fetchPosts) > 0) {
                       while ($post = mysqli_fetch_assoc($fetchPosts)) {
                            $postId = $post['id']; // Get the post ID for likes
                            $postUser = $post['firstName'] . ' ' . $post['lastName'];
                            $postImage = $post['profile_image'] ? $post['profile_image'] : 'images/blueuser.svg';
                            $postContent = htmlspecialchars($post['content']);
                            $postTime = date('F j, Y, g:i a', strtotime($post['created_at']));
                
                        // Check if the current user liked this post
                        $checkLikeQuery = mysqli_query($conn, "SELECT * FROM post_likes WHERE user_id='$userId' AND post_id='$postId'");
                        $isLiked = mysqli_num_rows($checkLikeQuery) > 0;

                        // Determine the correct Font Awesome icon class for the like button
                        $iconClass = $isLiked ? 'fas fa-heart liked' : 'far fa-heart not-liked'; // Add color class

                        // Count the number of likes for the post
                        $likeCountQuery = mysqli_query($conn, "SELECT COUNT(*) AS like_count FROM post_likes WHERE post_id='$postId'");
                        $likeCountResult = mysqli_fetch_assoc($likeCountQuery);
                        $likeCount = $likeCountResult['like_count'];
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

                    <!-- Add Like Button -->
                    <div class="post-actions">
                        <form method="POST" action="">
                            <input type="hidden" name="post_id" value="<?php echo $postId; ?>">
                            <!-- Like Button with Font Awesome Heart Icon -->
                            <button type="submit" name="like_button" class="like-button">
                                <i class="<?php echo $iconClass; ?>"></i> <!-- This will display the correct heart icon -->
                            </button>
                        
                                <p class="post-likes"><?php echo $likeCount; ?> people like this post</p>
                           
                        </form>
                    </div>
                    <!-- Display Like Count -->
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
    <script>
        const isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
    </script>
    <script src="sidebarnav.js"></script>

</body>
</html>
