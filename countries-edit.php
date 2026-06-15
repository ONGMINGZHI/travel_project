<?php
require('header.php');

$country_id = isset($_GET['id']) ? $_GET['id'] : (isset($_POST['country_id']) ? $_POST['country_id'] : null);

if (!$country_id) {
    header("Location: countries.php");
    exit;
}

$query = "SELECT * FROM countries WHERE country_id=:country_id";
$stmt = $db->prepare($query);
$stmt->execute([':country_id' => $country_id]);
$country = $stmt->fetch();

if (!$country) {
    header("Location: countries.php");
    exit;
}

if (isset($_POST['country_name']) && isset($_POST['continent']) && isset($_POST['description']) && isset($_POST['image'])) {

    $country_name = $_POST['country_name'];
    $continent    = $_POST['continent'];
    $description  = $_POST['description'];
    $image        = $_POST['image'];

    $updateQuery = "UPDATE countries SET country_name=:country_name, continent=:continent, description=:description, image=:image WHERE country_id=:country_id";
    $stmt = $db->prepare($updateQuery);
    $stmt->execute([
        ':country_name' => $country_name,
        ':continent'    => $continent,
        ':description'  => $description,
        ':image'        => $image,
        ':country_id'   => $country_id
    ]);

    header("Location: countries.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Country - Travel Explorer</title>
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
            max-width: 620px;
        }

        .heading { margin-bottom: 24px; }

        .heading .title {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #0d6efd;
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
            border-color: #0d6efd;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
            background: #fff;
            outline: none;
        }

        textarea.form-control { resize: vertical; min-height: 110px; }

        .btn-submit {
            background: #0d6efd;
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
            background: #0b5ed7;
            transform: translateY(-1px);
        }

        .footer {
            text-align: center;
            margin-top: 18px;
        }

        .btn-back {
            color: #0d6efd;
            font-size: 0.85rem;
            font-weight: 500;
            text-decoration: none;
        }

        .btn-back:hover { text-decoration: underline; color: #0b5ed7; }
    </style>
</head>
<body>

<div class="page-wrapper">

    <div class="heading">
        <p class="title">Countries</p>
        <h1>Edit Country</h1>
    </div>

    <div class="form-card">
        <form method="POST" id="updateCountryForm">

            <div class="row g-3 mb-3">
                <div class="col">
                    <label for="country_name" class="form-label">Country Name</label>
                    <input type="text" class="form-control" id="country_name" name="country_name"
                        required value="<?= htmlspecialchars($country['country_name']) ?>" />
                </div>
                <div class="col">
                    <label for="continent" class="form-label">Continent</label>
                    <input type="text" class="form-control" id="continent" name="continent"
                        required value="<?= htmlspecialchars($country['continent']) ?>" />
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description"
                    required><?= htmlspecialchars($country['description']) ?></textarea>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Image URL</label>
                <input type="url" class="form-control" id="image" name="image"
                    required value="<?= htmlspecialchars($country['image']) ?>" />
            </div>

            <input type="hidden" name="country_id" value="<?= $country_id ?>">

            <button type="submit" class="btn-submit">
                <i class="bi bi-check-lg me-2"></i>Update Country
            </button>

        </form>
    </div>

    <div class="footer">
        <a href="countries.php" class="btn-back">
            <i class="bi bi-arrow-left me-1"></i> Back to Countries
        </a>
    </div>

</div>
</body>
</html>