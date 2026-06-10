<?php
require('header.php');

$stmt = $db->prepare("SELECT * FROM countries ORDER BY country_name ASC");
$stmt->execute();
$countries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
<title>Countries</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.card-country{
    transition:0.3s;
    height:100%;
}

.card-country:hover{
    transform:translateY(-5px);
    box-shadow:0 8px 20px rgba(0,0,0,0.15);
}

.country-image{
    height:220px;
    object-fit:cover;
}

.page-header{
    background:linear-gradient(135deg,#0d6efd,#6ea8fe);
    color:white;
    padding:50px 0;
    margin-bottom:30px;
}
</style>

</head>
<body>

<div class="page-header text-center">
    <div class="container">
        <h1>Countries</h1>
        <p>Explore different countries and famous cities</p>
        <?php if (strtolower($_SESSION['user']['role']) === 'admin'): ?>
        <a href="countries-add.php" class="btn btn-warning btn-sm mt-2">Add Countries</a>
        <?php endif; ?>
        <a href="index.php" class="btn btn-outline-light btn-sm mt-2">← Back to Dashboard</a>
    </div>
</div>

<div class="container">
    <div class="row g-4">

        <?php if(count($countries) > 0): ?>

            <?php foreach($countries as $country): ?>

                <div class="col-md-6 col-lg-4">

                    <div class="card card-country">

                        <?php if(!empty($country['image'])): ?>
                            <img
                                src="<?php echo htmlspecialchars($country['image']); ?>"
                                class="card-img-top country-image"
                                alt="<?php echo htmlspecialchars($country['country_name']); ?>">
                        <?php else: ?>
                            <img
                                src="https://via.placeholder.com/400x220?text=No+Image"
                                class="card-img-top country-image"
                                alt="No Image">
                        <?php endif; ?>

                        <div class="card-body">

                            <h5 class="card-title">
                                <?php echo htmlspecialchars($country['country_name']); ?>
                            </h5>

                            <span class="badge bg-primary mb-2">
                                <?php echo htmlspecialchars($country['continent']); ?>
                            </span>
                            <p class="card-text"> <?php $desc = $country['description']; echo htmlspecialchars(strlen($desc) > 100 ? substr($desc,0,100).'...' : $desc); ?> </p>
                        </div>

                        <div class="card-footer bg-white">
                                <a href="cities.php?id=<?php echo $country['country_id']; ?>" class="btn btn-success btn-sm w-100 mt-auto">
                                View Cities
                            </a>
                            <div class="card-footer bg-light border-0">
                            <small class="text-muted">
                                Created:
                                <?php
                                    echo !empty($country['created_at'])
                                        ? date('d M Y', strtotime($country['created_at']))
                                        : '-';
                                ?>
                            </small>
                        </div>
                            <?php if (strtolower($_SESSION['user']['role']) === 'admin'): ?>
<a
                                            href="countries-edit.php?id=<?= $country['country_id'] ?>"
                                            class="btn btn-info btn-sm ms-2 float-end"
                                            title="Edit Countries"><i class="bi bi-pencil"></i></a>       
                             <?php endif; ?>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <div class="col-12">
                <div class="alert alert-warning">
                    No countries found.
                </div>
            </div>

        <?php endif; ?>

    </div>

</div>

</body>
</html>