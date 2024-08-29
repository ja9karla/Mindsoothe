<?php
session_start();

if (isset($_POST['verifyOTP'])) {
    $enteredOTP = $_POST['otp'];

    // Check if the OTP matches the one stored in the session
    if ($enteredOTP == $_SESSION['otp']) {
        // OTP is correct, redirect to reset password form
        header("Location: reset_password.html");
        exit();
    } else {
        echo "Invalid OTP. Please try again.";
    }
}
?>
