<?php
require('header.php');

$query = "INSERT INTO countries (country_name, continent, description, image)VALUES (:country_name, :continent, :description, :image)";

if (isset($_POST['country_name']) && isset($_POST['continent']) && isset($_POST['description']) &&isset($_POST['image'])) {

    $country_name = $_POST['country_name'];
    $continent = $_POST['continent'];
    $description = $_POST['description'];
    $image = $_POST['image'];

        $stmt = $db->prepare($query);

        $success = $stmt->execute([
            ":country_name" => $country_name,
            ":continent" => $continent,
            ":description" => $description,
            ":image" => $image,
        ]);

        if ($success) {
            header("Location: countries.php");
            exit;
        } else {
            echo "Failed to add country.";
        }
    }
?>
<!DOCTYPE html>
<html>
 <head>
    <title>Add Country</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"
        crossorigin="anonymous" />
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" />
    <style type="text/css">
        body {
            background: #f1f1f1;
        }
    </style>
    

</head>

<body>
    <div class="container mx-auto my-5" style="max-width: 700px;">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h1 class="h1">Add New Country</h1>
        </div>
        <div class="card mb-2 p-4">
            <form method="POST" id="addCountryForm">
                <input type="hidden" name="action" value="addNewCountry">
                <div class="mb-3">
                    <div class="row">
                        <div class="col">
                            <label for="country_name" class="form-label">Country Name</label>
                            <input type="text" class="form-control" id="country_name" name="country_name" required/>
                        </div>
                        <div class="col">
                            <label for="continent" class="form-label">Continent</label>
                            <input type="continent" class="form-control" id="continent" name="continent" required/>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="row">
                        <div class="col">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                        </div>
                        <div class="col">
                            <label for="image" class="form-label">Image URL</label>
                            <input type="url" class="form-control" id="image" name="image" required/>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
        <div class="text-center">
            <a href="countries.php" class="btn btn-link btn-sm"><i class="bi bi-arrow-left"></i> Back to Countries</a>
        </div>
    </div>
</body>

</html>