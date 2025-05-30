<?php
session_start();
require 'includes/db.php';

$email = $password = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $errors[] = "Both email and password are required.";
    } else {
        $stmt = $conn->prepare("SELECT id, name, password, profile_pic FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $name, $hashed_password, $profile_pic);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['user_name'] = $name;
                $_SESSION['user_pfp'] = $profile_pic ?: null;
                header("Location: homepage.php");
                exit;
            } else {
                $errors[] = "Incorrect password.";
            }
        } else {
            $errors[] = "No user found with that email.";
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
        max-width: 420px;
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
    <h2>Login</h2>

    <?php if (!empty($errors)): ?>
        <div class="error-box">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="success-box">
            <p><?= htmlspecialchars($_SESSION['success']) ?></p>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <form method="post" action="">
        <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($email) ?>" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
        <p>Don't have an account? <a href="signup.php">Register here</a></p>
        <p><a href="reset_request.php">Forgot your password?</a></p>
    </form>
</div>

</body>
</html>
