<?php
$KHALTI_URL = "https://khalti.com/api/v2/payment/initiate/";  // Replace with the actual Khalti URL
$KHALTI_KEY = "key 05bf95cc57244045b8df5fad06748dab";  // Your Khalti Secret Key
$ESEWA_URL = "https://rc-epay.esewa.com.np/api/epay/main/v2/form";
$ESEWA_MERCHANT_CODE = "EPAYTEST";  // Replace with
$payment_method = "esewa"; // payment method : esewa or khalti
$payment_success_url = "";
$payment_failure_url = "";
$khalti_response_url = "";
$website_url = ""; // your website url

if($payment_method == 'esewa'){?>

<style> b{color: #e96900;padding: 3px 5px;}</style>
<b>eSewa ID:</b> 9806800001/2/3/4/5 <br><b>Password:</b> Nepal@123 <b>MPIN:</b> 1122 <b>Token:</b>123456 
<body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/hmac-sha256.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/enc-base64.min.js"></script>
    <form action="<?= $ESEWA_URL; ?>" method="POST" onsubmit="generateSignature()" target="_blank">
        <table style="display:none">
            <tr>
                <td> <strong>Parameter </strong> </td>
                <td><strong>Value</strong></td>
            </tr>
            <tr>
                <td>Amount:</td>
                <td> <input type="text" id="amount" name="amount" value="100" class="form" required> <br>
                </td>
            </tr>

            <tr>
                <td>Tax Amount:</td>
                <td><input type="text" id="tax_amount" name="tax_amount" value="0" class="form" required>
                </td>
            </tr>

            <tr>
                <td>Total Amount:</td>
                <td><input type="text" id="total_amount" name="total_amount" value="100" class="form" required>
                </td>
            </tr>

            <tr>
                <td>Transaction UUID:</td>
                <td><input type="text" id="transaction_uuid" name="transaction_uuid" value="11-200-111sss1"
                        class="form" required> </td>
            </tr>

            <tr>
                <td>Product Code:</td>
                <td><input type="text" id="product_code" name="product_code" value="<?= $ESEWA_MERCHANT_CODE; ?>" class="form"
                        required> </td>
            </tr>

            <tr>
                <td>Product Service Charge:</td>
                <td><input type="text" id="product_service_charge" name="product_service_charge" value="0"
                        class="form" required> </td>
            </tr>

            <tr>
                <td>Product Delivery Charge:</td>
                <td><input type="text" id="product_delivery_charge" name="product_delivery_charge" value="0"
                        class="form" required> </td>
            </tr>

            <tr>
                <td>Success URL:</td>
                <td><input type="text" id="success_url" name="success_url" value="<?= $payment_success_url ?>"
                        class="form" required> </td>
            </tr>

            <tr>
                <td>Failure URL:</td>
                <td><input type="text" id="failure_url" name="failure_url" value="<?= $payment_failure_url ?>" class="form"
                        required> </td>
            </tr>

            <tr>
                <td>signed Field Names:</td>
                <td><input type="text" id="signed_field_names" name="signed_field_names"
                        value="total_amount,transaction_uuid,product_code" class="form" required> </td>
            </tr>

            <tr>
                <td>Signature:</td>
                <td><input type="text" id="signature" name="signature"
                        value="4Ov7pCI1zIOdwtV2BRMUNjz1upIlT/COTxfLhWvVurE=" class="form" required> </td>
            </tr>
            <tr>
                <td>Secret Key:</td>
                <td><input type="text" id="secret" name="secret" value="8gBm/:&EnhH.1/q" class="form" required>
                </td>
            </tr>
            <br><br>
        </table>
        <input value=" Pay with eSewa " type=submit class="button"
            style="display:block !important; background-color: #60bb46; cursor: pointer; color: #fff; border: none; padding: 5px 10px;'">
    </form>

    <script>
        // Function to auto-generate signature
        function generateSignature() {
            var currentTime = new Date();
            var formattedTime = currentTime.toISOString().slice(2, 10).replace(/-/g, '') + '-' + currentTime.getHours() +
                currentTime.getMinutes() + currentTime.getSeconds();
            document.getElementById("transaction_uuid").value = formattedTime;
            var total_amount = document.getElementById("total_amount").value;
            var transaction_uuid = document.getElementById("transaction_uuid").value;
            var product_code = document.getElementById("product_code").value;
            var secret = document.getElementById("secret").value;

            var hash = CryptoJS.HmacSHA256(
                `total_amount=${total_amount},transaction_uuid=${transaction_uuid},product_code=${product_code}`,
                `${secret}`);
            var hashInBase64 = CryptoJS.enc.Base64.stringify(hash);
            document.getElementById("signature").value = hashInBase64;
        }

        // Event listeners to call generateSignature() when inputs are changed
        document.getElementById("total_amount").addEventListener("input", generateSignature);
        document.getElementById("transaction_uuid").addEventListener("input", generateSignature);
        document.getElementById("product_code").addEventListener("input", generateSignature);
        document.getElementById("secret").addEventListener("input", generateSignature);
    </script>

</body>

<?php }elseif($payment_method=='khalti'){

$amount = $_POST['amount'];  // Assume the data is posted
$purchase_order_id = $_POST['purchase_order_id'];
$user_info = getUserInfo();  // Fetch user info (you can replace with actual function or variable)

if ($user_info) {
    // Prepare the payload as a JSON object
    $data = array(
        "return_url" => $khalti_response_url,
        "website_url" => $website_url,
        "amount" => $amount,
        "purchase_order_id" => $purchase_order_id,
        "purchase_order_name" => "test",
        "customer_info" => array(
            "name" => $user_info['full_name'],  // Assuming 'full_name' is in the user_info array
            "email" => $user_info['email'],    // Assuming 'email' is in the user_info array
            "phone" => $user_info['phone']     // Assuming 'phone' is in the user_info array
        )
    );

    // Convert data array to JSON string
    $payload = json_encode($data);

    // Setup headers
    $headers = array(
        "Authorization: Key $KHALTI_KEY",
        "Content-Type: application/json"
    );

    // Initialize cURL session
    $ch = curl_init($KHALTI_URL);

    // Set the cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Execute cURL request and get the response
    $response = curl_exec($ch);

    // Check if the request was successful
    if ($response === false) {
        echo "Error in API request: " . curl_error($ch);
    } else {
        // Decode JSON response
        $response_data = json_decode($response, true);
        
        // Check if the API returned a success status
        if (isset($response_data['pidx']) && isset($response_data['payment_url'])) {
            $pidx = $response_data['pidx'];
            $payment_url = $response_data['payment_url'];
            
            // Return JSON response with pidx and payment_url
            echo json_encode(array("pidx" => $pidx, "payment_url" => $payment_url));
        } else {
            echo json_encode(array("error" => "Failed to initiate payment."));
        }
    }

    // Close cURL session
    curl_close($ch);
} else {
    echo json_encode(array("error" => "User information not found."));
}

// Sample function to retrieve user info (replace with your actual logic)
function getUserInfo() {
    // Here we just return dummy data, replace with your actual user data fetching logic
    return array(
        "full_name" => "John Doe",
        "email" => "john.doe@example.com",
        "phone" => "9800000000"
    );
}
}else{
    echo "Invalid payment method";
}


?>