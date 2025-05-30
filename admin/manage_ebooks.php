<?php
session_start();
require_once '../includes/db.php';

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = (int)$_POST['delete_id'];
    
    // Delete the ebook from the database
    $stmt = $conn->prepare("DELETE FROM ebooks WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();

    // Optional: you can also delete the file from uploads folder here
    // e.g., unlink('../uploads/ebooks/' . $ebook['file_path']);

    header("Location: manage_ebooks.php?msg=deleted");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
     <link rel="icon" href="../uploads/images/favicon.png" type="image/png">
    <meta charset="UTF-8">
    <title>Manage eBooks - Admin</title>
    <link rel="stylesheet" href="../styles/style.css">
 <!-- Inside the <style> section -->
<style>
    body {
        background-color: #fff8f3;
        font-family: Georgia, serif;
        padding: 20px;
        color: #3e2f1c;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(90, 60, 30, 0.1);
    }
    th, td {
        padding: 12px;
        border-bottom: 1px solid #e3d8cc;
        text-align: left;
    }
    th {
        background-color: #c7a785;
    }

    .actions {
        display: flex;
        gap: 8px;
    }

    .btn {
        padding: 6px 10px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 0.9rem;
        text-align: center;
        white-space: nowrap;
        transition: background-color 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-view {
        background-color: #a9865b;
        color: #fff;
    }
    .btn-view:hover {
        background-color: #946c43;
    }

    .btn-edit {
        background-color: #728c6a;
        color: #fff;
    }
    .btn-edit:hover {
        background-color: #5a7354;
    }

    .btn-delete {
        background-color: #a74b4b;
        color: #fff;
    }
    .btn-delete:hover {
        background-color: #8d3838;
    }

    .actions form {
        margin: 0;
    }
</style>

<style>
    body {
        background-color: #fff8f3;
        font-family: Georgia, serif;
        padding: 20px;
        color: #3e2f1c;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(90, 60, 30, 0.1);
    }
    th, td {
        padding: 12px;
        border-bottom: 1px solid #e3d8cc;
        text-align: left;
    }
    th {
        background-color: #c7a785;
    }

    .actions {
        display: flex;
        gap: 8px;
    }

    .btn {
        padding: 6px 10px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 0.9rem;
        text-align: center;
        white-space: nowrap;
        transition: background-color 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-view {
        background-color: #a9865b;
        color: #fff;
    }
    .btn-view:hover {
        background-color: #946c43;
    }

    .btn-edit {
        background-color: #728c6a;
        color: #fff;
    }
    .btn-edit:hover {
        background-color: #5a7354;
    }

    .btn-delete {
        background-color: #a74b4b;
        color: #fff;
    }
    .btn-delete:hover {
        background-color: #8d3838;
    }

    .actions form {
        margin: 0;
    }
</style>

</head>
<body>

<h1>Manage eBooks</h1>

<?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
    <p style="color: green;">eBook deleted successfully.</p>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>Title</th>
            <th>Price</th>
            <th>Created</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $result = $conn->query("SELECT * FROM ebooks ORDER BY created_at DESC");
    if ($result->num_rows > 0):
        while ($ebook = $result->fetch_assoc()):
    ?>
        <tr>
            <td><?php echo htmlspecialchars($ebook['title']); ?></td>
            <td>$<?php echo number_format($ebook['price'], 2); ?></td>
            <td><?php echo htmlspecialchars($ebook['created_at']); ?></td>
           <td class="actions">
    <a href="../product.php?id=<?php echo $ebook['id']; ?>" class="btn btn-view">View</a>
    <a href="edit_ebook.php?id=<?php echo $ebook['id']; ?>" class="btn btn-edit">Edit</a>
    <form method="post" onsubmit="return confirm('Are you sure you want to delete this eBook?');">
        <input type="hidden" name="delete_id" value="<?php echo $ebook['id']; ?>">
        <button type="submit" class="btn btn-delete">Delete</button>
    </form>
</td>

        </tr>
    <?php
        endwhile;
    else:
        echo "<tr><td colspan='4'>No eBooks found.</td></tr>";
    endif;
    ?>
    </tbody>
</table>

<p><a href="dashboard.php">Back to Dashboard</a></p>

</body>
</html>
