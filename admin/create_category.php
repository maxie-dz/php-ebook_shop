<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $image = $_FILES['image'];

    if (!empty($name) && $image['error'] === 0) {
        $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        if (in_array(strtolower($ext), $allowed)) {
            $newName = uniqid('cat_', true) . "." . $ext;
            move_uploaded_file($image['tmp_name'], "../uploads/categories/" . $newName);

            $stmt = $conn->prepare("INSERT INTO categories (name, image) VALUES (?, ?)");
            $stmt->bind_param("ss", $name, $newName);
            $stmt->execute();
            $stmt->close();

            $msg = "Category added successfully!";
        } else {
            $msg = "Invalid image format!";
        }
    } else {
        $msg = "Please enter a name and choose an image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
     <link rel="icon" href="../uploads/images/favicon.png" type="image/png">
    <meta charset="UTF-8">
    <title>Create Category</title>
    <link rel="stylesheet" href="../styles/style.css">
    <style>
        body {
            background-color: #fef8f2;
            font-family: 'Georgia', serif;
            color: #3e2f1c;
            padding: 20px;
        }

        .form-container {
            max-width: 600px;
            margin: auto;
            background-color: #fffaf3;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(90, 60, 30, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        form label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        form input[type="text"],
        form input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #d3bfa9;
            border-radius: 5px;
        }

        button {
            margin-top: 20px;
            background-color: #c09572;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
        }

        .msg {
            text-align: center;
            color: green;
            font-weight: bold;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #9b6f3a;
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Create New Category</h2>
    <?php if ($msg): ?>
        <p class="msg"><?= htmlspecialchars($msg) ?></p>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <label for="name">Category Name:</label>
        <input type="text" name="name" id="name" required>

        <label for="image">Category Image:</label>
        <input type="file" name="image" id="image" accept="image/*" required>

        <button type="submit">Add Category</button>
    </form>
    <a class="back-link" href="dashboard.php">← Back to Dashboard</a>
</div>

</body>
</html>
