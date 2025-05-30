<?php

require_once '../includes/auth.php';

// In a full version you'd check for admin login here
?>

<!DOCTYPE html>
<html lang="en">
<head>
     <link rel="icon" href="../uploads/images/favicon.png" type="image/png">
    <meta charset="UTF-8">
    <title>Admin Dashboard - eBook Store</title>
    <link rel="stylesheet" href="../styles/style.css">
    <style>
        body {
            background-color: #fef8f2;
            font-family: 'Georgia', serif;
            color: #3e2f1c;
            padding: 20px;
        }
        .dashboard {
            max-width: 800px;
            margin: auto;
            background-color: #fffaf3;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(90, 60, 30, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
        }
        .admin-links a {
            display: block;
            margin: 10px 0;
            padding: 12px;
            background-color: #c09572;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            transition: background-color 0.3s ease;
        }
        .admin-links a:hover {
            background-color: #a37455;
        }
    </style>
</head>
<body>

<div class="dashboard">
    <h1>Admin Dashboard</h1>
    <div class="admin-links">
        <a href="add_ebook.php">Add New eBook</a>
        <a href="orders.php">View Orders</a>
        <a href="../index.php">Back to Store</a>
        <a href="manage_ebooks.php">Manage eBooks</a>
<a href="create_category.php">Create Category</a>
<a href="manage_categories.php">Manage Categories</a>

    </div>
</div>

</body>
</html>
