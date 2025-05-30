<?php
session_start();
require 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$success = '';
$errors = [];

// Handle profile picture upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_pic'])) {
    $file = $_FILES['profile_pic'];

    if ($file['error'] === 0 && in_array(mime_content_type($file['tmp_name']), ['image/jpeg', 'image/png'])) {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'uploads/pfp_' . $user_id . '.' . $ext;

        if (!is_dir('uploads')) mkdir('uploads');
        move_uploaded_file($file['tmp_name'], $filename);

        $stmt = $conn->prepare("UPDATE users SET profile_pic = ? WHERE id = ?");
        $stmt->bind_param("si", $filename, $user_id);
        $stmt->execute();
        $stmt->close();

        $_SESSION['user_pfp'] = $filename;
        $success = "Profile picture updated!";
    } else {
        $errors[] = "Invalid file type. Only JPG and PNG are allowed.";
    }
}

// Handle account deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_account'])) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    session_destroy();
    header("Location: index.php");
    exit;
}

require 'includes/header.php';
?>

<div class="form-container">
    <h2>Your Profile</h2>

    <?php if ($success): ?>
        <div class="success-box"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="error-box">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['user_pfp'])): ?>
        <img src="<?= htmlspecialchars($_SESSION['user_pfp']) ?>" alt="Profile Picture" class="profile-pic">
    <?php else: ?>
        <p>No profile picture uploaded.</p>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" style="margin-top: 20px;">
      <label for="profile_pic" class="custom-file-upload">
    Choose Profile Picture
</label>
<input type="file" id="profile_pic" name="profile_pic" accept="image/png, image/jpeg" required hidden>

        <button type="submit">Upload Picture</button>
    </form>

    <form method="post" onsubmit="return confirm('Are you sure you want to delete your account? This cannot be undone.');" style="margin-top: 30px;">
        <button type="submit" name="delete_account" class="delete-btn">Delete Account</button>
    </form>
</div>

<style>
    .profile-pic {
        max-width: 120px;
        border-radius: 50%;
        margin: 15px auto;
        display: block;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }
    input[type="file"] {
    display: none !important;
}
form:first-of-type button {
    margin-top: 16px;
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
    .form-container form {
    margin-bottom: 20px;
}


    .form-container h2 {
        font-family: 'Playfair Display', serif;
        color: #5b3e20;
        margin-bottom: 25px;
    }

    .form-container input[type="file"] {
        margin-bottom: 16px;
        display: block;
        margin: 0 auto 16px;
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

    .form-container .delete-btn {
        background-color: #c0392b;
    }

    .form-container .delete-btn:hover {
        background-color: #a93226;
    }

    .error-box, .success-box {
        padding: 12px;
        border-radius: 10px;
        margin-bottom: 16px;
        font-weight: 500;
        text-align: center;
    }

    .error-box {
        background-color: #ffe3e3;
        color: #a94442;
    }

    .success-box {
        background-color: #e6f9ec;
        color: #3c763d;
    }
    .custom-file-upload {
    display: inline-block;
    padding: 12px 24px;
    cursor: pointer;
    background-color: #5b3e20;
    color: white;
    font-weight: bold;
    font-size: 1rem;
    border-radius: 10px;
    transition: background-color 0.3s;
    user-select: none;
    box-shadow: 0 3px 8px rgba(110, 80, 40, 0.2);
}

.custom-file-upload:hover {
    background-color: #3e2c17;
}

/* Optional: show selected file name */
#profile_pic:focus + .custom-file-upload,
#profile_pic:hover + .custom-file-upload {
    background-color: #3e2c17;
}

</style>
