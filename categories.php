<?php
require_once 'includes/db.php';
include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
     <link rel="icon" href="uploads/images/favicon.png" type="image/png">
    <meta charset="UTF-8">
    <title>Browse Categories - WordCrate</title>
    <link rel="stylesheet" href="styles/style.css?v=171658210" />
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #fffaf5;
            padding: 40px 20px;
            margin: 0;
        }

        h2 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 30px;
            color: #5a3e2b;
        }

        .categories {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 30px;
            max-width: 1000px;
            margin: auto;
        }

        .category-card {
            background-color: #fff7ef;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            overflow: hidden;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        .category-card img {
            width: 100%;
            height: 160px;
            object-fit: cover;
        }

        .category-card h3 {
            margin: 15px 0;
            font-size: 1.2rem;
            color: #3e2f1c;
        }

        .category-card a {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 18px;
            background-color: #a9865b;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }

        .category-card a:hover {
            background-color: #8e6c4a;
        }
    </style>
</head>
<body>

<h2>Browse Book Categories</h2>

<div class="categories">
<?php
$stmt = $conn->prepare("SELECT * FROM categories ORDER BY name ASC");
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    while ($cat = $result->fetch_assoc()) {
        echo '<div class="category-card">';
        echo '<img src="uploads/categories/' . htmlspecialchars($cat['image']) . '" alt="' . htmlspecialchars($cat['name']) . '">';
        echo '<h3>' . htmlspecialchars($cat['name']) . '</h3>';
        echo '<a href="category_books.php?cat=' . urlencode($cat['name']) . '">View Books</a>';
        echo '</div>';
    }
} else {
    echo "<p style='text-align:center;'>No categories found.</p>";
}
$stmt->close();
?>
</div>

</body>
</html>
