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
    <title>Update City</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" />
    <style type="text/css">
      body { background: #f1f1f1; }
    </style>
  </head>
  <body>
    <div class="container mx-auto my-5" style="max-width: 700px;">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h1">Edit City</h1>
      </div>
      <div class="card mb-2 p-4">
        <form method="POST" action="cities-edit.php?id=<?= $city_id ?>" id="updatecityForm">
          <div class="mb-3">
            <div class="row">
              <div class="col">
                <label for="city_name" class="form-label">City Name</label>
                <input type="text" class="form-control" id="city_name" name="city_name" required value="<?= htmlspecialchars($city['city_name']) ?>" />
              </div>
              <div class="col">
                <label for="population" class="form-label">Population</label>
                <input type="text" class="form-control" id="population" name="population" required value="<?= htmlspecialchars($city['population']) ?>" />
              </div>
            </div>
          </div>
          <div class="mb-3">
            <label for="famous_for" class="form-label">Famous for</label>
            <textarea class="form-control" id="famous_for" name="famous_for" required><?= htmlspecialchars($city['famous_for']) ?></textarea>
          </div>
          <input type="hidden" name="city_id" value="<?= $city_id ?>">
          <div class="d-grid">
            <button type="submit" class="btn btn-primary">Update</button>
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