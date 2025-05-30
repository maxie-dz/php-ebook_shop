<?php
require_once 'includes/db.php';
include 'includes/header.php';
$category = isset($_GET['cat']) ? trim($_GET['cat']) : '';

if (!$category) {
    die("No category selected.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
     <link rel="icon" href="uploads/images/favicon.png" type="image/png">
    <meta charset="UTF-8" />
    <title><?php echo htmlspecialchars($category); ?> Books</title>
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
            color: #5a3e2b;
            margin-bottom: 30px;
            font-size: 2rem;
        }

        .ebooks {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 25px;
            max-width: 1000px;
            margin: auto;
        }

        .book-card {
            background-color: #fff7ef;
            border-radius: 14px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.07);
            padding: 15px;
            text-align: center;
            transition: box-shadow 0.3s ease;
        }

        .book-card:hover {
            box-shadow: 0 6px 20px rgba(0,0,0,0.12);
        }

        .book-card img {
            max-width: 150px;
            height: auto;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .book-card h3 {
            font-size: 1.1rem;
            margin-bottom: 8px;
            color: #3e2f1c;
        }

        .book-card p {
            margin: 0 0 12px;
            font-weight: 600;
            color: #a9865b;
        }

        .details-button {
            background-color: #a9865b;
            color: #fff;
            padding: 8px 15px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .details-button:hover {
            background-color: #8e6c4a;
        }
    </style>
</head>
<body>

<h2><?php echo htmlspecialchars($category); ?> Books</h2>

<div class="ebooks">
<?php
$stmt = $conn->prepare("SELECT * FROM ebooks WHERE category = ? ORDER BY created_at DESC");
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    while ($book = $result->fetch_assoc()) {
        echo '<div class="book-card">';
        echo '<img src="uploads/covers/' . htmlspecialchars($book['cover_image']) . '" alt="' . htmlspecialchars($book['title']) . '">';
        echo '<h3>' . htmlspecialchars($book['title']) . '</h3>';
        echo '<p>$' . number_format($book['price'], 2) . '</p>';
        echo '<a href="product.php?id=' . $book['id'] . '" class="details-button">View Details</a>';
        echo '</div>';
    }
} else {
    echo "<p style='text-align:center; color:#6b5843;'>No books found in this category.</p>";
}

$stmt->close();
?>
</div>

</body>
</html>
