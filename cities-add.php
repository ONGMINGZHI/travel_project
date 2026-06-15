<?php
require('header.php');

$country_id = $_GET['id'] ?? $_POST['country_id'] ?? null;

if (!$country_id) {
    die("Missing country ID.");
}

$error_message = "";

if (isset($_POST['city_name'], $_POST['population'], $_POST['famous_for'])) {

    $city_name  = trim($_POST['city_name']);
    $population = $_POST['population'];
    $famous_for = $_POST['famous_for'];

    $check_query = "SELECT COUNT(*) FROM cities WHERE country_id = :country_id AND LOWER(city_name) = LOWER(:city_name)";
    $check_stmt = $db->prepare($check_query);
    $check_stmt->execute([
        ":country_id" => $country_id,
        ":city_name"  => $city_name
    ]);
    
    $city_exists = $check_stmt->fetchColumn();

    if ($city_exists > 0) {
        $error_message = "The city '" . htmlspecialchars($city_name) . "' has already been added to this country.";
    } else {
        $query = "INSERT INTO cities (city_name, population, famous_for, country_id)
                  VALUES (:city_name, :population, :famous_for, :country_id)";

        $stmt = $db->prepare($query);

        $success = $stmt->execute([
            ":city_name"  => $city_name,
            ":population" => $population,
            ":famous_for" => $famous_for,
            ":country_id" => $country_id
        ]);

        if ($success) {
            header("Location: cities.php?id=" . $country_id);
            exit;
        } else {
            $error_message = "Failed to add city due to a system error.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add City - Travel Explorer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
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

        .heading { margin-bottom: 24px; }

        .heading .title {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #198754;
            margin-bottom: 6px;
        }

        .heading h1 {
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

        .form-control {
            border: 1.5px solid #e0d5c8;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 0.92rem;
            color: #2d1f0e;
            background: #fdfaf7;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            border-color: #198754;
            box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.1);
            background: #fff;
            outline: none;
        }

        .form-control::placeholder { color: #c4b5a5; }

        .btn-submit {
            background: #198754;
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
            background: #157347;
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

        .footer {
            text-align: center;
            margin-top: 18px;
        }

        .btn-back {
            color: #198754;
            font-size: 0.85rem;
            font-weight: 500;
            text-decoration: none;
        }

        .btn-back:hover { text-decoration: underline; color: #157347; }
    </style>
</head>
<body>

<div class="page-wrapper">

    <div class="heading">
        <p class="title">Cities</p>
        <h1>Add New City</h1>
    </div>

    <div class="form-card">

        <?php if (!empty($error_message)): ?>
            <div class="alert-error">
                <i class="bi bi-exclamation-circle-fill"></i>
                <?= $error_message ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="cities-add.php?id=<?= htmlspecialchars($country_id) ?>" id="addCityForm">
            <input type="hidden" name="action" value="addNewCity">

            <div class="row g-3 mb-3">
                <div class="col">
                    <label for="city_name" class="form-label">City Name</label>
                    <input type="text" class="form-control" id="city_name" name="city_name"
                        placeholder="e.g. Tokyo"
                        value="<?= isset($_POST['city_name']) ? htmlspecialchars($_POST['city_name']) : '' ?>"
                        required />
                </div>
                <div class="col">
                    <label for="population" class="form-label">Population</label>
                    <input type="text" class="form-control" id="population" name="population"
                        placeholder="e.g. 13,960,000 / 10 M"
                        value="<?= isset($_POST['population']) ? htmlspecialchars($_POST['population']) : '' ?>"
                        required />
                </div>
            </div>

            <div class="mb-3">
                <label for="famous_for" class="form-label">Famous For</label>
                <input type="text" class="form-control" id="famous_for" name="famous_for"
                    placeholder="e.g. Temples, Cherry Blossoms, Technology"
                    value="<?= isset($_POST['famous_for']) ? htmlspecialchars($_POST['famous_for']) : '' ?>"
                    required />
            </div>

            <input type="hidden" name="country_id" value="<?= htmlspecialchars($country_id) ?>">

            <button type="submit" class="btn-submit">
                <i class="bi bi-plus-lg me-2"></i>Add City
            </button>
        </form>

    </div>

    <div class="footer">
        <a href="cities.php?id=<?= htmlspecialchars($country_id) ?>" class="btn-back">
            <i class="bi bi-arrow-left me-1"></i> Back to Cities
        </a>
    </div>

</div>
</body>
</html>