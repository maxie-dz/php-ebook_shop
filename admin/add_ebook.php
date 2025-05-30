<?php

require_once '../includes/db.php';
require_once '../includes/auth.php'; // Ensure admin is logged in

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price       = floatval($_POST['price']);
    $category = isset($_POST['category']) ? trim($_POST['category']) : '';


    $coverName = $_FILES['cover']['name'];
    $ebookName = $_FILES['ebook']['name'];

    $coverTmp = $_FILES['cover']['tmp_name'];
    $ebookTmp = $_FILES['ebook']['tmp_name'];

    $coverDest = '../uploads/covers/' . basename($coverName);
    $ebookDest = '../uploads/ebooks/' . basename($ebookName);

    if (move_uploaded_file($coverTmp, $coverDest) && move_uploaded_file($ebookTmp, $ebookDest)) {
        $stmt = $conn->prepare("INSERT INTO ebooks (title, category, description, price, cover_image, file_path, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssdsd", $title, $category, $description, $price, $coverName, $ebookName);
        if ($stmt->execute()) {
            $message = "eBook added successfully!";
        } else {
            $message = "Error adding eBook.";
        }
        $stmt->close();
    } else {
        $message = "Failed to upload files.";
    }
}
$categories = [];
$result = $conn->query("SELECT name FROM categories ORDER BY name ASC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row['name'];
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add eBook - Admin</title>
    <link rel="stylesheet" href="../styles/style.css">
    <style>
        body {
            background-color: #fdfaf6;
            font-family: 'Georgia', serif;
            padding: 20px;
            color: #3e2f1c;
        }

        .form-container {
            max-width: 600px;
            margin: auto;
            background: #fff9f3;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(120, 90, 60, 0.1);
        }

        h1 {
            text-align: center;
        }

        form label {
            display: block;
            margin-top: 15px;
        }

        input[type="text"], input[type="number"], textarea, input[type="file"], select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        button {
            margin-top: 20px;
            padding: 12px 20px;
            background-color: #c09572;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #a37455;
        }

        .message {
            text-align: center;
            color: green;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h1>Add New eBook</h1>
    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" name="title" required>

        <label for="category">Category:</label>
        <select name="category" required>
     <option value="">Select a category</option>
<?php foreach ($categories as $cat): ?>
    <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
<?php endforeach; ?>

        </select>

        <label for="description">Description:</label>
        <textarea name="description" rows="4" required></textarea>

        <label for="price">Price ($):</label>
        <input type="number" name="price" step="0.01" required>

        <label for="cover">Cover Image (jpg/png):</label>
        <input type="file" name="cover" accept="image/*" required>

        <label for="ebook">eBook File (PDF or EPUB):</label>
        <input type="file" name="ebook" accept=".pdf,.epub" required>

        <button type="submit">Add eBook</button>
    </form>
</div>

</body>
</html>
