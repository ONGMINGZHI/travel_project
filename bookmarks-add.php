<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require('header.php'); 

if (!isset($_SESSION['user'])) {
    die("You must be logged in to bookmark hotels.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hotel_id'])) {
    
    $user_id = $_SESSION['user']['user_id']; 
    $hotel_id = (int)$_POST['hotel_id'];
    $city_id = (int)$_POST['city_id'];

    try {
        $stmt = $db->prepare("INSERT IGNORE INTO bookmarks (user_id, hotel_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $hotel_id]);

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