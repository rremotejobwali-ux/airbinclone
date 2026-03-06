<?php
require_once 'config/db.php';

// Category filtering
$category = $_GET['category'] ?? 'Trending';

// Fetch listings based on category
$stmt = $pdo->prepare("SELECT * FROM properties WHERE category = ? ORDER BY rating DESC");
$stmt->execute([$category]);
$properties = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Airbinclone - Vacation Rentals</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <?php 
    require_once 'auth.php'; 
    $user = getCurrentUser();
    ?>
    <!-- Header -->
    <header>
        <a href="index.php" class="logo">
            <i class="fa-brands fa-airbnb"></i> airbinclone
        </a>

        <div class="search-bar-sm">
            <span>Anywhere</span>
            <span>|</span>
            <span>Any week</span>
            <span>|</span>
            <span style="color: #717171;">Add guests</span>
            <div style="background: var(--primary-color); color: white; padding: 4px; border-radius: 50%;">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
        </div>

        <div class="user-menu" id="userMenuBtn">
            <i class="fa-solid fa-bars"></i>
            <div style="background: #717171; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; justify-content: center; align-items: center; overflow: hidden;">
                <?php if ($user && $user['profile_pic']): ?>
                    <img src="<?= htmlspecialchars($user['profile_pic']) ?>" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                <?php else: ?>
                    <i class="fa-solid fa-user"></i>
                <?php endif; ?>
            </div>

            <div class="dropdown-menu">
                <?php if ($user): ?>
                    <div class="dropdown-item" style="font-weight: 600;">Hello, <?= htmlspecialchars($user['name']) ?></div>
                    <div class="dropdown-divider"></div>
                    <a href="bookings.php" class="dropdown-item">My Bookings</a>
                    <a href="profile.php" class="dropdown-item">Profile</a>
                    <div class="dropdown-divider"></div>
                    <a href="logout.php" class="dropdown-item">Log out</a>
                <?php else: ?>
                    <a href="signup.php" class="dropdown-item" style="font-weight: 600;">Sign up</a>
                    <a href="login.php" class="dropdown-item">Log in</a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">Airbnb your home</a>
                    <a href="#" class="dropdown-item">Help Center</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <div class="categories-container">
        <nav class="categories-list">
            <a href="?category=Trending" class="category-link <?= $category == 'Trending' ? 'active' : '' ?>">
                <i class="fa-solid fa-fire"></i>
                <span>Trending</span>
            </a>
            <a href="?category=Beachfront" class="category-link <?= $category == 'Beachfront' ? 'active' : '' ?>">
                <i class="fa-solid fa-umbrella-beach"></i>
                <span>Beachfront</span>
            </a>
            <a href="?category=Amazing views" class="category-link <?= $category == 'Amazing views' ? 'active' : '' ?>">
                <i class="fa-solid fa-mountain-sun"></i>
                <span>Amazing views</span>
            </a>
            <a href="?category=Amazing pools" class="category-link <?= $category == 'Amazing pools' ? 'active' : '' ?>">
                <i class="fa-solid fa-person-swimming"></i>
                <span>Amazing pools</span>
            </a>
            <a href="?category=Arctic" class="category-link <?= $category == 'Arctic' ? 'active' : '' ?>">
                <i class="fa-solid fa-snowflake"></i>
                <span>Arctic</span>
            </a>
            <a href="?category=Camping" class="category-link <?= $category == 'Camping' ? 'active' : '' ?>">
                <i class="fa-solid fa-campground"></i>
                <span>Camping</span>
            </a>
            <a href="?category=Design" class="category-link <?= $category == 'Design' ? 'active' : '' ?>">
                <i class="fa-solid fa-building"></i>
                <span>Design</span>
            </a>
            <a href="?category=Castles" class="category-link <?= $category == 'Castles' ? 'active' : '' ?>">
                <i class="fa-solid fa-chess-rook"></i>
                <span>Castles</span>
            </a>
            <a href="?category=Farms" class="category-link <?= $category == 'Farms' ? 'active' : '' ?>">
                <i class="fa-solid fa-wheat-awn"></i>
                <span>Farms</span>
            </a>
        </nav>
    </div>

    <main class="main-layout">
        <section class="content-area">

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Stay somewhere special.</h1>
            <p>From villas to cabins, find your next adventure.</p>
        </div>
        <form action="listings.php" method="GET" class="main-search">
            <div class="search-input-group">
                <label for="location">Location</label>
                <input type="text" name="location" id="location" placeholder="Where are you going?">
            </div>
            
            <div class="search-input-group">
                <label for="checkin">Check in</label>
                <input type="date" name="checkin" id="checkin">
            </div>

            <div class="search-input-group">
                <label for="checkout">Check out</label>
                <input type="date" name="checkout" id="checkout">
            </div>

            <button type="submit" class="search-btn">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
    </section>

            <!-- Listings Section -->
            <div class="listings-container">
                <h2 class="section-title"><?= htmlspecialchars($category) ?> Stays</h2>
                
                <div class="listings-grid">
                    <?php foreach ($properties as $prop): ?>
            <a href="property.php?id=<?= $prop['id'] ?>" class="listing-card">
                <div class="listing-image">
                    <img src="<?= htmlspecialchars($prop['image_url']) ?>" alt="<?= htmlspecialchars($prop['title']) ?>" onerror="this.src='https://placehold.co/600x400?text=No+Image'">
                    <div class="review-badge">
                        <i class="fa-solid fa-star"></i> <?= $prop['rating'] ?>
                    </div>
                </div>
                <div class="listing-details">
                    <h3><?= htmlspecialchars($prop['location']) ?></h3>
                    <p class="listing-info"><?= htmlspecialchars($prop['title']) ?></p>
                    <div class="listing-price">$<?= number_format($prop['price_per_night'], 2) ?> <span style="font-weight: normal;">night</span></div>
                </div>
                </a>
                <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="footer-grid">
            <div class="footer-col">
                <h4>Support</h4>
                <a href="#">Help Center</a>
                <a href="#">AirCover</a>
                <a href="#">Anti-discrimination</a>
                <a href="#">Disability support</a>
                <a href="#">Cancellation options</a>
            </div>
            <div class="footer-col">
                <h4>Hosting</h4>
                <a href="#">Airbnb your home</a>
                <a href="#">AirCover for Hosts</a>
                <a href="#">Hosting resources</a>
                <a href="#">Hosting responsibly</a>
                <a href="#">Airbnb-friendly apartments</a>
            </div>
            <div class="footer-col">
                <h4>Airbinclone</h4>
                <a href="#">Newsroom</a>
                <a href="#">New features</a>
                <a href="#">Careers</a>
                <a href="#">Investors</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> Airbinclone, Inc. · <a href="#">Privacy</a> · <a href="#">Terms</a> · <a href="#">Sitemap</a></p>
            <div class="social-links">
                <i class="fa-brands fa-facebook"></i>
                <i class="fa-brands fa-twitter"></i>
                <i class="fa-brands fa-instagram"></i>
            </div>
        </div>
    </footer>

    <script src="assets/js/script.js"></script>
</body>
</html>
