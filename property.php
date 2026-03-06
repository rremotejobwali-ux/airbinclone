<?php
require_once 'config/db.php';

// Get property ID
$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php');
    exit;
}

// Fetch property details
$stmt = $pdo->prepare("SELECT * FROM properties WHERE id = ?");
$stmt->execute([$id]);
$property = $stmt->fetch();

if (!$property) {
    echo "Property not found.";
    exit;
}

// Fetch reviews (dummy data structure for now or reuse schema if populated)
// We defined a reviews table but it's empty. Let's show some static UI for reviews if none exist.
$stmtReviews = $pdo->prepare("SELECT * FROM reviews WHERE property_id = ?");
$stmtReviews->execute([$id]);
$reviews = $stmtReviews->fetchAll();

// Amenities to array
$amenities = explode(',', $property['amenities']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($property['title']) ?> - Airbinclone</title>
    <link rel="stylesheet" href="assets/css/style.css">
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
        <form action="listings.php" method="GET" class="search-bar-sm">
            <input type="text" name="location" placeholder="Search" style="border:none; outline:none; background:transparent;">
            <button type="submit" style="background:var(--primary-color); color:white; border:none; border-radius:50%; width:32px; height:32px; cursor:pointer;"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
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

    <div class="property-container">
        <div class="property-header">
            <h1><?= htmlspecialchars($property['title']) ?></h1>
            <div class="property-meta">
                <span><i class="fa-solid fa-star"></i> <?= $property['rating'] ?> · <?= count($reviews) ?> reviews · <?= htmlspecialchars($property['location']) ?></span>
                <span>Share · Save</span>
            </div>
        </div>

        <div class="property-gallery">
            <div class="main-img">
                <img src="<?= htmlspecialchars($property['image_url']) ?>" alt="Main Image" onerror="this.src='https://placehold.co/800x600?text=Main+Image'">
            </div>
            <div class="sub-images">
                <div class="sub-img"><img src="https://placehold.co/400x300?text=Room+1" alt="Room 1"></div>
                <div class="sub-img"><img src="https://placehold.co/400x300?text=Room+2" alt="Room 2"></div>
            </div>
        </div>

        <div class="property-content">
            <div class="property-info">
                <div style="border-bottom: 1px solid #ddd; padding-bottom: 2rem; margin-bottom: 2rem;">
                    <h2>Entire <?= htmlspecialchars($property['type']) ?> hosted by Host</h2>
                    <p>4 guests · 2 bedrooms · 2 beds · 1 bath</p>
                </div>

                <div style="border-bottom: 1px solid #ddd; padding-bottom: 2rem; margin-bottom: 2rem;">
                    <h3>What this place offers</h3>
                    <ul style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1rem;">
                        <?php foreach($amenities as $amenity): ?>
                        <li><i class="fa-solid fa-check"></i> <?= htmlspecialchars(trim($amenity)) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="description">
                    <h3>About this space</h3>
                    <p style="margin-top: 1rem;"><?= nl2br(htmlspecialchars($property['description'])) ?></p>
                </div>
            </div>

            <!-- Booking Widget -->
            <div class="booking-card">
                <div class="booking-price">
                    $<?= number_format($property['price_per_night'], 2) ?> <span style="font-weight: normal; font-size: 1rem;">night</span>
                </div>

                <form action="book.php" method="POST" class="booking-form">
                    <input type="hidden" name="property_id" value="<?= $property['id'] ?>">
                    <input type="hidden" name="price_per_night" value="<?= $property['price_per_night'] ?>">
                    
                    <div class="date-inputs">
                        <div class="date-input">
                            <label for="checkin">CHECK-IN</label>
                            <input type="date" name="checkin" id="checkin" required>
                        </div>
                        <div class="date-input">
                            <label for="checkout">CHECKOUT</label>
                            <input type="date" name="checkout" id="checkout" required>
                        </div>
                    </div>

                    <div class="guest-input">
                        <label style="font-size: 0.7rem; font-weight: bold; display: block;">GUESTS</label>
                        <select name="guests" style="width: 100%; border: none; outline: none; margin-top: 5px;">
                            <option value="1">1 guest</option>
                            <option value="2">2 guests</option>
                            <option value="3">3 guests</option>
                            <option value="4">4 guests</option>
                        </select>
                    </div>

                    <button type="submit" class="book-btn">Reserve</button>
                    
                    <p style="text-align: center; font-size: 0.9rem; margin-top: 1rem;">You won't be charged yet</p>
                    
                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #ddd; display: flex; justify-content: space-between; font-weight: bold;">
                        <span>Total</span>
                        <span>$ --</span>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; <?= date('Y') ?> Airbinclone</p>
    </footer>

    <script src="assets/js/script.js"></script>
    <script>
        // Dynamic price calculation
        const pricePerNight = <?= $property['price_per_night'] ?>;
        const checkinInput = document.getElementById('checkin');
        const checkoutInput = document.getElementById('checkout');
        const totalDisplay = document.querySelector('.booking-form span:last-child');

        function calculateTotal() {
            if (checkinInput.value && checkoutInput.value) {
                const start = new Date(checkinInput.value);
                const end = new Date(checkoutInput.value);
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                
                if (diffDays > 0) {
                    const total = diffDays * pricePerNight;
                    totalDisplay.textContent = '$' + total.toFixed(2);
                } else {
                    totalDisplay.textContent = '$ --';
                }
            }
        }

        checkinInput.addEventListener('change', calculateTotal);
        checkoutInput.addEventListener('change', calculateTotal);

        // Wow effect for booking button
        const bookBtn = document.querySelector('.book-btn');
        if (bookBtn) {
            bookBtn.addEventListener('click', (e) => {
                if (checkinInput.value && checkoutInput.value) {
                    bookBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Processing...';
                    bookBtn.style.opacity = '0.8';
                }
            });
        }
    </script>
</body>
</html>
