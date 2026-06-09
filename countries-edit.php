<?php
require('header.php');

if (isset($_GET['id'])) {
    $country_id = $_GET['id'];

    $query = "SELECT * FROM countries WHERE country_id=:country_id";
    $stmt = $db->prepare($query);
    $stmt->execute([
        ':country_id' => $country_id
    ]);
    $country = $stmt->fetch();
}

if (isset($_POST['country_name']) && isset($_POST['continent']) && isset($_POST['description']) &&isset($_POST['image'])) {

    $country_name = $_POST['country_name'];
    $continent = $_POST['continent'];
    $description = $_POST['description'];
    $image = $_POST['image'];

        $updateQuery = "UPDATE countries SET country_name=:country_name, continent=:continent, description=:description,image=:image WHERE country_id=:country_id";
        $stmt = $db->prepare($updateQuery);
        $stmt->execute([
            ':country_name' => $country_name,
            ':continent' => $continent,
            ':description' => $description,
            ':image' => $image,
            ':country_id' => $country_id
        ]);

    header("Location:countries.php");
    exit;
}
?>
  
<!DOCTYPE html>
<html>
  <head>
    <title>Update Countries</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css"
    />
    <style type="text/css">
      body {
        background: #f1f1f1;
      }
    </style>
  </head>
  <body>
    <div class="container mx-auto my-5" style="max-width: 700px;">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h1">Edit Country</h1>
      </div>
      <div class="card mb-2 p-4">
        <form method="POST" id="updateCountryForm">
          <div class="mb-3">
            <div class="row">
              <div class="col">
                <label for="country_name" class="form-label">Country name</label>
                <input type="text" class="form-control" id="country_name" name="country_name" required value="<?= $country['country_name'] ?>" />
              </div>
              <div class="col">
                <label for="continent" class="form-label">Continent</label>
                <input type="text" class="form-control" id="continent" name="continent" required value="<?= $country['continent'] ?>" />
              </div>
            </div>
            </div>
          <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" required><?= htmlspecialchars($country['description']) ?></textarea>
           </div>
          <div class="mb-3">
            <label for="image" class="form-label">Image URL</label>
        <input type="url" class="form-control" id="image" name="image"
       value="<?= htmlspecialchars($country['image']) ?>" required>
          </div>
            <input type="hidden" name="country_id" value="<?= $country_id ?>">
          <div class="d-grid">
            <button type="submit" class="btn btn-primary">Update</button>
          </div>
        </form>
      </div>
      <div class="text-center">
        <a href="countries.php" class="btn btn-link btn-sm"
          ><i class="bi bi-arrow-left"></i> Back to Countries</a
        >
      </div>
    </div>
  </body>
</html>