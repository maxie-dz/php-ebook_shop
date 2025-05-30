<?php
require_once '../includes/db.php';

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    $stmt = $conn->prepare("UPDATE orders SET payment_status = 'rejected' WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: orders.php?message=Order+rejected");
    exit;
} else {
    header("Location: orders.php?message=Missing+order+ID");
    exit;
}

?>
