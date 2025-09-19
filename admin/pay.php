<?php
// -------------------- CONFIG -------------------- //
$KHALTI_URL = "https://khalti.com/api/v2/payment/initiate/";
$KHALTI_KEY = "test_secret_key_here";

$ESEWA_URL = "https://rc-epay.esewa.com.np/api/epay/main/v2/form";
$ESEWA_MERCHANT_CODE = "EPAYTEST";
$ESEWA_SECRET = "8gBm/:&EnhH.1/q";

$payment_success_url = "http://yourwebsite.com/payment_success.php";
$payment_failure_url = "http://yourwebsite.com/payment_failure.php";
$khalti_response_url  = "http://yourwebsite.com/khalti_response.php";
$website_url = "http://localhost/php_sample/";

$payment_method = $_GET['method'] ?? null;

// -------------------- IF NO METHOD SELECTED -------------------- //
if (!$payment_method) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Select Payment Method</title>
        <style>
            body { font-family: Arial, sans-serif; text-align: center; margin-top: 100px; }
            .btn { padding: 12px 24px; margin: 10px; font-size: 18px; border: none; border-radius: 6px; cursor: pointer; }
            .esewa { background: #60bb46; color: white; }
            .khalti { background: #5a2e91; color: white; }
        </style>
    </head>
    <body>
        <h2>Choose a Payment Method</h2>
        <a href="?method=esewa"><button class="btn esewa">Pay with eSewa</button></a>
        <a href="?method=khalti"><button class="btn khalti">Pay with Khalti</button></a>
    </body>
    </html>
    <?php
    exit;
}
