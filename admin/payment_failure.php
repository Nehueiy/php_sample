<?php
// payment_failure.php

// This page will be redirected if the payment failed or was cancelled
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payment Failed</title>
  <style>
    body { font-family: Arial, sans-serif; text-align: center; margin-top: 100px; }
    .failure { color: red; font-size: 24px; }
    a { text-decoration: none; color: blue; }
  </style>
</head>
<body>
  <div class="failure">❌ Payment Failed or Cancelled.</div>
  <p>Please try again or choose another payment method.</p>
  <a href="pay.php">Go back to Payment</a>
</body>
</html>
