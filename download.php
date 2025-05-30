<?php
require_once 'includes/db.php';

$ebook_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$email = isset($_GET['email']) ? trim($_GET['email']) : '';

if (!$ebook_id || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid request.");
}

// Check if order exists and is paid
$stmt = $conn->prepare("SELECT ebooks.file_path FROM orders 
    JOIN ebooks ON orders.ebook_id = ebooks.id
    WHERE orders.ebook_id = ? AND orders.customer_email = ? AND orders.payment_status = 'paid'
    LIMIT 1");
$stmt->bind_param("is", $ebook_id, $email);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    die("No valid order found for this eBook and email.");
}

$file_path = 'uploads/ebooks/' . $order['file_path'];

if (!file_exists($file_path)) {
    die("File not found.");
}

// Send the file for download
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($file_path));
readfile($file_path);
exit;
