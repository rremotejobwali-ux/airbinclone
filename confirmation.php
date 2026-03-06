<?php
require_once 'config/db.php';

$booking_id = $_GET['booking_id'] ?? null;

if (!$booking_id) {
    header('Location: index.php');
    exit;
}

// Fetch booking details including property info
$sql = "SELECT b.*, p.title, p.location, p.image_url 
        FROM bookings b 
        JOIN properties p ON b.property_id = p.id 
        WHERE b.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$booking_id]);
$booking = $stmt->fetch();

if (!$booking) {
    echo "Booking not found.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed - Airbinclone</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php 
    require_once 'auth.php'; 
    $user = getCurrentUser();
    ?>
    <header>
        <a href="index.php" class="logo">
            <i class="fa-brands fa-airbnb"></i> airbinclone
        </a>
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
                <?php endif; ?>
            </div>
        </div>
    </header>

    <div style="max-width: 800px; margin: 4rem auto; padding: 2rem; text-align: center;">
        <div style="color: var(--primary-color); font-size: 3rem; margin-bottom: 1rem;">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <h1 style="margin-bottom: 1rem;">Your booking is confirmed!</h1>
        <p style="color: #717171; margin-bottom: 2rem;">You're all set for <strong><?= htmlspecialchars($booking['location']) ?></strong>.</p>

        <div style="text-align: left; border: 1px solid #ddd; border-radius: 12px; overflow: hidden; display: flex;">
            <div style="width: 200px; background: #eee;">
                <img src="<?= htmlspecialchars($booking['image_url']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div style="padding: 1.5rem; flex: 1;">
                <h2 style="font-size: 1.2rem; margin-bottom: 0.5rem;"><?= htmlspecialchars($booking['title']) ?></h2>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1rem; font-size: 0.95rem;">
                    <div>
                        <strong>Check-in</strong><br>
                        <?= date('D, M j, Y', strtotime($booking['check_in_date'])) ?>
                    </div>
                    <div>
                        <strong>Checkout</strong><br>
                        <?= date('D, M j, Y', strtotime($booking['check_out_date'])) ?>
                    </div>
                    <div style="margin-top: 1rem;">
                        <strong>Reference Code</strong><br>
                        #ABC<?= str_pad($booking['id'], 6, '0', STR_PAD_LEFT) ?>
                    </div>
                    <div style="margin-top: 1rem;">
                        <strong>Total Paid</strong><br>
                        $<?= number_format($booking['total_price'], 2) ?>
                    </div>
                </div>
            </div>
        </div>

        <a href="index.php" class="book-btn" style="display: inline-block; width: auto; margin-top: 3rem; padding: 1rem 3rem; text-decoration: none;">Explore more stays</a>
    </div>

</body>
</html>
