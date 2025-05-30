<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Get image to delete
    $stmt = $conn->prepare("SELECT image FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($image);
    $stmt->fetch();
    $stmt->close();

    // Delete image file
    if ($image && file_exists("../uploads/categories/$image")) {
        unlink("../uploads/categories/$image");
    }

    // Delete category from DB
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: manage_categories.php");
    exit;
}

$result = $conn->query("SELECT * FROM categories ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
     <link rel="icon" href="../uploads/images/favicon.png" type="image/png">
    <meta charset="UTF-8">
    <title>Manage Categories</title>
    <link rel="stylesheet" href="../styles/style.css">
    <style>
        body {
            background: #fffaf3;
            font-family: 'Georgia', serif;
            color: #3e2f1c;
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            background: #fdf8f1;
        }

        th, td {
            padding: 12px 16px;
            border: 1px solid #d8c4aa;
            text-align: left;
        }

        th {
            background: #f2e6d9;
        }

        img {
            max-width: 80px;
            border-radius: 6px;
        }

        .btn-delete {
            background: #d86b5b;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
        }

        .btn-delete:hover {
            background: #c55647;
        }

        .back {
            display: block;
            margin: 20px auto;
            text-align: center;
            color: #704822;
            text-decoration: underline;
        }
    </style>
</head>
<body>

<h2>Manage Categories</h2>

<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Image</th>
            <th>Created</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><img src="../uploads/categories/<?= htmlspecialchars($row['image']) ?>" alt="Category Image"></td>
                <td><?= $row['created_at'] ?></td>
                <td><a class="btn-delete" href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this category?')">Delete</a></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<a class="back" href="dashboard.php">← Back to Dashboard</a>

</body>
</html>
