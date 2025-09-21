<?php
// reset_password.php

// Include database connection
require $_SERVER['DOCUMENT_ROOT'] . '/php_sample/config/db.php';


$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($new_password !== $confirm_password) {
        $message = "Passwords do not match!";
    } else {
        // Hash the password before saving
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the user's password in database
        $stmt = $mysqli->prepare("UPDATE user SET password=? WHERE email=?");
        $stmt->bind_param("ss", $hashed_password, $email);

        if ($stmt->execute()) {
            $message = "Password updated successfully! You can now <a href='login.php'>login</a>.";
        } else {
            $message = "Error updating password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-primary">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-5">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">Reset Your Password</h1>
                        <?php if($message) echo "<p style='color:red;'>$message</p>"; ?>
                    </div>
                    <form method="POST">
                        <div class="form-group">
                            <input type="email" name="email" class="form-control form-control-user" 
                                placeholder="Enter your email" required>
                        </div>
                        <div class="form-group">
                            <input type="password" name="new_password" class="form-control form-control-user" 
                                placeholder="New Password" required>
                        </div>
                        <div class="form-group">
                            <input type="password" name="confirm_password" class="form-control form-control-user" 
                                placeholder="Confirm New Password" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-user btn-block">Reset Password</button>
                    </form>
                    <hr>
                    <div class="text-center">
                        <a class="small" href="login.php">Back to Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
