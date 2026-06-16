<?php
require('header.php');

$query = "INSERT INTO users (username, email, password, role)VALUES (:username, :email,   :password, :role)";

$error_message = "";

if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) &&isset($_POST['confirm_password']) && isset($_POST['role'])) {

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

    if ($password != $confirm_password) {
        $error_message = "Passwords do not match!";
    } else {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $db->prepare($query);

        $success = $stmt->execute([
            ":username" => $username,
            ":email" => $email,
            ":password" => $hashedPassword,
            ":role" => $role,
        ]);

        if ($success) {
            header("Location: users.php");
            exit;
        } else {
            $error_message = "Failed to add user.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User - Travel Explorer</title>
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
            max-width: 560px;
        }

        .heading {
            margin-bottom: 24px;
        }

        .heading .title {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #c17d3c;
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

        .form-control, .form-select {
            border: 1.5px solid #e0d5c8;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 0.92rem;
            color: #2d1f0e;
            background: #fdfaf7;
            transition: border-color 0.2s;
        }

        .form-control:focus, .form-select:focus {
            border-color: #c17d3c;
            box-shadow: 0 0 0 3px rgba(193, 125, 60, 0.12);
            background: #fff;
            outline: none;
        }

        .form-control::placeholder { color: #c4b5a5; }

        .form-select { cursor: pointer; }

        .btn-submit {
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
            cursor: pointer;
        }

        .btn-submit:hover {
            background: #a8682e;
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
            background: none;
            border: none;
            color: #c17d3c;
            font-size: 0.85rem;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-back:hover { text-decoration: underline; color: #a8682e; }
    </style>
</head>
<body>

<div class="page-wrapper">

    <div class="heading">
        <p class="title">Administration</p>
        <h1>Add New User</h1>
    </div>

    <div class="form-card">

        <?php if (!empty($error_message)): ?>
            <div class="alert-error">
                <i class="bi bi-exclamation-circle-fill"></i>
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>

        <form method="POST" id="addUserForm">
            <input type="hidden" name="action" value="addNewUser">

            <div class="row g-3 mb-3">
                <div class="col">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username"
                        placeholder="e.g. banana"
                        value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>"
                        required />
                </div>
                <div class="col">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email"
                        placeholder="banana@minion.com"
                        value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                        required />
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password"
                        placeholder="password" required />
                </div>
                <div class="col">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                        placeholder="Repeat password" required />
                </div>
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="">Select a role</option>
                    <option value="user" <?= (isset($_POST['role']) && $_POST['role'] === 'user') ? 'selected' : '' ?>>User</option>
                    <option value="admin" <?= (isset($_POST['role']) && $_POST['role'] === 'admin') ? 'selected' : '' ?>>Admin</option>
                </select>
            </div>

            <button type="submit" class="btn-submit">
                <i class="bi bi-person-plus-fill me-2"></i>Add User
            </button>
        </form>

    </div>

    <div class="page-footer">
        <a href="users.php" class="btn-back">
            <i class="bi bi-arrow-left me-1"></i> Back to Users
        </a>
    </div>

</div>
</body>
</html>