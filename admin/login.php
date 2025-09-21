<?php 
session_start();

// If already logged in
if (isset($_SESSION['user_id'])) {
    if (isset($_SESSION['redirect_url'])) {
        $redirect_url = $_SESSION['redirect_url'];
        unset($_SESSION['redirect_url']);
        header("Location: $redirect_url");
    } else {
        header("Location: ../index.php"); // Adjust path
    }
    exit();
}

$emailErr = $passwordErr = "" ;
$email = $password = $invalid_message = "";
$emailClass = $passwordClass = "";
$hasErrors = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once '../helper/index.php';
    require_once("../config/db.php");

    // Validation
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
        $hasErrors = true;
    }
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
        $hasErrors = true;
    }

    if (!$hasErrors) {
        $email = validate_input($_POST['email']);
        $password = $_POST['password'];
        $email = $conn->real_escape_string($email);

        try {
            $sql = "SELECT * FROM user WHERE email = '$email'";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                $user = $result->fetch_assoc();

                if (password_verify($password, $user['password'])) {
                    // Optional: remove status check if not needed
                    if (!isset($user['status']) || $user['status'] == 1) {
                        $_SESSION['email'] = $email;
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['status'] = $user['status'];

                        // Redirect
                        if (isset($_SESSION['redirect_url'])) {
                            $redirect_url = $_SESSION['redirect_url'];
                            unset($_SESSION['redirect_url']);
                            header("Location: $redirect_url");
                        } else {
                            header("Location: ../index.php"); // Adjust path
                        }
                        exit();
                    } else {
                        $invalid_message = "Your account is not active.";
                    }
                } else {
                    $invalid_message = "Incorrect password.";
                }
            } else {
                $invalid_message = "Incorrect email.";
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

$emailClass = $emailErr ? 'error-border' : '';
$passwordClass = $passwordErr ? 'error-border' : '';
?>
