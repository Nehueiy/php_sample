<?php
$servername = "localhost";
$username = "root";
$passwords = "";
$dbname = "php_sample";

// Create connection
$conn = new mysqli($servername, $username, $passwords, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>