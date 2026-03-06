<?php
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $property_id = $_POST['property_id'];
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $guests = $_POST['guests'];
    
    // Basic validation
    if (empty($checkin) || empty($checkout)) {
        die("Please select dates.");
    }

    // Calculate total price
    $stmt = $pdo->prepare("SELECT price_per_night FROM properties WHERE id = ?");
    $stmt->execute([$property_id]);
    $price_per_night = $stmt->fetchColumn();

    $start = new DateTime($checkin);
    $end = new DateTime($checkout);
    $interval = $start->diff($end);
    $days = $interval->days;

    if ($days <= 0) {
        die("Invalid dates.");
    }

    $total_price = $days * $price_per_night;

    require_once 'auth.php';
    requireLogin();
    
    $user = getCurrentUser();
    $user_id = $user['id']; 

    // Insert booking
    $stmt = $pdo->prepare("INSERT INTO bookings (property_id, user_id, check_in_date, check_out_date, total_price, status) VALUES (?, ?, ?, ?, ?, 'confirmed')");
    
    if ($stmt->execute([$property_id, $user_id, $checkin, $checkout, $total_price])) {
        // Get the last inserted ID as booking reference
        $booking_id = $pdo->lastInsertId();
        header("Location: confirmation.php?booking_id=" . $booking_id);
        exit;
    } else {
        echo "Error creating booking.";
    }
} else {
    header('Location: index.php');
}
?>
