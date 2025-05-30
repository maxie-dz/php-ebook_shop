<?php
session_start();
require 'includes/db.php';

$name = $email = $password = $confirm_password = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $errors[] = "All fields are required.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = "Email is already registered.";
        }
        $stmt->close();
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashed_password);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Account created! You can now log in.";
            header("Location: login.php");
            exit;
        } else {
            $errors[] = "Registration failed. Please try again.";
        }
        $stmt->close();
    }
}

require 'includes/header.php';
?>

<style>
    body {
        background-color: #fffaf5;
        font-family: 'Open Sans', sans-serif;
    }

    .form-container {
        max-width: 460px;
        margin: 60px auto;
        padding: 30px 25px;
        background-color: #ffffff;
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        border-radius: 16px;
        text-align: center;
    }

    .form-container h2 {
        font-family: 'Playfair Display', serif;
        color: #5b3e20;
        margin-bottom: 25px;
    }

    .form-container input {
        width: 90%;
        padding: 12px 14px;
        margin-bottom: 16px;
        border: 1px solid #ddd;
        border-radius: 10px;
        font-size: 1rem;
        transition: border-color 0.2s;
    }

    .form-container input:focus {
        border-color: #c09572;
        outline: none;
    }

    .form-container button {
        background-color: #5b3e20;
        color: #fff;
        padding: 12px;
        width: 100%;
        border: none;
        border-radius: 10px;
        font-weight: bold;
        font-size: 1rem;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .form-container button:hover {
        background-color: #3e2c17;
    }

    .form-container a {
        color: #c09572;
        text-decoration: none;
    }

    .form-container a:hover {
        text-decoration: underline;
    }

    .error-box, .success-box {
        background-color: #ffe3e3;
        color: #a94442;
        padding: 10px;
        border-radius: 10px;
        margin-bottom: 16px;
        font-weight: 500;
    }

    .success-box {
        background-color: #e6f9ec;
        color: #3c763d;
    }

    @media (max-width: 480px) {
        .form-container {
            margin: 30px 16px;
        }
    }
</style>

<div class="form-container">
    <h2>Sign Up</h2>

    <?php if (!empty($errors)): ?>
        <div class="error-box">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="">
        <input type="text" name="name" placeholder="Your Name" value="<?= htmlspecialchars($name) ?>" required>
        <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($email) ?>" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <button type="submit">Register</button>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </form>
</div>

</body>
</html>
