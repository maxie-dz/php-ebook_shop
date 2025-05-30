<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';
include 'includes/header.php';
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
     <link rel="icon" href="uploads/images/favicon.png" type="image/png">
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($ebook['title']); ?> - WordCrate</title>
    <link rel="stylesheet" href="styles/style.css">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #fffaf5;
            margin: 0;
            padding: 40px 20px;
        }
        .product-card {
            max-width: 900px;
            margin: auto;
            background-color: #fff8f1;
            border-radius: 20px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            padding: 30px;
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            gap: 30px;
        }
        .product-image {
            flex: 1 1 250px;
            max-width: 300px;
        }
        .product-image img {
            width: 100%;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .product-info {
            flex: 2 1 400px;
        }
        .product-info h1 {
            font-size: 2rem;
            margin-bottom: 10px;
            font-family: 'Playfair Display', serif;
            color: #3e2f1c;
        }
        .product-info p {
            line-height: 1.6;
            margin-bottom: 10px;
            color: #5b4631;
        }
        .price {
            font-size: 1.4rem;
            font-weight: bold;
            color: #a86c3f;
            margin-top: 15px;
        }
        .btn-group {
            margin-top: 20px;
        }
        .btn {
            background-color: #a9865b;
            color: #fff;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 1rem;
            text-decoration: none;
            margin-right: 15px;
            transition: background-color 0.3s ease;
            display: inline-block;
        }
        .btn:hover {
            background-color: #8e6c4a;
        }
        @media (max-width: 768px) {
            .product-card {
                flex-direction: column;
                text-align: center;
            }
            .product-info {
                text-align: left;
            }
        }
    </style>
</head>
<body>

<div class="product-card">
    <div class="product-image">
        <img src="uploads/covers/<?= htmlspecialchars($ebook['cover_image']); ?>" alt="Cover of <?= htmlspecialchars($ebook['title']); ?>">
    </div>
    <div class="product-info">
        <h1><?= htmlspecialchars($ebook['title']); ?></h1>
        <p><strong>Description:</strong></p>
        <p><?= nl2br(htmlspecialchars($ebook['description'])); ?></p>
        <p class="price">$<?= number_format($ebook['price'], 2); ?></p>

        <div class="btn-group">
            <a href="create_order.php?id=<?= $ebook['id']; ?>" class="btn">Buy with PayPal</a>
            <a href="index.php" class="btn" style="background-color: #eee; color: #3e2f1c;">← Back to Store</a>
        </div>
    </div>
</div>

</body>
</html>
