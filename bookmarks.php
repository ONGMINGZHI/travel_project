<?php
require('header.php');

// 1. Simulating the logged-in user ID (Replace with your actual session variable)
$current_user_id = 1; 

// 2. Fetch bookmarked hotels along with their City and Country details using INNER JOINs
$query = "
    SELECT 
        b.bookmark_id, b.created_at AS bookmarked_at,
        h.hotel_id, h.hotel_name, h.star_ranking, h.price_per_night, h.address,
        c.city_name,
        co.country_name
    FROM bookmarks b
    INNER JOIN hotels h ON b.hotel_id = h.hotel_id
    INNER JOIN cities c ON h.city_id = c.city_id
    INNER JOIN countries co ON c.country_id = co.country_id
    WHERE b.user_id = ?
    ORDER BY b.created_at DESC
";

$stmt = $db->prepare($query);
$stmt->execute([$current_user_id]);
$bookmarks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Saved Bookmarks</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .card-bookmark {
            transition: 0.3s;
            height: 100%;
        }
        .card-bookmark:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }
        .page-header {
            background: linear-gradient(135deg, #e06377, #f3a6b4); /* Pink/Rose theme for favorites/bookmarks */
            color: white;
            padding: 50px 0;
            margin-bottom: 30px;
        }
        .stars {
            color: #ffc107;
        }
    </style>
</head>
<body>

<div class="page-header text-center">
    <div class="container">
        <h1>My Saved Hotels</h1>
        <p>Your personalized travel shortlist</p>
        <a href="countries.php" class="btn btn-outline-light btn-sm mt-2">Browse More Countries</a>
    </div>
</div>

<div class="container mb-5">
    <div class="row g-4">

        <?php if(count($bookmarks) > 0): ?>
            <?php foreach($bookmarks as $bookmark): ?>

                <div class="col-md-6 col-lg-4">
                    <div class="card card-bookmark shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            
                            <div class="mb-2">
                                <span class="badge bg-secondary">
                                    <?php echo htmlspecialchars($bookmark['country_name']); ?>
                                </span>
                                <span class="badge bg-success">
                                    <?php echo htmlspecialchars($bookmark['city_name']); ?>
                                </span>
                            </div>

                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title text-dark mb-0">
                                    <?php echo htmlspecialchars($bookmark['hotel_name']); ?>
                                </h5>
                                <span class="stars">
                                    <?php echo str_repeat('★', $bookmark['star_ranking']); ?>
                                </span>
                            </div>
                            
                            <p class="card-text text-muted small mb-4">
                                <?php echo htmlspecialchars($bookmark['address']); ?>
                            </p>
                            
                            <div class="mt-auto">
                                <hr>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted d-block">Price per night</small>
                                        <span class="fs-5 fw-bold text-success">$<?php echo number_format($bookmark['price_per_night']); ?></span>
                                    </div>
                                    <a href="hotels.php?id=<?php echo $bookmark['hotel_id']; ?>" class="btn btn-sm btn-outline-danger">
                                        View Deal
                                    </a>
                                </div>
                            </div>

                        </div>
                        
                        <div class="card-footer bg-light border-0">
                            <small class="text-muted" style="font-size: 0.75rem;">
                                Saved on: <?php echo date('d M Y', strtotime($bookmark['bookmarked_at'])); ?>
                            </small>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <div class="alert alert-light border shadow-sm p-4 inline-block" style="max-width: 500px; margin: 0 auto;">
                    <h4 class="text-muted">No bookmarks found</h4>
                    <p class="text-muted small">You haven't saved any hotels to your list yet.</p>
                    <a href="countries.php" class="btn btn-danger btn-sm mt-2">Explore Destinations</a>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

</body>
</html>