<?php
session_start();
require_once 'includes/db.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$message = '';
$show_form = false;

$token = $_GET['token'] ?? '';
if (empty($token)) {
    die('No token provided.');
}



// Prepare and execute token check query
$stmt = $conn->prepare("SELECT user_id, token, expires_at, NOW() as now_time FROM password_reset_requests WHERE token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('Invalid token (no match found).');
}

$row = $result->fetch_assoc();


// Check expiry explicitly
if (strtotime($row['expires_at']) < time()) {
    die('Token expired.');
}



$user_id = $row['user_id'];

// Fetch username to show in form
$stmt2 = $conn->prepare("SELECT username FROM users WHERE id = ?");
$stmt2->bind_param("i", $user_id);
$stmt2->execute();
$result2 = $stmt2->get_result();
$user = $result2->fetch_assoc();
$username = $user['username'] ?? 'User';

$show_form = true;

// Handle POST form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (!$password || strlen($password) < 6) {
        $message = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirm_password) {
        $message = 'Passwords do not match.';
    } else {
        // Hash password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Update password in users table
        $stmt3 = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt3->bind_param("si", $password_hash, $user_id);
        if ($stmt3->execute()) {
            // Delete used reset token
            $stmt4 = $conn->prepare("DELETE FROM password_reset_requests WHERE token = ?");
            $stmt4->bind_param("s", $token);
            $stmt4->execute();

            $message = 'Your password has been reset successfully. You can now <a href="login.php">login</a>.';
            $show_form = false;
        } else {
            $message = 'Failed to reset password. Try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset password for <?= htmlspecialchars($username) ?></h2>

    <?php if ($message): ?>
        <p><?= $message ?></p>
    <?php endif; ?>

    <?php if ($show_form): ?>
    <form method="POST" action="">
        <label>New Password:</label><br>
        <input type="password" name="password" required minlength="6"><br><br>
        <label>Confirm Password:</label><br>
        <input type="password" name="confirm_password" required minlength="6"><br><br>
        <button type="submit">Reset Password</button>
    </form>
    <?php endif; ?>
</body>
</html>
