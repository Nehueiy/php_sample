<?php
session_start();
require_once("../config/db.php"); // adjust path if needed

$errors = [];
$success = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string(trim($_POST['name']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validation
    if (empty($name)) {
        $errors[] = "Name is required";
    }

    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }

    if (empty($errors)) {
        // Check if email already exists
        $check = $conn->prepare("SELECT id FROM user WHERE email=?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $errors[] = "Email already registered";
        } else {
            // Insert new customer
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $role = 1;   // Customer
            $status = 1; // Active

            // Make sure your user table has a `name` column
            $stmt = $conn->prepare("INSERT INTO user (name, email, password, role, status) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssii", $name, $email, $hashed_password, $role, $status);

            if ($stmt->execute()) {
              // auto login after registration
    $_SESSION['user_id'] = $stmt->insert_id;
    $_SESSION['email'] = $email;
    $_SESSION['name'] = $name;
    $_SESSION['role'] = $role;
    
    header("Location: customer_dashboard.php");
    exit();
}    
             else {
                $errors[] = "Database error: " . $stmt->error;
            }
            $stmt->close();
        }
        $check->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Customer</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-primary">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-8 col-md-9">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Create Customer Account</h1>
                        </div>

                        <!-- Error Messages -->
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= htmlspecialchars($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <!-- Success Message -->
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?= $success ?></div>
                        <?php endif; ?>

                        <form class="user" method="post" action="">
                            <div class="form-group">
                                <input type="text" name="name" class="form-control form-control-user"
                                       placeholder="Full Name" required>
                            </div>
                            <div class="form-group">
                                <input type="email" name="email" class="form-control form-control-user" 
                                       placeholder="Enter Email Address..." required>
                            </div>
                            <div class="form-group">
                                <input type="password" name="password" class="form-control form-control-user" 
                                       placeholder="Password" required>
                            </div>
                            <div class="form-group">
                                <input type="password" name="confirm_password" class="form-control form-control-user" 
                                       placeholder="Confirm Password" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                Register Account
                            </button>
                        </form>
                        <hr>
                        <div class="text-center">
                            <a class="small" href="login.php">Already have an account? Login!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap scripts -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>
</body>
</html>
