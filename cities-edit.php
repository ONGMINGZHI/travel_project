<?php
require('header.php');

$city_id = isset($_GET['id']) ? (int)$_GET['id'] : (isset($_POST['city_id']) ? (int)$_POST['city_id'] : null);

if (!$city_id) {
    die("Missing city ID.");
}

$query = "SELECT * FROM cities WHERE city_id = :city_id";
$stmt = $db->prepare($query);
$stmt->execute([':city_id' => $city_id]);
$city = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$city) {
    die("City not found.");
}

$country_id = $city['country_id'];

if (isset($_POST['city_name'], $_POST['population'], $_POST['famous_for'])) {

    $city_name  = $_POST['city_name'];
    $population = $_POST['population'];
    $famous_for = $_POST['famous_for'];

    $updateQuery = "UPDATE cities 
                    SET city_name = :city_name, population = :population, famous_for = :famous_for 
                    WHERE city_id = :city_id";
                    
    $stmt = $db->prepare($updateQuery);
    $success = $stmt->execute([
        ':city_name'  => $city_name,
        ':population' => $population,
        ':famous_for' => $famous_for,
        ':city_id'    => $city_id
    ]);

    if ($success) {
        header("Location: cities.php?id=" . $country_id);
        exit;
    } else {
        echo "<div class='alert alert-danger'>Failed to update city.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit City - Travel Explorer</title>
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

        textarea.form-control { resize: vertical; min-height: 100px; }

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
        <h1>Edit City</h1>
    </div>

    <div class="form-card">
        <form method="POST" action="cities-edit.php?id=<?= $city_id ?>" id="updatecityForm">

            <div class="row g-3 mb-3">
                <div class="col">
                    <label for="city_name" class="form-label">City Name</label>
                    <input type="text" class="form-control" id="city_name" name="city_name"
                        required value="<?= htmlspecialchars($city['city_name']) ?>" />
                </div>
                <div class="col">
                    <label for="population" class="form-label">Population</label>
                    <input type="text" class="form-control" id="population" name="population"
                        required value="<?= htmlspecialchars($city['population']) ?>" />
                </div>
            </div>

            <div class="mb-3">
                <label for="famous_for" class="form-label">Famous For</label>
                <textarea class="form-control" id="famous_for" name="famous_for"
                    required><?= htmlspecialchars($city['famous_for']) ?></textarea>
            </div>

            <input type="hidden" name="city_id" value="<?= $city_id ?>">

            <button type="submit" class="btn-submit">
                <i class="bi bi-check-lg me-2"></i>Update City
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