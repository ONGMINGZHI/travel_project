<?php
require('header.php');

// 1. Get the city ID from the URL safely, defaulting to 0 if not present
$city_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// 2. Fetch the city details first to verify it exists and get its info
$city_stmt = $db->prepare("SELECT * FROM cities WHERE city_id = ?");
$city_stmt->execute([$city_id]);
$city = $city_stmt->fetch(PDO::FETCH_ASSOC);

// If the city doesn't exist, stop execution
if (!$city) {
    die("City not found.");
}

// 3. Fetch ONLY the hotels that belong to this specific city
$hotel_stmt = $db->prepare("
    SELECT hotels.*, cities.city_name 
    FROM hotels 
    INNER JOIN cities ON hotels.city_id = cities.city_id 
    WHERE hotels.city_id = ?
    ORDER BY hotels.star_ranking DESC, hotels.hotel_name ASC
");
$hotel_stmt->execute([$city_id]);
$hotels = $hotel_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotels in <?php echo htmlspecialchars($city['city_name']); ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .card-hotel {
            transition: 0.3s;
            height: 100%;
        }
        .card-hotel:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        .page-header {
            background: linear-gradient(135deg, #ffc107, #ffecb3); /* Warm golden tone for hospitality/hotels */
            color: #212529;
            padding: 50px 0;
            margin-bottom: 30px;
        }
        .stars {
            color: #ffc107;
            font-size: 1.1rem;
        }
    </style>
</head>
<body>

<div class="page-header text-center">
    <div class="container">
        <h1>Hotels in <?php echo htmlspecialchars($city['city_name']); ?></h1>
        <p class="mb-2">Famous for: <em><?php echo htmlspecialchars($city['famous_for']); ?></em></p>
        <a href="cities.php?id=<?php echo $city['country_id']; ?>" class="btn btn-outline-dark btn-sm">← Back to Cities</a>
    </div>
</div>

<div class="container mb-5">

    <div class="row g-4">

        <?php if(count($hotels) > 0): ?>

            <?php foreach($hotels as $hotel): ?>

                <div class="col-md-6 col-lg-4">

                    <div class="card card-hotel shadow-sm">

                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0 text-dark">
                                    <?php echo htmlspecialchars($hotel['hotel_name']); ?>
                                </h5>
                                <span class="stars" title="<?php echo $hotel['star_ranking']; ?> Stars">
                                    <?php echo str_repeat('★', $hotel['star_ranking']); ?>
                                </span>
                            </div>

                            <p class="card-text text-muted small mb-3">
                                <i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($hotel['address']); ?>
                            </p>

                            <hr>

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-secondary small">Price per night</span>
                                <span class="fs-4 fw-bold text-success">
                                    $<?php echo number_format($hotel['price_per_night']); ?>
                                </span>
                            </div>

                        </div>

                        <div class="card-footer bg-white border-0 text-end">
                            <small class="text-muted" style="font-size: 0.75rem;">
                                Registered: <?php echo date('d M Y', strtotime($hotel['created_at'])); ?>
                            </small>
                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <div class="col-12">
                <div class="alert alert-warning text-center">
                    No accommodations currently listed for <strong><?php echo htmlspecialchars($city['city_name']); ?></strong>.
                </div>
            </div>

        <?php endif; ?>

    </div>

</div>

</body>
</html>