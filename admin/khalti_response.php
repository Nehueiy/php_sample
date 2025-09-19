<?php
// khalti_response.php

// Khalti usually returns token + amount in response after payment.
// You should verify transaction here by calling Khalti’s verification API.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payload = json_decode(file_get_contents('php://input'), true);

    // Example response structure:
    // $payload['token'], $payload['amount']

    // TODO: Verify with Khalti API
    // send POST request with secret_key + token + amount

    // for now just show the response
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'received',
        'data' => $payload
    ]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Khalti Payment Response</title>
</head>
<body>
  <h2>This endpoint is for Khalti payment verification</h2>
  <p>If you reached here directly, no payment data is available.</p>
</body>
</html>

