<?php
session_start();
// Check if the user is already logged in
if (!isset($_SESSION['user_id'])) {
    // Store the current URL in a session variable to redirect back to this page after login
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    // Redirect to login page if the user is not logged in
    header("Location: login.php");
    exit();
}


?>