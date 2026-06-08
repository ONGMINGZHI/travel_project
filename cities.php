<?php
require('header.php');

// 1. Get the country ID from the URL safely, defaulting to 0 if not present
$country_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// 2. Fetch the country details first to verify it exists
$country_stmt = $db->prepare("SELECT * FROM countries WHERE country_id = ?");
$country_stmt->execute([$country_id]);
$country = $country_stmt->fetch(PDO::FETCH_ASSOC);

if (!$country) {
    die("Country not found.");
}

// 3. Fetch ONLY the cities matching this country ID (No JOIN needed here since hotels are on the next page!)
$city_stmt = $db->prepare("
    SELECT * FROM cities 
    WHERE country_id = ? 
    ORDER BY city_name ASC
");
$city_stmt->execute([$country_id]);
$cities = $city_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cities in <?php echo htmlspecialchars($country['country_name']); ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .card-city {
            transition: 0.3s;
            height: 100%;
        }
        .card-city:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }
        .page-header {
            background: linear-gradient(135deg, #198754, #a3cfbb);
            color: white;
            padding: 50px 0;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

<div class="page-header text-center">
    <div class="container">
        <h1>Cities in <?php echo htmlspecialchars($country['country_name']); ?></h1>
        <p>Explore local destinations and top-rated accommodations</p>
        <a href="countries.php" class="btn btn-outline-light btn-sm mt-2">← Back to Countries</a>
    </div>
</div>

<div class="container mb-5">
    <div class="row g-4">

        <?php if(count($cities) > 0): ?>
            <?php foreach($cities as $city): ?>

                <div class="col-md-6 col-lg-4">
                    <div class="card card-city shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            
                            <h4 class="card-title text-success mb-3">
                                <?php echo htmlspecialchars($city['city_name']); ?>
                            </h4>
                            
                            <p class="card-text mb-4" style="font-size: 0.9rem;">
                                <strong>Population:</strong> <?php echo htmlspecialchars($city['population']); ?> <br>
                                <strong>Famous For:</strong> <?php echo htmlspecialchars($city['famous_for']); ?>
                            </p>
                            
                            <a href="hotels.php?id=<?php echo $city['city_id']; ?>" class="btn btn-success btn-sm w-100 mt-auto">
                                View Hotels
                            </a>

                        </div>
                        
                        <div class="card-footer bg-light border-0">
                            <small class="text-muted" style="font-size: 0.75rem;">
                                City added: <?php echo date('d M Y', strtotime($city['created_at'])); ?>
                            </small>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info text-center">
                    No cities found for <strong><?php echo htmlspecialchars($country['country_name']); ?></strong>.
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

</body>
</html>