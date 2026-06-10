<?php
// Start the session if it isn't automatically started in header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Access your $db object
require('header.php'); 

// Redirect to login if user isn't authenticated
// Adjust 'user' or 'user_id' to match your actual authentication structure
if (!isset($_SESSION['user'])) {
    die("You must be logged in to bookmark hotels.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hotel_id'])) {
    
    // Assuming your login session stores the logged-in user's database ID here:
    $user_id = $_SESSION['user']['user_id']; 
    $hotel_id = (int)$_POST['hotel_id'];
    $city_id = (int)$_POST['city_id'];

    try {
        // Use INSERT IGNORE to prevent database unique restriction crash errors if clicked twice
        $stmt = $db->prepare("INSERT IGNORE INTO bookmarks (user_id, hotel_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $hotel_id]);

        // rowCount() = 0 means INSERT IGNORE skipped it (already bookmarked)
        $status = $stmt->rowCount() > 0 ? "bookmarked" : "already_bookmarked";

        header("Location: hotels.php?id=" . $city_id . "&status=" . $status);
        exit();

    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
} else {
    header("Location: index.php");
    exit();
}