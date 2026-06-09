<?php

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = isset($_POST['username']) ? $_POST['username'] : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : null;

    if ($password != $confirm_password) {

      $error_message = "Passwords do not match!";

    } else {

        $db = new PDO("mysql:host=localhost;dbname=traveldb", "root", "");

        $query = "INSERT INTO users (username, email, password, role)
                  VALUES (:username, :email, :password, :role)";

        $stmt = $db->prepare($query);

        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_BCRYPT),
            ':role' => 'user'
        ]);
        header("Location: login.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>User registered</title>
    <style type="text/css">
     body { background:#0b1220; color:#e2e8f0; }
        .nav-btn { background:none; border:none; color:#94a3b8; margin-right:10px; }
        .nav-btn.active { color:#f59e0b; border-bottom:2px solid #f59e0b; }
     </style>
</head>
<body> 
    <div class="container my-5 mx-auto" style="max-width: 500px;">
      <h1 class="h1 mb-4 text-center">Sign Up a New Account</h1>

      <div class="card p-4">
  <?php if (!empty($error_message)): ?>
      <div class="alert alert-danger text-center py-2 mb-3 small">
          <?php echo htmlspecialchars($error_message); ?>
      </div>
  <?php endif; ?>
  
  <form method="POST" action="register.php">
          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required />
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" name="email" required/>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input
              type="password"
              class="form-control"
              id="password"
              name="password"
              required
            />
          </div>
          <div class="mb-3">
            <label for="confirm_password" class="form-label"
              >Confirm Password</label
            >
            <input
              type="password"
              class="form-control"
              id="confirm_password"
              name="confirm_password"
              required
            />
          </div>
          <div class="d-grid">
            <input type="submit" value="Sign Up"class="btn btn-primary w-100">
          </div>
        </form>
      </div>

      <!-- links -->
      <div
        class="d-flex justify-content-between align-items-center gap-3 mx-auto pt-3"
      >
        <a href="index.php" class="text-decoration-none small"
          ><i class="bi bi-arrow-left-circle"></i> Go back</a
        >
        <a href="login.php" class="text-decoration-none small"
          >Already have an account? Login here
          <i class="bi bi-arrow-right-circle"></i
        ></a>
      </div>
    </div>

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
      crossorigin="anonymous"
    ></script>
  </body>
</html>