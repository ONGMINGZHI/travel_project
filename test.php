<?php
require('header.php');

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

    $check_stmt = $db->prepare("SELECT COUNT(*) FROM hotels WHERE city_id = :city_id AND LOWER(hotel_name) = LOWER(:hotel_name)");
    $check_stmt->execute([
        ':city_id'    => $city_id,
        ':hotel_name' => $hotel_name
    ]);

    if ($check_stmt->fetchColumn() > 0) {
        $error_message = "The hotel '" . htmlspecialchars($hotel_name) . "' has already been added to this city.";
    } else {
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
    <title>Add Hotel - Travel Explorer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            background-image: url('passport-stamps.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-color: #fdf6ec;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 16px;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: rgba(253, 246, 236, 0.82);
            z-index: 0;
        }

        .page-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 580px;
        }

        .page-heading { margin-bottom: 24px; }

        .page-heading .eyebrow {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #b45309;
            margin-bottom: 6px;
        }

        .page-heading h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.9rem;
            color: #2d1f0e;
        }

        .form-card {
            background: #fff;
            border: 1.5px solid #e8ddd0;
            border-radius: 16px;
            padding: 36px 40px;
        }

        .form-label {
            font-size: 0.78rem;
            font-weight: 600;
            color: #5a4a3a;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 6px;
        }

        .form-control, .form-select {
            border: 1.5px solid #e0d5c8;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 0.92rem;
            color: #2d1f0e;
            background: #fdfaf7;
            transition: border-color 0.2s;
        }

        .form-control:focus, .form-select:focus {
            border-color: #d97706;
            box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.12);
            background: #fff;
            outline: none;
        }

        .form-control::placeholder { color: #c4b5a5; }
        .form-select { cursor: pointer; }

        .btn-submit {
            background: #d97706;
            border: none;
            border-radius: 8px;
            color: #fff;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 11px;
            width: 100%;
            margin-top: 6px;
            transition: background 0.2s, transform 0.15s;
            cursor: pointer;
        }

        .btn-submit:hover {
            background: #b45309;
            transform: translateY(-1px);
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            border-radius: 8px;
            font-size: 0.85rem;
            padding: 10px 14px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .page-footer {
            text-align: center;
            margin-top: 18px;
        }

        .btn-back {
            color: #d97706;
            font-size: 0.85rem;
            font-weight: 500;
            text-decoration: none;
        }

        .btn-back:hover { text-decoration: underline; color: #b45309; }
    </style>
</head>
<body>

<div class="page-wrapper">

    <div class="page-heading">
        <p class="eyebrow">Hotels</p>
        <h1>Add New Hotel</h1>
    </div>

    <div class="form-card">

        <?php if (!empty($error_message)): ?>
            <div class="alert-error">
                <i class="bi bi-exclamation-circle-fill"></i>
                <?= $error_message ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="hotels-add.php?id=<?= htmlspecialchars($city_id) ?>" id="addHotelForm">

            <div class="row g-3 mb-3">
                <div class="col">
                    <label for="hotel_name" class="form-label">Hotel Name</label>
                    <input type="text" class="form-control" id="hotel_name" name="hotel_name"
                        placeholder="e.g. Grand Hyatt"
                        value="<?= isset($_POST['hotel_name']) ? htmlspecialchars($_POST['hotel_name']) : '' ?>"
                        required />
                </div>
                <div class="col">
                    <label for="star_ranking" class="form-label">Star Ranking</label>
                    <select class="form-select" id="star_ranking" name="star_ranking" required>
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <option value="<?= $i ?>" <?= (isset($_POST['star_ranking']) && $_POST['star_ranking'] == $i) ? 'selected' : '' ?>>
                                <?= $i ?> Star<?= $i > 1 ? 's' : '' ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col">
                    <label for="address" class="form-label">Street Address</label>
                    <input type="text" class="form-control" id="address" name="address"
                        placeholder="e.g. 123 Main Street"
                        value="<?= isset($_POST['address']) ? htmlspecialchars($_POST['address']) : '' ?>"
                        required />
                </div>
                <div class="col">
                    <label for="price_per_night" class="form-label">Price per Night ($)</label>
                    <input type="number" step="0.01" class="form-control" id="price_per_night" name="price_per_night"
                        placeholder="e.g. 120.00"
                        value="<?= isset($_POST['price_per_night']) ? htmlspecialchars($_POST['price_per_night']) : '' ?>"
                        required />
                </div>
            </div>

            <input type="hidden" name="city_id" value="<?= htmlspecialchars($city_id) ?>">

            <button type="submit" class="btn-submit">
                <i class="bi bi-plus-lg me-2"></i>Add Hotel
            </button>
        </form>

    </div>

    <div class="page-footer">
        <a href="hotels.php?id=<?= htmlspecialchars($city_id) ?>" class="btn-back">
            <i class="bi bi-arrow-left me-1"></i> Back to Hotels
        </a>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>