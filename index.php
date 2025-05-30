<?php
session_start();
require_once 'includes/db.php'; // Your DB connection
require_once 'includes/header.php'; // Include header for HTML structure
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="uploads/images/favicon.png" type="image/png">

    <meta charset="UTF-8" />
    <title>WordCrate - Browse eBooks</title>
    <link rel="stylesheet" href="styles/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Open+Sans&display=swap" rel="stylesheet" />
    <style>
        /* === BEGIN copied navbar & sidemenu styles from homepage.php === */
        body {
            margin: 0;
            font-family: 'Open Sans', sans-serif;
            background-color: #fffaf5;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 24px;
            background-color: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-logo img {
            height: 40px;
        }

        .nav-logo h1 {
            font-size: 1.5rem;
            font-family: 'Playfair Display', serif;
            color: #5b3e20;
            margin: 0;
        }

        .hamburger {
            cursor: pointer;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .hamburger div {
            width: 25px;
            height: 3px;
            background-color: #5b3e20;
        }
.side-menu {
    position: fixed;
    top: 0;
    right: -360px;
    width: 260px;
    height: 100%;
    background-color: #5b3e20;
    padding: 25px 20px;
    box-shadow: -4px 0 12px rgba(0,0,0,0.3);
    transition: right 0.3s ease;
    z-index: 1000;
    display: flex;
    flex-direction: column;
    gap: 15px;
    overflow-y: auto; /* ADD THIS */
}


        .side-menu.active {
            right: 0;
        }

        .side-menu a {
            color: #fff;
            text-decoration: none;
            font-size: 1.1rem;
            font-weight: 500;
        }

        .close-btn {
            font-size: 2rem;
            align-self: flex-end;
            cursor: pointer;
        }

.side-menu-buttons {
    margin-top: 40px;  /* INSTEAD of 'auto' */
    display: flex;
    flex-direction: column;
    gap: 10px;
    padding-bottom: 20px;
}


        .side-menu-btn {
            background-color: #c09572;
            padding: 10px;
            border-radius: 6px;
            text-align: center;
            font-weight: bold;
            color: #fff;
        }
        .ebook-card img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 3px 8px rgba(110, 80, 40, 0.18);
    display: block;
    margin: 0 auto 14px auto;
}


    
    </style>
</head>
<body>



<div class="ebooks">
    <?php
    $result = $conn->query("SELECT * FROM ebooks ORDER BY created_at DESC");
    if ($result && $result->num_rows > 0):
        while ($ebook = $result->fetch_assoc()):
    ?>
    <div class="ebook-card">
        <img src="uploads/covers/<?= htmlspecialchars($ebook['cover_image']); ?>" alt="<?= htmlspecialchars($ebook['title']); ?>" />
        <h3><?= htmlspecialchars($ebook['title']); ?></h3>
        <p><?= htmlspecialchars($ebook['description']); ?></p>
        <p><strong>Price:</strong> $<?= number_format($ebook['price'], 2); ?></p>
        <a href="product.php?id=<?= $ebook['id']; ?>" class="btn-primary">View Details</a>
    </div>
    <?php endwhile; else: ?>
    <p>No books found.</p>
    <?php endif; ?>
</div>

<script>
    const hamburger = document.getElementById('hamburger');
    const sideMenu = document.getElementById('sideMenu');
    const closeBtn = document.getElementById('closeBtn');

    hamburger.addEventListener('click', () => {
        sideMenu.classList.add('active');
        sideMenu.setAttribute('aria-hidden', 'false');
    });

    closeBtn.addEventListener('click', () => {
        sideMenu.classList.remove('active');
        sideMenu.setAttribute('aria-hidden', 'true');
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && sideMenu.classList.contains('active')) {
            sideMenu.classList.remove('active');
            sideMenu.setAttribute('aria-hidden', 'true');
        }
    });

    document.addEventListener('click', (e) => {
        if (!sideMenu.contains(e.target) && !hamburger.contains(e.target)) {
            if (sideMenu.classList.contains('active')) {
                sideMenu.classList.remove('active');
                sideMenu.setAttribute('aria-hidden', 'true');
            }
        }
    });
</script>

</body>
</html>
