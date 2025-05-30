<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
        $user_id = $_SESSION['user_id'];

        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            session_unset();
            session_destroy();
            header("Location: signup.php?deleted=1");
            exit;
        } else {
            $errors[] = "Failed to delete account.";
        }
        $stmt->close();
    } else {
        header("Location: homepage.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Account</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-container">
    <h2>Delete Account</h2>

    <?php if (!empty($errors)): ?>
        <div class="error-box">
            <?php foreach ($errors as $error) echo "<p>" . htmlspecialchars($error) . "</p>"; ?>
        </div>
    <?php endif; ?>

    <p>Are you sure you want to delete your account? This action is irreversible.</p>

    <form method="post" action="">
        <button type="submit" name="confirm" value="yes">Yes, delete my account</button>
        <button type="submit" name="confirm" value="no">No, keep my account</button>
    </form>
</div>
</body>
</html>
