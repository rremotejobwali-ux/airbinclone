<?php
require_once 'config/db.php';

// Build the query based on GET parameters
$query = "SELECT * FROM properties WHERE 1=1";
$params = [];

if (!empty($_GET['location'])) {
    $query .= " AND location LIKE ?";
    $params[] = '%' . $_GET['location'] . '%';
}

if (!empty($_GET['type'])) {
    $query .= " AND type = ?";
    $params[] = $_GET['type'];
}

// Price filtering logic (simplified)
if (!empty($_GET['min_price'])) {
    $query .= " AND price_per_night >= ?";
    $params[] = $_GET['min_price'];
}
if (!empty($_GET['max_price'])) {
    $query .= " AND price_per_night <= ?";
    $params[] = $_GET['max_price'];
}

// Execute query
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$properties = $stmt->fetchAll();

// Get unique types for filter
$typesStmt = $pdo->query("SELECT DISTINCT type FROM properties");
$types = $typesStmt->fetchAll(PDO::FETCH_COLUMN);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stays in <?= !empty($_GET['location']) ? htmlspecialchars($_GET['location']) : 'Anywhere' ?> - Airbinclone</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .listings-page-container {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 2rem;
            padding: 2rem 5%;
            margin-top: 1rem;
        }
        .filter-sidebar {
            position: sticky;
            top: 100px;
            height: calc(100vh - 100px);
            overflow-y: auto;
            padding-right: 1rem;
        }
        .filter-group {
            margin-bottom: 2rem;
            border-bottom: 1px solid #ddd;
            padding-bottom: 2rem;
        }
        .filter-group h3 {
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }
        .filter-input {
            display: block;
            margin-bottom: 0.5rem;
        }
    </style>
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
        
        <form action="listings.php" method="GET" class="search-bar-sm" style="box-shadow: 0 1px 2px rgba(0,0,0,0.1);">
            <input type="text" name="location" value="<?= htmlspecialchars($_GET['location'] ?? '') ?>" placeholder="Start your search" style="border:none; outline:none; background:transparent;">
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

    <div class="listings-page-container">
        <!-- Sidebar Filters -->
        <aside class="filter-sidebar">
            <form action="listings.php" method="GET">
                <!-- Preserve location if set -->
                <?php if(!empty($_GET['location'])): ?>
                    <input type="hidden" name="location" value="<?= htmlspecialchars($_GET['location']) ?>">
                <?php endif; ?>

                <div class="filter-group">
                    <h3>Price range</h3>
                    <div style="display: flex; gap: 1rem; align-items: center;">
                        <input type="number" name="min_price" placeholder="Min" class="guest-input" style="width: 100%;" value="<?= htmlspecialchars($_GET['min_price'] ?? '') ?>">
                        <span>-</span>
                        <input type="number" name="max_price" placeholder="Max" class="guest-input" style="width: 100%;" value="<?= htmlspecialchars($_GET['max_price'] ?? '') ?>">
                    </div>
                </div>

                <div class="filter-group">
                    <h3>Type of place</h3>
                    <?php foreach ($types as $type): ?>
                    <label class="filter-input">
                        <input type="radio" name="type" value="<?= htmlspecialchars($type) ?>" <?= (isset($_GET['type']) && $_GET['type'] == $type) ? 'checked' : '' ?>>
                        <?= htmlspecialchars($type) ?>
                    </label>
                    <?php endforeach; ?>
                </div>

                <button type="submit" class="book-btn" style="padding: 0.75rem;">Apply Filters</button>
                <a href="listings.php" style="display: block; text-align: center; margin-top: 1rem; text-decoration: underline; font-size: 0.9rem;">Clear all</a>
            </form>
        </aside>

        <!-- Results Grid -->
        <div class="main-content">
            <?php if (count($properties) > 0): ?>
                <p style="margin-bottom: 1.5rem; font-weight: bold;"><?= count($properties) ?> stays found</p>
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
                            <p class="listing-info"><?= htmlspecialchars($prop['type']) ?></p>
                            <div class="listing-price">$<?= number_format($prop['price_per_night'], 2) ?> <span style="font-weight: normal;">night</span></div>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div style="text-align: center; margin-top: 4rem;">
                    <h3>No stays found</h3>
                    <p>Try adjusting your search or filters to find what you're looking for.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Mobile Responsive Adjustments -->
    <style>
        @media (max-width: 768px) {
            .listings-page-container {
                grid-template-columns: 1fr;
            }
            .filter-sidebar {
                position: static;
                height: auto;
                border-bottom: 1px solid #ddd;
                margin-bottom: 2rem;
                padding-bottom: 1rem;
            }
        }
    </style>

</body>
</html>
