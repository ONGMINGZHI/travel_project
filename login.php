<?php
session_start();

$username = isset($_POST['username']) ? $_POST['username'] : null;
$password = isset($_POST['password']) ? $_POST['password'] : null;

function render_error_page($message, $icon = 'bi-exclamation-triangle-fill') {
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Travel Explorer - Notice</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
        <style>
            * { box-sizing: border-box; margin: 0; padding: 0; }
            body {
                min-height: 100vh;
                font-family: Inter, sans-serif;
                background-image: url("passport-stamps.jpg");
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 40px 16px;
            }
            .error-card {
                background: #fff;
                border-radius: 16px;
                padding: 48px 40px;
                max-width: 420px;
                width: 100%;
                text-align: center;
                box-shadow: 0 25px 60px rgba(0,0,0,0.2);
            }
            .icon-wrap {
                width: 64px;
                height: 64px;
                background: #fef3e2;
                border: 2px solid #f5d9b0;
                border-radius: 50%;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 1.6rem;
                color: #c17d3c;
                margin-bottom: 20px;
            }
            h1 {
                font-family: "Playfair Display", serif;
                font-size: 1.4rem;
                color: #2d1f0e;
                margin-bottom: 10px;
            }
            p { color: #7a6652; font-size: 0.88rem; line-height: 1.6; margin-bottom: 28px; }
            .btn-back {
                display: inline-block;
                background: #c17d3c;
                color: #fff;
                font-weight: 600;
                font-size: 0.88rem;
                padding: 10px 24px;
                border-radius: 8px;
                text-decoration: none;
                transition: background 0.2s;
            }
            .btn-back:hover { background: #a8682e; color: #fff; }
        </style>
    </head>
    <body>
        <div class="error-card">
            <div class="icon-wrap"><i class="bi ' . $icon . '"></i></div>
            <h1>' . htmlspecialchars($message) . '</h1>
            <p>Please check your credentials and try again.</p>
            <a href="login.php" class="btn-back"><i class="bi bi-arrow-left me-1"></i> Go Back</a>
        </div>
    </body>
    </html>';
    exit;
}

if (isset($_POST['username'])) {

    $db = new PDO("mysql:host=localhost;dbname=traveldb", "root", "");
    $query = "SELECT * FROM users WHERE username = :username";
    $stmt = $db->prepare($query);
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch();

    if ($user) {
        $is_password_match = password_verify($password, $user['password']);

        if ($is_password_match) {
            $_SESSION['user'] = $user;

            if (isset($user['role']) && strtolower($user['role']) === 'admin') {
                header("Location: manage.php");
            } else {
                header("Location: index.php");
            }
            exit;
            
        } else {
            render_error_page("Wrong password!", "bi-shield-lock-fill");
        }
    } else {
        render_error_page("User not found!", "bi-person-x-fill");
    }

} else {
    if (isset($_SESSION['user'])) {
        if (isset($_SESSION['user']['role']) && strtolower($_SESSION['user']['role']) === 'admin') {
            header("Location: manage.php");
        } else {
            header("Location: index.php");
        }
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Travel Explorer</title>
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
            max-width: 860px;
            min-height: 480px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(0,0,0,0.35);
        }

        .left {
            flex: 1;
            background: #fdf6ec;
            padding: 52px 44px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            border-right: 1px solid #e8ddd0;
        }

        .left .title {
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #c17d3c;
            margin-bottom: 10px;
        }

        .left h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            color: #2d1f0e;
            line-height: 1.25;
            margin-bottom: 16px;
        }

        .left p {
            color: #7a6652;
            font-size: 0.92rem;
            line-height: 1.7;
            margin-bottom: 28px;
        }

        .right {
            flex: 1;
            background: #ffffff;
            padding: 52px 44px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .right h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            color: #2d1f0e;
            margin-bottom: 6px;
        }

        .right .title {
            color: #9e8a78;
            font-size: 0.85rem;
            margin-bottom: 32px;
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
            padding: 11px 14px;
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

        .btn-login {
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

        .btn-login:hover {
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

        @media (max-width: 680px) {
            .page-layout { flex-direction: column; }
            . left { padding: 36px 28px 28px; border-right: none; border-bottom: 1px solid #e8ddd0; }
            .righttitle { padding: 32px 28px 40px; }
        }
    </style>
</head>
<body>

<div class="page-layout">

    <div class=" left">
        <span class="title">Travel Explorer</span>
        <h1>Travel Is The Only Thing You Buy That Makes You Richer.</h1>
        <p>Sign in to pick up where you left off — your saved hotels and favourite destinations are waiting for you.</p>
    </div>

    <div class="right">
        <h2>Sign in</h2>
        <p class="title">Enter your credentials to continue.</p>

        <form method="POST" action="login.php">
            <div class="mb-4">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username"
                    placeholder="Enter your username"
                    value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>"
                    required />
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password"
                    placeholder="••••••••" required />
            </div>

            <button type="submit" class="btn-login">Sign In   <i class="bi bi-arrow-right"></i></button>
        </form>

        <p class="form-footer">
            Don't have an account? <a href="register.php">Register here</a>
        </p>
    </div>

</div>
</body>
</html>