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
$nameErr = $emailErr = $passwordErr = $rpasswordErr = "" ;
$name = $email = $passwords = $rpassword = "";
$nameClass = $emailClass = $passwordClass = $rpasswordClass = "";


//SET FLAG
$hasErrors = false;

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once '../helper/index.php';
    // Validate Name
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
        $hasErrors = true;
    } else {
        $name = validate_input($_POST["name"]);
        // Check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
            $nameErr = "Only letters and white space allowed";
            $hasErrors = true;
        }
    }

    // Validate Email
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
        $hasErrors = true;
    } else {
        $email = validate_input($_POST["email"]);
        // Check if email format is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
            $hasErrors = true;
        }
    }
    //password is at least 8 characters long and includes at least one uppercase letter, one lowercase letter, and one number.
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
        $hasErrors = true;
    } else {
        $password = validate_input($_POST["password"]);
        // Check if password only contains letters and numbers
        if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}$/', $password)) {
            $passwordErr = "Password is at least 8 characters long, one uppercase letter, one lowercase letter, and one number";
            $hasErrors = true;
        }
    }
    if (empty($_POST["rpassword"])) {
        $rpasswordErr = "Repeat Password is required";
        $hasErrors = true;
    } else {
        $rpassword = validate_input($_POST["rpassword"]);
        if($passwords != $rpassword){
            $rpasswordErr = "Repeat Password not matched";
            $hasErrors = true;
        }
    }

    $nameClass = $nameErr ? 'error-border' : '';
    $emailClass = $emailErr ? 'error-border' : '';
    $passwordClass = $passwordErr ? 'error-border' : '';
    $rpasswordClass = $rpasswordErr ? 'error-border' : '';
    if (!$hasErrors) {
        //require_once("../config/db.php");
        $_SESSION['user_id'] = $email;
        // INSER QUERY
        //     $sql = "INSERT INTO MyGuests (firstname, lastname, email)
        // VALUES ('John', 'Doe', 'john@example.com')";

        // if ($conn->query($sql) === TRUE) {
        // echo "New record created successfully";
        // redirect("Location:login.php");
        // exit();
        // } else {
        // echo "Error: " . $sql . "<br>" . $conn->error;
        // }
        header("Localtion:index.php");
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Signup</title>
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body class="bg-gradient-primary">
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                            </div>
                            <form class="user" method="post">
                                <div class="form-group row">
                                    <div class="col-sm-12 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user <?= $nameClass; ?>" id="exampleFirstName" placeholder="Name" name="name">
                                        <span class="text-danger text-sm"><?= $nameErr ?></span>
                                    </div>
                                   
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user <?= $emailClass ?>" id="exampleInputEmail" placeholder="Email Address" name="email">
                                    <span class="text-danger text-sm"><?= $emailErr ?></span>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user <?= $passwordClass ?>" id="exampleInputPassword" placeholder="Password" name="password">
                                        <span class="text-danger text-sm"><?= $passwordErr ?></span>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user <?= $rpasswordClass ?>" id="exampleRepeatPassword" placeholder="Repeat Password" name="rpassword">
                                        <span class="text-danger text-sm"><?= $rpasswordErr ?></span>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-user btn-block"> Register Account </button>
                                <hr>
                                <a href="index.php" class="btn btn-google btn-user btn-block">
                                    <i class="fab fa-google fa-fw"></i> Register with Google
                                </a>
                                <a href="index.php" class="btn btn-facebook btn-user btn-block">
                                    <i class="fab fa-facebook-f fa-fw"></i> Register with Facebook
                                </a>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="forgot-password.php">Forgot Password?</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="login.php">Already have an account? Login!</a>
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