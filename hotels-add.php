<?php
require('header.php');

// Grab the parent context city ID from the query string or POST submission payload
$city_id = $_GET['id'] ?? $_POST['city_id'] ?? null;

if (!$city_id) {
    die("Missing city ID.");
}

$error_message = "";

if (isset($_POST['hotel_name'], $_POST['star_ranking'], $_POST['address'], $_POST['price_per_night'])) {
    
    $hotel_name      = trim($_POST['hotel_name']);
    $star_ranking    = (int)$_POST['star_ranking'];
    $address         = trim($_POST['address']);
    $price_per_night = (float)$_POST['price_per_night'];

    // Check if this hotel name already exists in this specific city (Case-Insensitive)
    $check_stmt = $db->prepare("SELECT COUNT(*) FROM hotels WHERE city_id = :city_id AND LOWER(hotel_name) = LOWER(:hotel_name)");
    $check_stmt->execute([
        ':city_id'    => $city_id,
        ':hotel_name' => $hotel_name
    ]);

    if ($check_stmt->fetchColumn() > 0) {
        $error_message = "The hotel '" . htmlspecialchars($hotel_name) . "' has already been added to this city.";
    } else {
        // Proceed with insertion if it's unique
        $query = "INSERT INTO hotels (hotel_name, star_ranking, address, price_per_night, city_id) 
                  VALUES (:hotel_name, :star_ranking, :address, :price_per_night, :city_id)";
        $stmt = $db->prepare($query);
        $success = $stmt->execute([
            ':hotel_name'      => $hotel_name,
            ':star_ranking'    => $star_ranking,
            ':address'         => $address,
            ':price_per_night' => $price_per_night,
            ':city_id'         => $city_id
        ]);

        if ($success) {
            header("Location: hotels.php?id=" . $city_id);
            exit;
        } else {
            $error_message = "Failed to add hotel due to a database exception error.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" />
    <style type="text/css">
        body { background: #f1f1f1; }
    </style>
</head>
<body>
    <div class="container mx-auto my-5" style="max-width: 700px;">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h1 class="h1">Add New Hotel</h1>
        </div>
        <div class="card mb-2 p-4">
            
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger d-flex align-items-center mb-3" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div><?= $error_message ?></div>
                </div>
            <?php endif; ?>

            <form method="POST" action="hotels-add.php?id=<?= htmlspecialchars($city_id) ?>" id="addHotelForm">
                <div class="mb-3">
                    <div class="row">
                        <div class="col">
                            <label for="hotel_name" class="form-label">Hotel Name</label>
                            <input type="text" class="form-control" id="hotel_name" name="hotel_name" value="<?= isset($_POST['hotel_name']) ? htmlspecialchars($_POST['hotel_name']) : '' ?>" required/>
                        </div>
                        <div class="col">
                            <label for="star_ranking" class="form-label">Star Ranking</label>
                            <select class="form-select" id="star_ranking" name="star_ranking" required>
                                <option value="5">5 Stars</option>
                                <option value="4">4 Stars</option>
                                <option value="3">3 Stars</option>
                                <option value="2">2 Stars</option>
                                <option value="1">1 Star</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="row">
                        <div class="col">
                            <label for="address" class="form-label">Street Address</label>
                            <input type="text" class="form-control" id="address" name="address" value="<?= isset($_POST['address']) ? htmlspecialchars($_POST['address']) : '' ?>" required/>
                        </div>
                        <div class="col">
                            <label for="price_per_night" class="form-label">Price per Night ($)</label>
                            <input type="number" step="0.01" class="form-control" id="price_per_night" name="price_per_night" value="<?= isset($_POST['price_per_night']) ? htmlspecialchars($_POST['price_per_night']) : '' ?>" required/>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="city_id" value="<?= htmlspecialchars($city_id) ?>">
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Add Hotel</button>
                </div>
            </form>
        </div>
        <div class="text-center">
            <a href="hotels.php?id=<?= htmlspecialchars($city_id) ?>" class="btn btn-link btn-sm">
                ← Back to Hotels
            </a>
        </div>
    </div>
</body>
</html>