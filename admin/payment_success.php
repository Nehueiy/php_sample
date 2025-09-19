<?php
// payment_success.php

// This page will be redirected by eSewa/Khalti if payment is successful
// You can verify transaction here with the gateway API if needed

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payment Success</title>
  <style>
    body { font-family: Arial, sans-serif; text-align: center; margin-top: 100px; }
    .success { color: green; font-size: 24px; }
    a { text-decoration: none; color: blue; }
  </style>
</head>
<body>
  <div class="success">✅ Your payment was successful!</div>
  <p>Thank you for shopping with us.</p>
  <a href="index.php">Return to Home</a>
</body>
</html>
