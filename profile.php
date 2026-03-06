<?php
// profile.php
require_once 'config/db.php';
require_once 'auth.php';
requireLogin();

$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Airbinclone</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

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
                <a href="bookings.php" class="dropdown-item">My Bookings</a>
                <a href="profile.php" class="dropdown-item">Profile</a>
                <div class="dropdown-divider"></div>
                <a href="logout.php" class="dropdown-item">Log out</a>
            </div>
        </div>
    </header>

    <div class="property-container">
        <div style="max-width: 600px; margin: 4rem auto; background: white; border: 1px solid #ddd; border-radius: 20px; padding: 3rem; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
            <div style="text-align: center; margin-bottom: 2rem;">
                <div style="width: 120px; height: 120px; background: #717171; color: white; border-radius: 50%; display: flex; justify-content: center; align-items: center; margin: 0 auto 1.5rem; font-size: 3rem; overflow: hidden;">
                     <?php if ($user['profile_pic']): ?>
                        <img src="<?= htmlspecialchars($user['profile_pic']) ?>" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <i class="fa-solid fa-user"></i>
                    <?php endif; ?>
                </div>
                <h1 style="font-size: 2rem;"><?= htmlspecialchars($user['name']) ?></h1>
                <p style="color: #717171;"><?= htmlspecialchars($user['email']) ?></p>
            </div>

            <div style="border-top: 1px solid #eee; padding-top: 2rem;">
                <h2 style="font-size: 1.25rem; margin-bottom: 1.5rem;">Account Settings</h2>
                
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; border-bottom: 1px solid #f0f0f0;">
                    <div>
                        <div style="font-weight: 600;">Personal info</div>
                        <div style="font-size: 0.9rem; color: #717171;">Provide personal details and how we can reach you</div>
                    </div>
                    <i class="fa-solid fa-chevron-right" style="color: #717171;"></i>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; border-bottom: 1px solid #f0f0f0;">
                    <div>
                        <div style="font-weight: 600;">Login & security</div>
                        <div style="font-size: 0.9rem; color: #717171;">Update your password and secure your account</div>
                    </div>
                    <i class="fa-solid fa-chevron-right" style="color: #717171;"></i>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 0;">
                    <div>
                        <div style="font-weight: 600;">Payments & payouts</div>
                        <div style="font-size: 0.9rem; color: #717171;">Review payments, payouts, coupons, and gift cards</div>
                    </div>
                    <i class="fa-solid fa-chevron-right" style="color: #717171;"></i>
                </div>
            </div>

            <a href="logout.php" style="display: block; text-align: center; margin-top: 2rem; color: var(--primary-color); font-weight: 600; text-decoration: underline;">Log out</a>
        </div>
    </div>

    <footer>
        <p>&copy; <?= date('Y') ?> Airbinclone. All rights reserved.</p>
    </footer>

    <script src="assets/js/script.js"></script>
</body>
</html>
