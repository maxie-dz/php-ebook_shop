<?php
error_reporting(E_ALL & ~E_DEPRECATED);
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';
include 'includes/header.php';
require __DIR__ . '/vendor/autoload.php';

use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid eBook ID.");
}

$ebook_id = (int) $_GET['id'];

// Fetch eBook details
$stmt = $conn->prepare("SELECT * FROM ebooks WHERE id = ?");
$stmt->bind_param("i", $ebook_id);
$stmt->execute();
$result = $stmt->get_result();
$ebook = $result->fetch_assoc();

if (!$ebook) {
    die("eBook not found.");
}

// PayPal credentials
$clientId = "AYx7Xuz4QusbUvdsviWKWftfMnqBd7F90nnXHJdYWLBBj3ha7opxORUn59g7xT6fEh7R2pipew7VJs5V";
$clientSecret = "EJJVzHBVew41QFFeEkjj04tO7My6s_tgFS2dUR6mv--3-FqJobwqBbkmfzWNVhNkqVUYLibxHBkvv6Dl";

$environment = new SandboxEnvironment($clientId, $clientSecret);
$client = new PayPalHttpClient($environment);

$request = new OrdersCreateRequest();
$request->prefer('return=representation');
$request->body = [
    "intent" => "CAPTURE",
    "purchase_units" => [[
        "amount" => [
            "currency_code" => "USD",
            "value" => number_format($ebook['price'], 2, '.', '')
        ]
    ]],
    "application_context" => [
        "cancel_url" => "http://localhost:8888/cancel.php",
        "return_url" => "http://localhost:8888/checkout.php?id=" . $ebook_id
    ]
];

try {
    $response = $client->execute($request);
    $paypal_order_id = $response->result->id;

    // Insert order WITHOUT customer email
    $stmt = $conn->prepare("INSERT INTO orders (ebook_id, paypal_order_id, status, created_at) VALUES (?, ?, 'pending', NOW())");
    $stmt->bind_param("is", $ebook_id, $paypal_order_id);
    $stmt->execute();

    // Get approval URL
    $approveUrl = '';
    foreach ($response->result->links as $link) {
        if ($link->rel === 'approve') {
            $approveUrl = $link->href;
            break;
        }
    }

    if (!$approveUrl) {
        throw new Exception("Approval URL not found.");
    }
} catch (Exception $ex) {
    $error = "API Error: " . htmlspecialchars($ex->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
     <link rel="icon" href="uploads/images/favicon.png" type="image/png">
    <meta charset="UTF-8" />
    <title>Buy eBook - <?php echo htmlspecialchars($ebook['title']); ?></title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <style>
        body {
            font-family: Georgia, serif;
            background-color: #fef8f2;
            color: #3e2f1c;
            padding: 30px;
            max-width: 600px;
            margin: auto;
        }
        .order-info {
            background-color: #fffaf3;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(90, 60, 30, 0.1);
            text-align: center;
        }
        .order-id {
            font-weight: bold;
            margin-bottom: 20px;
            font-size: 1.2rem;
        }
        .pay-now-btn {
            background-color: #a9865b;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            font-size: 1.2rem;
            border-radius: 5px;
            display: inline-block;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }
        .pay-now-btn:hover {
            background-color: #946c43;
        }
        .back-link {
            display: inline-block;
            margin-top: 25px;
            font-size: 1rem;
            color: #a9865b;
            text-decoration: none;
            border: 2px solid #a9865b;
            padding: 8px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .back-link:hover {
            background-color: #a9865b;
            color: #fff;
        }
        .error {
            background: #fdd;
            color: #900;
            padding: 10px;
            border: 1px solid #900;
            margin-bottom: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<h1>Buy eBook: <?php echo htmlspecialchars($ebook['title']); ?></h1>

<?php if (!empty($error)): ?>
    <div class="error"><?php echo $error; ?></div>
<?php else: ?>
    <div class="order-info">
        <div class="order-id">Order ID: <?php echo htmlspecialchars($paypal_order_id); ?></div>
        <a href="<?php echo htmlspecialchars($approveUrl); ?>" class="pay-now-btn">Pay Now</a>
        <br>
        <a href="index.php" class="back-link">← Back to Store</a>
    </div>
<?php endif; ?>

</body>
</html>
