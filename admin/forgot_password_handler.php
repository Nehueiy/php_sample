<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if the email exists in your users table
    // Send a password reset link via email
    // For now, just redirect to login page with a message
    header("Location: login.php?msg=reset_link_sent");
    exit();
}
?>
