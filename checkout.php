<?php
session_start();
require_once 'includes/db.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
include 'includes/header.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Get PayPal order id (token) from URL
$paypal_order_id = $_GET['token'] ?? null;
if (!$paypal_order_id) {
    die("Missing PayPal order token.");
}

// Fetch order by PayPal order id
$stmt = $conn->prepare("SELECT * FROM orders WHERE paypal_order_id = ?");
$stmt->bind_param("s", $paypal_order_id);
$stmt->execute();
$orderResult = $stmt->get_result();
$order = $orderResult->fetch_assoc();

if (!$order) {
    die("Order not found.");
}

// Get ebook id from order to avoid mismatch
$ebook_id = $order['ebook_id'];

// Fetch ebook details
$stmt2 = $conn->prepare("SELECT * FROM ebooks WHERE id = ?");
$stmt2->bind_param("i", $ebook_id);
$stmt2->execute();
$ebookResult = $stmt2->get_result();
$ebook = $ebookResult->fetch_assoc();

if (!$ebook) {
    die("eBook not found.");
}

$success = false;
$error = '';
$email = ''; // Initialize

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } else {
        $payment_method = 'card'; // or 'paypal', you can adjust
        $price = (float) $ebook['price'];

        // Update order with customer email and mark as paid
        $stmt3 = $conn->prepare("UPDATE orders SET customer_email = ?, amount_paid = ?, price = ?, payment_status = 'paid', payment_method = ? WHERE paypal_order_id = ?");
       $stmt3->bind_param("sddss", $email, $price, $price, $payment_method, $paypal_order_id);


        if ($stmt3->execute()) {
            $success = true;

            // Send confirmation email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP
                $mail->SMTPAuth = true;
                $mail->Username = 'lamminne2004@gmail.com';
                $mail->Password = 'vwgk sofv icjg ldar';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('lamminne2004@gmail.com', 'WordCrate');
                $mail->addAddress($email);

                $downloadUrl = "http://localhost:8888/download.php?id=$ebook_id&email=" . urlencode($email);

                $mail->Subject = 'Your eBook Purchase';
                $mail->Body = "Thank you for purchasing \"{$ebook['title']}\".\n\nDownload your eBook here:\n$downloadUrl";

                $mail->send();
            } catch (Exception $e) {
                error_log("Mail Error: " . $mail->ErrorInfo);
            }
        } else {
            $error = "Payment failed. Try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
     <link rel="icon" href="uploads/images/favicon.png" type="image/png">
    <meta charset="UTF-8">
    <title>Checkout - <?php echo htmlspecialchars($ebook['title']); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            font-family: Georgia, serif;
            background-color: #fef8f2;
            color: #3e2f1c;
            padding: 30px;
        }
        .checkout-box {
            max-width: 600px;
            margin: auto;
            background-color: #fffaf3;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(90, 60, 30, 0.1);
        }
        input[type="email"] {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #a9865b;
            color: white;
            padding: 12px 20px;
            border: none;
            margin-top: 20px;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
        }
        button:hover {
            background-color: #946c43;
        }
        .success {
            background-color: #e5f8e0;
            border: 1px solid #b4e0a6;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .error {
            background-color: #fde8e8;
            border: 1px solid #f5b3b3;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            color: #a94442;
        }
        .back-button {
    display: inline-block;
    background-color: #d1b89d;
    color: #fff;
    padding: 10px 18px;
    border-radius: 6px;
    text-decoration: none;
    margin-top: 25px;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

.back-button:hover {
    background-color: #b99e83;
}

    </style>
</head>
<body>

<div class="checkout-box">
    <h1>Checkout: <?php echo htmlspecialchars($ebook['title']); ?></h1>
    <p>Price: $<?php echo number_format($ebook['price'], 2); ?></p>

    <?php if ($success): ?>
        <div class="success">
            <strong>Success!</strong> Your order has been placed. A download link will be sent to your email.
        </div>
    <?php else: ?>

        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="post">
            <label for="email">Your Email:</label>
            <input type="email" name="email" required value="<?php echo htmlspecialchars($email); ?>">
            <button type="submit">Pay Now</button>
        </form>
    <?php endif; ?>

<a href="product.php?id=<?php echo $ebook_id; ?>" class="back-button">← Back to Product</a>

</div>

</body>
</html>
