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
  
   $profileImage = $user['profile_image'] ? $user['profile_image'] : '../images/blueuser.svg';
   $profileImage = 'images/blueuser.svg';
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="homepagetopbar.css">
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <a href="homepage.php">MyWebsite</a>
        </div>
        <div class="nav-links">
            <a href="homepage.php">Home</a>
            <a href="about.php">About</a>
            <a href="contact.php">Contact</a>
        </div>
        <div class="profile">
            <div class="profile-menu">
       
                <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="Profile Image">

                <button class="profile-btn"><?php echo htmlspecialchars($fullName); ?> &#9662;</button>
                <div class="profile-dropdown">
                    <a href="profile.php">Profile</a>
                    <a href="settings.php">Settings</a>
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <div style="text-align:center; padding:15%;">
      <p style="font-size:50px; font-weight:bold;">
       Hello <?php echo $fullName; ?>:
      </p>
      <a href="logout.php">Logout</a>
    </div>

    <script>
        // Toggle the dropdown menu
        document.querySelector('.profile-btn').addEventListener('click', function() {
            document.querySelector('.profile-dropdown').classList.toggle('show');
        });

        // Close the dropdown if the user clicks outside of it
        window.onclick = function(event) {
            if (!event.target.matches('.profile-btn')) {
                var dropdowns = document.getElementsByClassName("profile-dropdown");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
</body>
</html>
