<?php
// -------------------- CONFIG -------------------- //
$KHALTI_URL = "https://khalti.com/api/v2/payment/initiate/";
$KHALTI_KEY = "test_secret_key_here";   // Replace with real Khalti Secret Key

$ESEWA_URL = "https://rc-epay.esewa.com.np/api/epay/main/v2/form";
$ESEWA_MERCHANT_CODE = "EPAYTEST";      // Replace with your real merchant code
$ESEWA_SECRET = "8gBm/:&EnhH.1/q";      // Replace with your real secret

// Change these to your actual URLs
$payment_success_url = "http://yourwebsite.com/payment_success.php";
$payment_failure_url = "http://yourwebsite.com/payment_failure.php";
$khalti_response_url = "http://yourwebsite.com/khalti_response.php";
$website_url = "http://yourwebsite.com";

// -------------------- SELECT METHOD -------------------- //
$payment_method = $_GET['method'] ?? "esewa"; // esewa OR khalti


// -------------------- ESEWA PAYMENT -------------------- //
if ($payment_method == "esewa") {
    // Example payment data
    $amount = 100;
    $tax_amount = 0;
    $total_amount = $amount + $tax_amount;
    $transaction_uuid = uniqid("txn_");

    // Generate Signature (Server side – safer!)
    $data_to_sign = "total_amount={$total_amount},transaction_uuid={$transaction_uuid},product_code={$ESEWA_MERCHANT_CODE}";
    $signature = base64_encode(hash_hmac('sha256', $data_to_sign, $ESEWA_SECRET, true));
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Pay with eSewa</title>
    </head>
    <body>
        <form action="<?= $ESEWA_URL; ?>" method="POST" target="_blank">
            <input type="hidden" name="amount" value="<?= $amount; ?>">
            <input type="hidden" name="tax_amount" value="<?= $tax_amount; ?>">
            <input type="hidden" name="total_amount" value="<?= $total_amount; ?>">
            <input type="hidden" name="transaction_uuid" value="<?= $transaction_uuid; ?>">
            <input type="hidden" name="product_code" value="<?= $ESEWA_MERCHANT_CODE; ?>">
            <input type="hidden" name="product_service_charge" value="0">
            <input type="hidden" name="product_delivery_charge" value="0">
            <input type="hidden" name="success_url" value="<?= $payment_success_url; ?>">
            <input type="hidden" name="failure_url" value="<?= $payment_failure_url; ?>">
            <input type="hidden" name="signed_field_names" value="total_amount,transaction_uuid,product_code">
            <input type="hidden" name="signature" value="<?= $signature; ?>">

            <button type="submit" style="background:#60bb46;color:#fff;padding:10px 20px;border:none;cursor:pointer;">
                Pay with eSewa
            </button>
        </form>
    </body>
    </html>
    <?php
}


// -------------------- KHALTI PAYMENT -------------------- //
elseif ($payment_method == "khalti") {
    // Example data (normally comes from cart/order)
    $amount = $_POST['amount'] ?? 10000; // Khalti uses paisa (10000 = Rs.100)
    $purchase_order_id = $_POST['purchase_order_id'] ?? uniqid("order_");

    $user_info = getUserInfo(); // Replace with actual logged-in user data

    if ($user_info) {
        $data = array(
            "return_url" => $khalti_response_url,
            "website_url" => $website_url,
            "amount" => $amount,
            "purchase_order_id" => $purchase_order_id,
            "purchase_order_name" => "Order Payment",
            "customer_info" => array(
                "name" => $user_info['full_name'],
                "email" => $user_info['email'],
                "phone" => $user_info['phone']
            )
        );

        $payload = json_encode($data);

        $headers = array(
            "Authorization: Key $KHALTI_KEY",
            "Content-Type: application/json"
        );

        $ch = curl_init($KHALTI_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        if ($response === false) {
            echo "Error in API request: " . curl_error($ch);
        } else {
            $response_data = json_decode($response, true);

            if (isset($response_data['pidx']) && isset($response_data['payment_url'])) {
                $payment_url = $response_data['payment_url'];

                // Redirect user to Khalti payment page
                header("Location: " . $payment_url);
                exit;
            } else {
                echo "Failed to initiate Khalti payment.";
            }
        }
        curl_close($ch);
    } else {
        echo "User information not found.";
    }
}


// -------------------- HELPER FUNCTION -------------------- //
function getUserInfo() {
    // Replace with actual session/user DB data
    return array(
        "full_name" => "John Doe",
        "email" => "john.doe@example.com",
        "phone" => "9800000000"
    );
}

else {
    echo "Invalid payment method!";
}
?>
