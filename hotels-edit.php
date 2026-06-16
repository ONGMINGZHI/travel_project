<?php
require('header.php');

$hotel_id = isset($_GET['id']) ? (int)$_GET['id'] : (isset($_POST['hotel_id']) ? (int)$_POST['hotel_id'] : null);

if (!$hotel_id) {
    die("Missing hotel ID.");
}

$query = "SELECT * FROM hotels WHERE hotel_id = :hotel_id";
$stmt = $db->prepare($query);
$stmt->execute([':hotel_id' => $hotel_id]);
$hotel = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$hotel) {
    die("Hotel not found.");
}

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Hotel - Travel Explorer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
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
        <h1>Edit Hotel Details</h1>
    </div>

    <div class="form-card">
        <form method="POST" action="hotels-edit.php?id=<?= $hotel_id ?>" id="updateHotelForm">

            <div class="row g-3 mb-3">
                <div class="col">
                    <label for="hotel_name" class="form-label">Hotel Name</label>
                    <input type="text" class="form-control" id="hotel_name" name="hotel_name"
                        required value="<?= htmlspecialchars($hotel['hotel_name']) ?>" />
                </div>
                <div class="col">
                    <label for="star_ranking" class="form-label">Star Ranking</label>
                    <select class="form-select" id="star_ranking" name="star_ranking" required>
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <option value="<?= $i ?>" <?= $hotel['star_ranking'] == $i ? 'selected' : '' ?>>
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
                        required value="<?= htmlspecialchars($hotel['address']) ?>" />
                </div>
                <div class="col">
                    <label for="price_per_night" class="form-label">Price per Night ($)</label>
                    <input type="number" step="0.01" class="form-control" id="price_per_night" name="price_per_night"
                        required value="<?= htmlspecialchars($hotel['price_per_night']) ?>" />
                </div>
            </div>

            <input type="hidden" name="hotel_id" value="<?= $hotel_id ?>">

            <button type="submit" class="btn-submit">
                <i class="bi bi-check-lg me-2"></i>Update Hotel
            </button>

        </form>
    </div>

    <div class="page-footer">
        <a href="hotels.php?id=<?= htmlspecialchars($city_id) ?>" class="btn-back">
            <i class="bi bi-arrow-left me-1"></i> Back to Hotels
        </a>
    </div>

</div>
</body>
</html>