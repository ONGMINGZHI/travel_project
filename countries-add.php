<?php
require('header.php');

$query = "INSERT INTO countries (country_name, continent, description, image) VALUES (:country_name, :continent, :description, :image)";

$error_message = "";

if (isset($_POST['country_name']) && isset($_POST['continent']) && isset($_POST['description']) && isset($_POST['image'])) {

    $country_name = trim($_POST['country_name']);
    $continent    = $_POST['continent'];
    $description  = $_POST['description'];
    $image        = $_POST['image'];

    $check_stmt = $db->prepare("SELECT COUNT(*) FROM countries WHERE LOWER(country_name) = LOWER(?)");
    $check_stmt->execute([$country_name]);
    
    if ($check_stmt->fetchColumn() > 0) {
        $error_message = "The country '" . htmlspecialchars($country_name) . "' has already been added.";
    } else {
        $stmt = $db->prepare($query);

        $success = $stmt->execute([
            ":country_name" => $country_name,
            ":continent"    => $continent,
            ":description"  => $description,
            ":image"        => $image,
        ]);

        if ($success) {
            header("Location: countries.php");
            exit;
        } else {
            $error_message = "Failed to add country due to a database layout error.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Country - Travel Explorer</title>
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
            max-width: 620px;
        }

        .page-heading { margin-bottom: 24px; }

        .page-heading .eyebrow {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #0d6efd;
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

        .form-control::placeholder { color: #c4b5a5; }

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

    <div class="page-heading">
        <p class="eyebrow">Countries</p>
        <h1>Add New Country</h1>
    </div>

    <div class="form-card">

        <?php if (!empty($error_message)): ?>
            <div class="alert-error">
                <i class="bi bi-exclamation-circle-fill"></i>
                <?= $error_message ?>
            </div>
        <?php endif; ?>

        <form method="POST" id="addCountryForm">
            <input type="hidden" name="action" value="addNewCountry">

            <div class="row g-3 mb-3">
                <div class="col">
                    <label for="country_name" class="form-label">Country Name</label>
                    <input type="text" class="form-control" id="country_name" name="country_name"
                        placeholder="e.g. Japan"
                        value="<?= isset($_POST['country_name']) ? htmlspecialchars($_POST['country_name']) : '' ?>"
                        required />
                </div>
                <div class="col">
                    <label for="continent" class="form-label">Continent</label>
                    <input type="text" class="form-control" id="continent" name="continent"
                        placeholder="e.g. Asia"
                        value="<?= isset($_POST['continent']) ? htmlspecialchars($_POST['continent']) : '' ?>"
                        required />
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description"
                        placeholder="Brief description of the country..." required><?= isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '' ?></textarea>
                </div>
                <div class="col">
                    <label for="image" class="form-label">Image URL</label>
                    <input type="url" class="form-control" id="image" name="image"
                        placeholder="https://example.com/image.jpg"
                        value="<?= isset($_POST['image']) ? htmlspecialchars($_POST['image']) : '' ?>"
                        required />
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="bi bi-plus-lg me-2"></i>Add Country
            </button>
        </form>

    </div>

    <div class="page-footer">
        <a href="countries.php" class="btn-back">
            <i class="bi bi-arrow-left me-1"></i> Back to Countries
        </a>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>