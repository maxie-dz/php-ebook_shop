<?php
session_start();
require_once 'includes/db.php'; // Database connection
require_once 'includes/header.php'; // Header with session start and navbar
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="uploads/images/favicon.png" type="image/png">
    <meta charset="UTF-8" />
    <title>WordCrate - Home</title>
    <link rel="stylesheet" href="styles/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Open+Sans&display=swap" rel="stylesheet" />
    <style>
        body {
            margin: 0;
            font-family: 'Open Sans', sans-serif;
            background-color: #fffaf5;
        }

        .hero {
            text-align: center;
            padding: 80px 20px;
            background: url('images/hero-bg.jpg') no-repeat center/cover;
            color: #fff;
            user-select: none;
        }

        .hero h2 {
            font-size: 3rem;
            font-family: 'Playfair Display', serif;
            margin-bottom: 10px;
            text-shadow: 2px 2px 6px rgba(0,0,0,0.7);
        }

        .hero p {
            font-size: 1.3rem;
            text-shadow: 1px 1px 4px rgba(0,0,0,0.6);
        }

        .btn-primary {
            background-color: #c09572;
            color: #fff;
            padding: 14px 26px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 25px;
            display: inline-block;
            transition: background-color 0.3s ease;
            box-shadow: 0 4px 8px rgba(192, 149, 114, 0.5);
        }

        .btn-primary:hover {
            background-color: #a47957;
        }

        .featured-categories {
            padding: 40px 20px;
            max-width: 1100px;
            margin: 0 auto 60px auto;
        }

        .featured-categories h3 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            margin-bottom: 25px;
            color: #3e2f1c;
            text-align: center;
        }

        .category-row {
            display: flex;
            gap: 25px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .category-card {
            background-color: #fff9f0;
            width: 240px;
            border-radius: 15px;
            box-shadow: 0 6px 16px rgba(150, 115, 85, 0.15);
            overflow: hidden;
            transition: 0.3s ease;
            text-align: center;
            text-decoration: none;
        }

        .category-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 26px rgba(150, 115, 85, 0.25);
        }

        .category-card img {
            width: 100%;
            height: 140px;
            object-fit: cover;
        }

        .category-card h4 {
            margin: 15px 0 8px 0;
            font-family: 'Georgia', serif;
            color: #5b3e20;
        }

        .testimonials {
            background-color: #f9f3e8;
            padding: 50px 20px;
            max-width: 900px;
            margin: 0 auto 60px auto;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(150, 115, 85, 0.1);
        }

        .testimonials h3 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            text-align: center;
            margin-bottom: 35px;
            color: #3e2f1c;
        }

        .testimonial {
            font-style: italic;
            font-family: Georgia, serif;
            color: #5b3e20;
            margin-bottom: 25px;
            position: relative;
            padding-left: 40px;
        }

        .testimonial::before {
            content: "“";
            font-size: 4rem;
            position: absolute;
            left: 0;
            top: -10px;
            color: #c09572;
            font-weight: 900;
        }

        .testimonial-author {
            margin-top: 8px;
            font-weight: 700;
            font-family: 'Playfair Display', serif;
            color: #a47957;
        }

        .section {
            max-width: 1100px;
            margin: 0 auto 60px auto;
            padding: 40px 20px;
        }

        .section h3 {
            font-size: 2rem;
            text-align: center;
            color: #3e2f1c;
            font-family: 'Playfair Display', serif;
            margin-bottom: 30px;
        }

        .trending-row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 25px;
        }

        .trending-item {
            background-color: #fff;
            width: 200px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            padding: 15px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .trending-item:hover {
            transform: translateY(-5px);
        }

        .trending-item img {
            width: 100%;
            height: 260px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .trending-item h4 {
            font-size: 1.1rem;
            color: #5b3e20;
            margin: 0 0 6px 0;
        }

        .trending-item p {
            font-weight: bold;
            color: #9b6f3a;
            margin: 0 0 10px 0;
        }
    </style>
</head>
<body>

<section class="hero">
    <h2>Discover Great Reads</h2>
    <p>Handpicked trending eBooks just for you</p>
    <a href="index.php" class="btn-primary">Explore Now</a>
</section>

<section class="featured-categories">
    <h3>Featured Categories</h3>
    <div class="category-row">
        <?php
        $catQuery = $conn->query("SELECT * FROM categories ORDER BY name ASC");
        if ($catQuery && $catQuery->num_rows > 0) {
            while ($cat = $catQuery->fetch_assoc()) {
                $categoryName = htmlspecialchars($cat['name']);
                $categoryImage = !empty($cat['image']) ? "uploads/categories/" . htmlspecialchars($cat['image']) : "images/category-placeholder.jpg";
                $categoryLink = "categories.php?cat=" . urlencode($cat['name']);

                $stmt = $conn->prepare("SELECT * FROM ebooks WHERE category = ? ORDER BY created_at DESC LIMIT 1");
                $stmt->bind_param("s", $cat['name']);
                $stmt->execute();
                $bookResult = $stmt->get_result();
                $book = $bookResult->fetch_assoc();
                $stmt->close();

                $bookTitle = $book ? htmlspecialchars($book['title']) : "No books yet";
                $bookLink = $book ? "product.php?id=" . $book['id'] : $categoryLink;
        ?>
            <a href="<?= $bookLink; ?>" class="category-card">
                <img src="<?= $categoryImage; ?>" alt="<?= $categoryName; ?> category" />
                <h4><?= $categoryName; ?></h4>
                <p style="color:#9b6f3a; font-weight:600;"><?= $bookTitle; ?></p>
            </a>
        <?php
            }
        } else {
            echo "<p style='text-align:center;'>No categories found.</p>";
        }
        ?>
    </div>
</section>

<section class="testimonials">
    <h3>What Our Readers Say</h3>
    <div class="testimonial">
        <p>This is the best online bookstore I’ve ever used. The selection and prices are amazing!</p>
        <div class="testimonial-author">– Emily R.</div>
    </div>
    <div class="testimonial">
        <p>I found so many hidden gems here. The interface is easy to use and the recommendations are spot on.</p>
        <div class="testimonial-author">– David M.</div>
    </div>
    <div class="testimonial">
        <p>Great customer service and fast delivery. Highly recommend WordCrate to all book lovers.</p>
        <div class="testimonial-author">– Sarah K.</div>
    </div>
</section>

<section class="section">
    <h3>Trending Now</h3>
    <div class="trending-row">
        <?php
        $result = $conn->query("SELECT * FROM ebooks ORDER BY created_at DESC LIMIT 6");
        if ($result && $result->num_rows > 0):
            while ($ebook = $result->fetch_assoc()):
        ?>
        <div class="trending-item">
            <img src="uploads/covers/<?= htmlspecialchars($ebook['cover_image']); ?>" alt="Cover of <?= htmlspecialchars($ebook['title']); ?>" />
            <h4><?= htmlspecialchars($ebook['title']); ?></h4>
            <p>$<?= number_format($ebook['price'], 2); ?></p>
            <a href="product.php?id=<?= $ebook['id']; ?>" class="btn-primary" style="padding: 8px 14px; font-size: 0.9rem;">Details</a>
        </div>
        <?php endwhile; else: ?>
        <p>No trending books found.</p>
        <?php endif; ?>
    </div>
</section>

</body>
</html>
