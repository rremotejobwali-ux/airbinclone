<?php
// bookings.php
require_once 'config/db.php';
require_once 'auth.php';
requireLogin();

$user = getCurrentUser();
$user_id = $user['id'];

// Fetch user bookings with property details
$stmt = $pdo->prepare("
    SELECT b.*, p.title, p.location, p.image_url, p.type 
    FROM bookings b 
    JOIN properties p ON b.property_id = p.id 
    WHERE b.user_id = ? 
    ORDER BY b.check_in_date DESC
");
$stmt->execute([$user_id]);
$bookings = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - Airbinclone</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <!-- Header (simplified inclusion for now) -->
    <header>
        <a href="index.php" class="logo">
            <i class="fa-brands fa-airbnb"></i> airbinclone
        </a>
        <div class="user-menu" id="userMenuBtn">
            <i class="fa-solid fa-bars"></i>
            <div style="background: #717171; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; justify-content: center; align-items: center; overflow: hidden;">
                <?php if ($user['profile_pic']): ?>
                    <img src="<?= htmlspecialchars($user['profile_pic']) ?>" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                <?php else: ?>
                    <i class="fa-solid fa-user"></i>
                <?php endif; ?>
            </div>
            <div class="dropdown-menu">
                <div class="dropdown-item" style="font-weight: 600;">Hello, <?= htmlspecialchars($user['name']) ?></div>
                <div class="dropdown-divider"></div>
                <a href="bookings.php" class="dropdown-item">My Bookings</a>
                <a href="profile.php" class="dropdown-item">Profile</a>
                <div class="dropdown-divider"></div>
                <a href="logout.php" class="dropdown-item">Log out</a>
            </div>
        </div>
    </header>

    <div class="listings-container">
        <h1 class="section-title">Trips</h1>
        
        <?php if (empty($bookings)): ?>
            <div style="text-align: center; padding: 4rem 0;">
                <i class="fa-solid fa-plane-up" style="font-size: 3rem; color: #ddd; margin-bottom: 1rem;"></i>
                <h2>No trips booked... yet!</h2>
                <p style="color: #717171; margin-bottom: 2rem;">Time to dust off your bags and start planning your next adventure.</p>
                <a href="index.php" class="auth-btn" style="display: inline-block; width: auto; padding: 12px 24px;">Start searching</a>
            </div>
        <?php else: ?>
            <div class="listings-grid">
                <?php foreach ($bookings as $booking): ?>
                <div class="listing-card">
                    <div class="listing-image">
                        <img src="<?= htmlspecialchars($booking['image_url']) ?>" alt="<?= htmlspecialchars($booking['title']) ?>" onerror="this.src='https://placehold.co/600x400?text=No+Image'">
                        <div class="review-badge" style="background: var(--primary-color); color: white;">
                            <?= strtoupper($booking['status']) ?>
                        </div>
                    </div>
                    <div class="listing-details">
                        <h3><?= htmlspecialchars($booking['location']) ?></h3>
                        <p class="listing-info"><?= htmlspecialchars($booking['title']) ?></p>
                        <p style="font-size: 0.85rem; margin-top: 5px;">
                            <i class="fa-regular fa-calendar" style="margin-right: 5px;"></i>
                            <?= date('M j', strtotime($booking['check_in_date'])) ?> - <?= date('M j, Y', strtotime($booking['check_out_date'])) ?>
                        </p>
                        <div class="listing-price">Total paid: $<?= number_format($booking['total_price'], 2) ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; <?= date('Y') ?> Airbinclone. All rights reserved.</p>
    </footer>

    <script src="assets/js/script.js"></script>
</body>
</html>
