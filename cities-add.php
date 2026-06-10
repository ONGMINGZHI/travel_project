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
    <title>Add City</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" />
    <style type="text/css">
        body { background: #f1f1f1; }
    </style>
</head>
<body>
    <div class="container mx-auto my-5" style="max-width: 700px;">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h1 class="h1">Add New City</h1>
        </div>
        <div class="card mb-2 p-4">
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?= $error_message ?>
                </div>
            <?php endif; ?>  
            <form method="POST" action="cities-add.php?id=<?= htmlspecialchars($country_id) ?>" id="addCityForm">
                <input type="hidden" name="action" value="addNewCity">
                <div class="mb-3">
                    <div class="row">
                        <div class="col">
                            <label for="city_name" class="form-label">City name</label>
                            <input type="text" class="form-control" id="city_name" name="city_name" required/>
                        </div>
                        <div class="col">
                            <label for="population" class="form-label">Population</label>
                            <input type="text" class="form-control" id="population" name="population" required/>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="row">
                        <div class="col">
                            <label for="famous_for" class="form-label">Famous for</label>
                            <input type="text" class="form-control" id="famous_for" name="famous_for" required/>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="country_id" value="<?= htmlspecialchars($country_id) ?>">
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
        <div class="text-center">
            <a href="cities.php?id=<?= htmlspecialchars($country_id) ?>" class="btn btn-link btn-sm">
                ← Back to Cities
            </a>
        </div>
    </div>
</body>
</html>