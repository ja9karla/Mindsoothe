<?php
require_once 'vendor/autoload.php';
include 'connect.php'; // Ensure your database connection is included

require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$clientId = getenv('CLIENT_ID');
$clientSecret = getenv('CLIENT_SECRET');

$redirectUri = 'http://localhost/mindsoothe(1)/google_callback.php';  // Must match registered URI in the Developer Console

$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

// Handling the callback from Google
if (isset($_GET['code'])) {
    // Exchange authorization code for an access token
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if (!isset($token['error'])) {
        $client->setAccessToken($token['access_token']);

        // Create a service instance to get user profile data
        $oauth2 = new Google_Service_Oauth2($client);
        $userInfo = $oauth2->userinfo->get(); // Fetch user profile information

        // Extract user details
        $email = $userInfo->email;
        $firstName = $userInfo->givenName;
        $lastName = $userInfo->familyName;
        $picture = $userInfo->picture; // URL to user's profile picture

        // Debug: Display user profile information (optional)
        echo "User Email: " . $email . "<br>";
        echo "First Name: " . $firstName . "<br>";
        echo "Last Name: " . $lastName . "<br>";
        echo "Profile Picture URL: " . $picture . "<br>";
        echo "Locale: " . $locale . "<br>";

        // Check if user exists in the database
        $checkUser = "SELECT * FROM User_Acc WHERE email='$email'";
        $result = $conn->query($checkUser);

        if ($result->num_rows > 0) {
            // User exists, log them in
            session_start();
            $_SESSION['email'] = $email;
            $_SESSION['firstName'] = $firstName; // Optional: Store additional user info in session
            $_SESSION['lastName'] = $lastName;   // Optional: Store additional user info in session
            $_SESSION['picture'] = $picture;     // Optional: Store profile picture URL in session
            header("Location: gracefulThread.php");
        } else {
            // User does not exist, insert them into the database
            $insertQuery = "INSERT INTO User_Acc (firstName, lastName, email, profile_image) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("ssss", $firstName, $lastName, $email, $picture);

            if ($stmt->execute()) {
                session_start();
                $_SESSION['email'] = $email;
                $_SESSION['firstName'] = $firstName;
                $_SESSION['lastName'] = $lastName;
                $_SESSION['picture'] = $picture;
                header("Location: gracefulThread.php");
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    } else {
        // Handle error during access token exchange
        echo "Error fetching access token: " . $token['error_description'];
    }
} else {
    echo "No authentication code provided!";
}
?>