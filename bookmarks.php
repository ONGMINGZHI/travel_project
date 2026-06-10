<?php
require('header.php');

if (!isset($_SESSION['user']['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = (int)$_SESSION['user']['user_id'];

try {
    $stmt = $db->prepare("
        SELECT hotels.*, bookmarks.bookmark_id, bookmarks.created_at AS bookmarked_at 
        FROM hotels 
        INNER JOIN bookmarks ON hotels.hotel_id = bookmarks.hotel_id 
        WHERE bookmarks.user_id = ?
        ORDER BY bookmarks.created_at DESC
    ");
    $stmt->execute([$user_id]);
    $bookmarks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching bookmarks: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookmarked Places</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <style>
        .page-header {
            background: linear-gradient(135deg, #6c757d, #e9ecef);
            color: #212529;
            padding: 40px 0;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

<div class="page-header text-center">
    <div class="container">
        <h1><i class="bi bi-bookmark-heart-fill text-danger"></i> My Saved Accommodations</h1>
        <a href="index.php" class="btn btn-sm btn-outline-dark mt-2">← Back to Main Menu</a>
    </div>
</div>

<div class="container mb-5">

    <?php if (isset($_GET['status']) && $_GET['status'] === 'removed'): ?>
        <div class="alert alert-success alert-dismissible fade show text-center mb-4" role="alert">
            <i class="bi bi-check-circle-fill"></i> Bookmark removed successfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <?php if (count($bookmarks) > 0): ?>
            <?php foreach ($bookmarks as $hotel): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-dark"><?= htmlspecialchars($hotel['hotel_name']) ?></h5>
                            <p class="card-text text-muted small"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($hotel['address']) ?></p>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-secondary small">Price</span>
                                <span class="fs-5 fw-bold text-success">$<?= number_format($hotel['price_per_night']) ?>/night</span>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center pb-3">
                            <small class="text-muted" style="font-size:0.7rem">Saved: <?= date('d M Y', strtotime($hotel['bookmarked_at'])) ?></small>
                            
                            <a href="bookmarks-edit.php?id=<?= $hotel['bookmark_id'] ?>" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-trash"></i> Remove
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center my-5">
                <i class="bi bi-bookmark text-muted" style="font-size: 3rem;"></i>
                <p class="mt-3 text-muted">You haven't saved any hotels to your lists yet.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>