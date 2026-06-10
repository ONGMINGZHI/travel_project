<?php
require('header.php');

// Cleanly read the specific Hotel ID from either the URL bar or POST submission
$hotel_id = isset($_GET['id']) ? (int)$_GET['id'] : (isset($_POST['hotel_id']) ? (int)$_POST['hotel_id'] : null);

if (!$hotel_id) {
    die("Missing hotel ID.");
}

// Fetch the targeted hotel record out of the database structure
$query = "SELECT * FROM hotels WHERE hotel_id = :hotel_id";
$stmt = $db->prepare($query);
$stmt->execute([':hotel_id' => $hotel_id]);
$hotel = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$hotel) {
    die("Hotel not found.");
}

// Isolate the real city context key from the database record row
$city_id = $hotel['city_id'];

if (isset($_POST['hotel_name'], $_POST['star_ranking'], $_POST['address'], $_POST['price_per_night'])) {

    $hotel_name      = $_POST['hotel_name'];
    $star_ranking    = (int)$_POST['star_ranking'];
    $address         = $_POST['address'];
    $price_per_night = (float)$_POST['price_per_night'];

    $updateQuery = "UPDATE hotels 
                    SET hotel_name = :hotel_name, star_ranking = :star_ranking, address = :address, price_per_night = :price_per_night 
                    WHERE hotel_id = :hotel_id";
                    
    $stmt = $db->prepare($updateQuery);
    $success = $stmt->execute([
        ':hotel_name'      => $hotel_name,
        ':star_ranking'    => $star_ranking,
        ':address'         => $address,
        ':price_per_night' => $price_per_night,
        ':hotel_id'        => $hotel_id
    ]);

    if ($success) {
        // Safely routes the admin directly back to the active parent city group list view
        header("Location: hotels.php?id=" . $city_id);
        exit;
    } else {
        echo "<div class='alert alert-danger'>Failed to update hotel records.</div>";
    }
}
?>
  
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" />
    <style type="text/css">
        body { background: #f1f1f1; }
    </style>
</head>
<body>
    <div class="container mx-auto my-5" style="max-width: 700px;">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h1 class="h1">Edit Hotel Details</h1>
        </div>
        <div class="card mb-2 p-4">
            <form method="POST" action="hotels-edit.php?id=<?= $hotel_id ?>" id="updateHotelForm">
                <div class="mb-3">
                    <div class="row">
                        <div class="col">
                            <label for="hotel_name" class="form-label">Hotel Name</label>
                            <input type="text" class="form-control" id="hotel_name" name="hotel_name" required value="<?= htmlspecialchars($hotel['hotel_name']) ?>" />
                        </div>
                        <div class="col">
                            <label for="star_ranking" class="form-label">Star Ranking</label>
                            <select class="form-select" id="star_ranking" name="star_ranking" required>
                                <option value="5" <?= $hotel['star_ranking'] == 5 ? 'selected' : '' ?>>5 Stars</option>
                                <option value="4" <?= $hotel['star_ranking'] == 4 ? 'selected' : '' ?>>4 Stars</option>
                                <option value="3" <?= $hotel['star_ranking'] == 3 ? 'selected' : '' ?>>3 Stars</option>
                                <option value="2" <?= $hotel['star_ranking'] == 2 ? 'selected' : '' ?>>2 Stars</option>
                                <option value="1" <?= $hotel['star_ranking'] == 1 ? 'selected' : '' ?>>1 Star</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="row">
                        <div class="col">
                            <label for="address" class="form-label">Street Address</label>
                            <input type="text" class="form-control" id="address" name="address" required value="<?= htmlspecialchars($hotel['address']) ?>" />
                        </div>
                        <div class="col">
                            <label for="price_per_night" class="form-label">Price per Night ($)</label>
                            <input type="number" step="0.01" class="form-control" id="price_per_night" name="price_per_night" required value="<?= htmlspecialchars($hotel['price_per_night']) ?>" />
                        </div>
                    </div>
                </div>
                <input type="hidden" name="hotel_id" value="<?= $hotel_id ?>">
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Update Hotel</button>
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