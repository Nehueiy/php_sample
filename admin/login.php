<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect POST data
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $row['password'])) {
            // Successful login: Redirect to index.php
            session_start();
            $_SESSION['user_id'] = $row['id']; // Save user info in session
            header("Location: index.php");
            exit();
        } else {
            // Password incorrect
            echo "<script>alert('Invalid password. Please try again.');</script>";
        }
    } else {
        // No account found
        echo "<script>alert('No account found with that email.');</script>";
    }

    $stmt->close();
}

$conn->close();

?>




<?php 
// Start the session to track login state
session_start();
// If the user is already logged in, redirect them to the homepage or the requested page
if (isset($_SESSION['user_id'])) {
    // If there's a redirect URL stored, send them back to that page after login
    if (isset($_SESSION['redirect_url'])) {
        $redirect_url = $_SESSION['redirect_url'];
        unset($_SESSION['redirect_url']);  // Remove redirect URL after use
        header("Location: $redirect_url");
    } else {
        // Redirect to the homepage if no redirect URL is set
        header("Location: index.php");
    }
    exit();
}

// Initialize error variables
$emailErr = $passwordErr = "" ;
$email = $password = $invalid_message = "";
$emailClass = $passwordClass = "";

//SET FLAG
$hasErrors = false;

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once '../helper/index.php';
   
    // Validate Email
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
        $hasErrors = true;
    }
    //password is at least 8 characters long and includes at least one uppercase letter, one lowercase letter, and one number.
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
        $hasErrors = true;
    }
   

    if (!$hasErrors) {
        require_once("../config/db.php");
        $email = validate_input($_POST['email']);
        $password = $_POST['password'];
        // Escape special characters in user input to prevent SQL injection
        $email = $conn->real_escape_string($email);
         // Start the try-catch block
    try {
        // Prepare SQL query to check if the email exists
        $sql = "SELECT * FROM users WHERE email = '$email'";
        // Execute the query
        $result = $conn->query($sql);

        // Check if the email exists in the database
        if ($result->num_rows > 0) {
            // Fetch user data
            $user = $result->fetch_assoc();

            // Verify the password using password_verify
            if (password_verify($password, $user['password'])) {

                if($user['status'] == 1){
                    // Successful login
                    $_SESSION['email'] = $email;
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['role'] = $user['role'];
                    header('Location: index.php');
                }else{
                    $invalid_message = "Your account is not active. Please check your email for activation link.";
                }
                
            } else {
                // Incorrect password
                $invalid_message = "Incorrect  password.";
            }
        } else {
            // Email not found
            $invalid_message = "Incorrect email ";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
    }
}
    // FOR input border css
    $emailClass = $emailErr ? 'error-border' : '';
    $passwordClass = $passwordErr ? 'error-border' : '';

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Login</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                    </div>
                                    <form class="user" method="post">
                                        <span class="text-danger text-sm"><?= $invalid_message; ?></span>
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user <?= $emailClass ?>" id="exampleInputEmail" aria-describedby="emailHelp"  placeholder="Enter Email Address..." name="email">
                                            <span class="text-danger text-sm"><?= $emailErr ?></span>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user <?= $passwordClass ?>" id="exampleInputPassword" placeholder="Password" name="password">
                                            <span class="text-danger text-sm"><?= $passwordErr ?></span>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="customCheck">
                                                <label class="custom-control-label" for="customCheck">Remember
                                                    Me</label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Login
                                        </button>
                                        <hr>
                                        <a href="index.php" class="btn btn-google btn-user btn-block">
                                            <i class="fab fa-google fa-fw"></i> Login with Google
                                        </a>
                                        <a href="index.php" class="btn btn-facebook btn-user btn-block">
                                            <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook
                                        </a>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="forgot.php">Forgot Password?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="signup.php">Create an Account!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>