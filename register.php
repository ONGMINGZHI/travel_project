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
            $check_query = "SELECT COUNT(*) FROM users WHERE username = :username";
    $check_stmt = $db->prepare($check_query);
    $check_stmt->execute([
        ":username" => $username,
    ]);
    
    $username_exists = $check_stmt->fetchColumn();

    if ($username_exists > 0) {
        $error_message = "The username '" . htmlspecialchars($username) . "' has already been added.";
    } else {
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
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Travel Explorer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            background-image: url('assets/passport.jpg');
            background-color: rgba(255,255,255,0.6);
            background-blend-mode: lighten;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 16px;
        }

        .page-layout {
            display: flex;
            width: 100%;
            max-width: 900px;
            min-height: 560px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(0,0,0,0.35);
        }

        .left-panel {
            flex: 1;
            background: #fdf6ec;
            padding: 52px 44px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            border-right: 1px solid #e8ddd0;
        }

        .left-panel .title {
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #c17d3c;
            margin-bottom: 10px;
        }

        .left-panel h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: #2d1f0e;
            line-height: 1.25;
            margin-bottom: 16px;
        }

        .left-panel p {
            color: #7a6652;
            font-size: 0.92rem;
            line-height: 1.7;
            margin-bottom: 28px;
        }

        .right-panel {
            flex: 1;
            background: #ffffff;
            padding: 48px 44px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .right-panel h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            color: #2d1f0e;
            margin-bottom: 6px;
        }

        .right-panel .subtitle {
            color: #9e8a78;
            font-size: 0.85rem;
            margin-bottom: 28px;
        }

        .form-label {
            font-size: 0.8rem;
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
            border-color: #c17d3c;
            box-shadow: 0 0 0 3px rgba(193, 125, 60, 0.12);
            background: #fff;
            outline: none;
        }

        .form-control::placeholder { color: #c4b5a5; }

        .btn-register {
            background: #c17d3c;
            border: none;
            border-radius: 8px;
            color: #fff;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 11px;
            width: 100%;
            margin-top: 6px;
            transition: background 0.2s, transform 0.15s;
            letter-spacing: 0.02em;
            cursor: pointer;
        }

        .btn-register:hover {
            background: #a8682e;
            transform: translateY(-1px);
            color: #fff;
        }

        .form-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.85rem;
            color: #9e8a78;
        }

        .form-footer a {
            color: #c17d3c;
            font-weight: 500;
            text-decoration: none;
        }

        .form-footer a:hover { text-decoration: underline; }

        .alert-danger {
            background: #ffffff;
            border: 1px solid #fecaca;
            color: #b91c1c;
            border-radius: 8px;
            font-size: 0.85rem;
            padding: 10px 14px;
            margin-bottom: 18px;
        }

        @media (max-width: 680px) {
            .page-layout { flex-direction: column; }
            .left-panel { padding: 36px 28px 28px; border-right: none; border-bottom: 1px solid #e8ddd0; }
            .right-panel { padding: 32px 28px 40px; }
        }
    </style>
</head>
<body>

<div class="page-layout">

    <div class="left-panel">
        <span class="title">Travel Explorer</span>
        <h1>Your journey starts here.</h1>
        <p>Create a free account to bookmark hotels, explore destinations, and plan your next adventure across the world.</p>
    </div>

    <div class="right-panel">
        <h2>Create an account</h2>
        <p class="subtitle">Fill in your details to get started.</p>

        <?php if (!empty($error_message)): ?>
            <div class="alert-danger d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-circle-fill"></i> <?= $error_message ?>
            </div>
        <?php endif; ?>

        <form method="POST" id="registerForm">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username"
                    placeholder="e.g. banana"
                    value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>"
                    required />
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email"
                    placeholder="banana@example.com"
                    value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                    required />
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password"
                    placeholder="Enter your password" required />
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                    placeholder="Repeat your password" required />
            </div>

            <button type="submit" class="btn-register">Register  <i class="bi bi-arrow-right"></i></button>
        </form>

        <p class="form-footer">
            Already have an account? <a href="login.php">Sign in here</a>
        </p>
    </div>

</div>
</body>
</html>