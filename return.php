<?php
error_reporting(E_ALL & ~E_DEPRECATED);
require_once 'includes/db.php'; // Your DB connection
require_once 'vendor/autoload.php'; // PHPMailer + PayPal SDK if needed
include 'includes/header.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Get PayPal order id from URL (usually 'token' on return)
$paypalOrderId = $_GET['token'] ?? $_GET['order_id'] ?? null;

if (!$paypalOrderId) {
    die("Invalid order.");
}

// Lookup order in your database by PayPal order ID to get ebook_id and customer email
$stmt = $conn->prepare("SELECT ebook_id, customer_email FROM orders WHERE paypal_order_id = ?");
$stmt->bind_param("s", $paypalOrderId);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    die("Order not found.");
}

$ebook_id = $order['ebook_id'];
$email = $order['customer_email'];

// Look up eBook in database
$stmt2 = $conn->prepare("SELECT * FROM ebooks WHERE id = ?");
$stmt2->bind_param("i", $ebook_id);
$stmt2->execute();
$result2 = $stmt2->get_result();
$ebook = $result2->fetch_assoc();

if (!$ebook) {
    die("eBook not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
     <link rel="icon" href="uploads/images/favicon.png" type="image/png">
    <meta charset="UTF-8">
    <title>Download Your eBook</title>
    <style>
        body {
            font-family: Georgia, serif;
            background-color: #fef8f2;
            color: #3e2f1c;
            padding: 30px;
            text-align: center;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background-color: #fffaf3;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(90, 60, 30, 0.1);
        }
        .btn {
            background-color: #a9865b;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            font-size: 1.2rem;
            border-radius: 5px;
            display: inline-block;
            transition: background-color 0.3s ease;
            margin-top: 20px;
        }
        .btn:hover {
            background-color: #946c43;
        }
        input[type=email] {
            padding: 10px;
            width: 80%;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 1rem;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Thanks for your purchase!</h1>
    <p>You bought: <strong><?= htmlspecialchars($ebook['title']) ?></strong></p>

    <form method="post">
        <input type="email" name="user_email" placeholder="Enter your email" required value="<?= htmlspecialchars($email) ?>"><br>
        <input type="submit" class="btn" value="Send Download Link">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_email = $_POST['user_email'];

        // Create download link (e.g., secure or expiring URL)
        $downloadLink = "https://localhost:8888.com/ebooks/downloads/" . urlencode($ebook['file']);

        // Send via PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com';
            $mail->SMTPAuth = true;
            $mail->Username = '';
            $mail->Password = '';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('your@email.com', 'eBook Store');
            $mail->addAddress($user_email);

            $mail->isHTML(true);
            $mail->Subject = 'Your eBook Download';
            $mail->Body = "Thank you for your purchase!<br><br>"
                        . "Click here to download your eBook: <a href='$downloadLink'>$downloadLink</a>";

            $mail->send();
            echo "<p style='color:green;'>Download link sent to your email.</p>";
        } catch (Exception $e) {
            echo "<p style='color:red;'>Mailer Error: " . htmlspecialchars($mail->ErrorInfo) . "</p>";
        }
    }
    ?>
</div>
</body>
</html>
