<?php
require('header.php');

if (isset($_POST['city_name'], $_POST['population'], $_POST['famous_for'], $_POST['country_id'])) {

    $city_name = $_POST['city_name'];
    $population = $_POST['population'];
    $famous_for = $_POST['famous_for'];
    $country_id = $_POST['country_id'];

    $query = "INSERT INTO cities (city_name, population, famous_for, country_id)
              VALUES (:city_name, :population, :famous_for, :country_id)";

    $stmt = $db->prepare($query);

    $stmt->execute([
        ":city_name" => $city_name,
        ":population" => $population,
        ":famous_for" => $famous_for,
        ":country_id" => $country_id
    ]);

    header("Location: cities.php?id=" . $country_id);
    exit;
}
        ?>
    <title>Add City</title>
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
            <h1 class="h1">Add New City</h1>
        </div>
        <div class="card mb-2 p-4">
            <form method="POST" id="addCityForm">
                <input type="hidden" name="action" value="addNewCity">
                <div class="mb-3">
                    <div class="row">
                        <div class="col">
                            <label for="city_name" class="form-label">City name</label>
                            <input type="text" class="form-control" id="city_name" name="city_name" required/>
                        </div>
                        <div class="col">
                            <label for="population" class="form-label">Population</label>
                            <input type="number" class="form-control" id="population" name="population" required/>
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
                <input type="hidden" name="country_id" value="<?= $_GET['id'] ?? '' ?>">
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
        <div class="text-center">
            <a href="cities.php?id=<?= isset($_GET['id']) ? $_GET['id'] : '' ?>" class="btn btn-link btn-sm">
    ← Back to cities
</a>
        </div>
    </div>
</body>
</html>
