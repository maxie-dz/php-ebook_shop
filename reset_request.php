<?php
session_start();
require_once 'includes/db.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Please enter a valid email address.';
    } else {
        // Find user by email
        $stmt = $conn->prepare("SELECT id, username FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            // Delete old tokens for this user
            $stmt = $conn->prepare("DELETE FROM password_reset_requests WHERE user_id = ?");
            $stmt->bind_param("i", $user['id']);
            $stmt->execute();

            // Create new token and expiry
            $token = bin2hex(random_bytes(32));
            $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $stmt = $conn->prepare("INSERT INTO password_reset_requests (user_id, token, expires_at) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $user['id'], $token, $expires_at);
            $stmt->execute();

            // Build reset URL - change domain accordingly
            $reset_link = "http://localhost:8888/reset_password.php?token=$token";

            // Send email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'lamminne2004@gmail.com';
                $mail->Password = 'vwgk sofv icjg ldar'; // your app password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('no-reply@yourdomain.com', 'WordCrate');
                $mail->addAddress($email, $user['username']);

                $mail->isHTML(true);
                $mail->Subject = 'Password Reset Request';
                $mail->Body = "
                    <p>Hello {$user['username']},</p>
                    <p>You requested a password reset. Click the link below to reset your password:</p>
                    <p><a href='$reset_link'>$reset_link</a></p>
                    <p>This link will expire in 1 hour.</p>
                ";

                $mail->send();
                $message = 'A password reset link has been sent to your email.';
            } catch (Exception $e) {
                $message = "Email sending failed: {$mail->ErrorInfo}";
            }
        } else {
            $message = 'Email not found.';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Request Password Reset</title>
</head>
<body>
    <h2>Reset your password</h2>
    <?php if ($message): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        <button type="submit">Send reset link</button>
    </form>
</body>
</html>
