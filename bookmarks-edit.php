<?php
require('header.php');

if (!isset($_SESSION['user']['user_id'])) {
    header("Location: login.php");
    exit();
}

$bookmark_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = (int)$_SESSION['user']['user_id'];

if (!$bookmark_id) {
    header("Location: bookmarks.php");
    exit();
}

// Verify this bookmark actually belongs to the logged-in user before deleting
$stmt = $db->prepare("SELECT * FROM bookmarks WHERE bookmark_id = ? AND user_id = ?");
$stmt->execute([$bookmark_id, $user_id]);
$bookmark = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$bookmark) {
    // Bookmark not found or doesn't belong to this user
    header("Location: bookmarks.php");
    exit();
}

// Handle confirmed deletion via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    $stmt = $db->prepare("DELETE FROM bookmarks WHERE bookmark_id = ? AND user_id = ?");
    $stmt->execute([$bookmark_id, $user_id]);
    header("Location: bookmarks.php?status=removed");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remove Bookmark</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <style>
        body { background: #f1f1f1; }
    </style>
</head>
<body>
    <div class="container mx-auto my-5" style="max-width: 500px;">

        <div class="card shadow-sm p-4 text-center">
            <div class="mb-3">
                <i class="bi bi-bookmark-x-fill text-danger" style="font-size: 3rem;"></i>
            </div>
            <h4 class="mb-2">Remove Bookmark?</h4>
            <p class="text-muted mb-4">
                Are you sure you want to remove this hotel from your saved list? This cannot be undone.
            </p>

            <form method="POST">
                <input type="hidden" name="confirm_delete" value="1">
                <div class="d-flex justify-content-center gap-3">
                    <a href="bookmarks.php" class="btn btn-outline-secondary px-4">Cancel</a>
                    <button type="submit" class="btn btn-danger px-4">
                        <i class="bi bi-trash"></i> Yes, Remove
                    </button>
                </div>
            </form>
        </div>

    </div>
</body>
</html>