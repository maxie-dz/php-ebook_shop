<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="uploads/images/favicon.png" type="image/png">
    <meta charset="UTF-8" />
    <title><?= $pageTitle ?? 'WordCrate' ?></title>
    <link rel="stylesheet" href="styles/style.cssv=5" />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Open+Sans&display=swap" rel="stylesheet" />
    <style>
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
        .profile-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    margin-bottom: 15px;
}

.profile-pic {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #c09572;
    box-shadow: 0 0 6px rgba(255, 255, 255, 0.4);
}

    </style>
</head>
<body>

<div class="navbar">
    <div class="nav-logo">
        <img src="uploads/logos/logo-transparent.png" alt="WordCrate Logo" />
        <h1>WordCrate</h1>
    </div>
    <div class="hamburger" id="hamburger">
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>

<div class="side-menu" id="sideMenu" aria-hidden="true" role="navigation">
    <div class="close-btn" id="closeBtn" aria-label="Close menu">&times;</div>
    <a href="homepage.php">Home</a>
    <a href="index.php">Browse</a>
    <a href="categories.php">Categories</a>
    <hr style="border: 0; border-top: 1px solid #fff3;">
 <div class="side-menu-buttons">
<?php if (isset($_SESSION['user_id'])): 
    // User is logged in
    $pfp = !empty($_SESSION['user_pfp']) ? $_SESSION['user_pfp'] : 'uploads/PFP/default.png'; 
?>
    <div class="profile-container">
        <img src="<?= htmlspecialchars($pfp) ?>" alt="Profile Picture" class="profile-pic" />
       
    </div>
    <a href="profile.php" class="side-menu-btn">Profile</a>

    <a href="logout.php" class="side-menu-btn">Logout</a>
<?php else: ?>
    <a href="login.php" class="side-menu-btn">Login</a>
    <a href="signup.php" class="side-menu-btn">Sign Up</a>
<?php endif; ?>
</div>

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
