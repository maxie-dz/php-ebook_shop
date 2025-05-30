<?php
session_start();

$admin_username = "admin";
$admin_password = "secret123"; // You can hash this if needed

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
