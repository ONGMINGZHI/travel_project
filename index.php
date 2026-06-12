<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Travel Explorer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-color: #fdf6ec;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: rgba(253, 246, 236, 0.80);
            z-index: 0;
        }

        .page-wrapper {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            padding: 60px 16px 60px;
        }

        .header {
            text-align: center;
            margin-bottom: 48px;
        }

        .header .title {
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #c17d3c;
            margin-bottom: 10px;
        }

        .header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.8rem;
            color: #2d1f0e;
            margin-bottom: 10px;
            line-height: 1.15;
        }

        .header p {
            color: #7a6652;
            font-size: 0.95rem;
        }

        .nav-card {
            background: #ffffff;
            border: 1.5px solid #e8ddd0;
            border-radius: 16px;
            padding: 36px 28px;
            text-align: center;
            transition: transform 0.25s, box-shadow 0.25s;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .nav-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 40px rgba(193, 125, 60, 0.15);
        }

        .card-icon {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 20px;
            flex-shrink: 0;
        }

        .icon-blue  { background: #eff6ff; color: #3b7dd8; }
        .icon-amber { background: #fef3e2; color: #c17d3c; }

        .nav-card h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            color: #2d1f0e;
            margin-bottom: 8px;
        }

        .nav-card p {
            color: #9e8a78;
            font-size: 0.85rem;
            margin-bottom: 24px;
            line-height: 1.6;
            flex: 1;
        }

        .btn-card {
            display: block;
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            font-size: 0.88rem;
            text-decoration: none;
            transition: background 0.2s, transform 0.15s;
            text-align: center;
        }

        .btn-card:hover { transform: translateY(-1px); }

        .btn-amber { background: #c17d3c; color: #fff; }
        .btn-amber:hover { background: #a8682e; color: #fff; }

        .btn-blue { background: #3b7dd8; color: #fff; }
        .btn-blue:hover { background: #2d6bbf; color: #fff; }

        .footer { 
            text-align: center; 
            margin:20px;
        }

        .btn-logout {
            background: #fff;
            border: 1.5px solid #e0d5c8;
            color: #5a4a3a;
            font-weight: 600;
            font-size: 0.88rem;
            padding: 10px 28px;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.2s, border-color 0.2s;
            display: inline-block;
        }

        .btn-logout:hover {
            background: #fdf0e0;
            border-color: #c17d3c;
            color: #c17d3c;
        }

        .admin {
            max-width: 480px;
            margin: 24px auto 0;
            background: #fef3e2;
            border: 1.5px solid #f5d9b0;
            border-radius: 12px;
            padding: 20px 28px;
            text-align: center;
        }

        .admin p {
            color: #7a4f1e;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 14px;
        }

        .btn-manage {
            background: #c17d3c;
            color: #fff;
            font-weight: 600;
            font-size: 0.88rem;
            padding: 10px 24px;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.2s;
            display: inline-block;
        }

        .btn-manage:hover { background: #a8682e; color: #fff; }
    </style>
</head>
<body>

<div class="page-wrapper">

    <div class="header">
        <p class="title">Travel Explorer</p>
        <h1>Where would you like<br>to go today?</h1>
        <p>Explore destinations, discover hotels, and save your favourites.</p>
    </div>

    <div class="container" style="max-width: 760px;">
        <div class="row g-4 justify-content-center">

            <div class="col-12 col-sm-6">
                <div class="nav-card">
                    <div class="card-icon icon-blue">
                        <i class="bi bi-globe-americas"></i>
                    </div>
                    <h3>Countries</h3>
                    <p>Explore destinations around the world and discover cities and hotels.</p>
                    <a href="countries.php" class="btn-card btn-blue">Browse Countries <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>

            <div class="col-12 col-sm-6">
                <div class="nav-card">
                    <div class="card-icon icon-amber">
                        <i class="bi bi-bookmark-heart"></i>
                    </div>
                    <h3>Bookmarks</h3>
                    <p>View the hotels you've saved for your next trip.</p>
                    <a href="bookmarks.php" class="btn-card btn-amber">My Bookmarks <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>

        </div>
    </div>

    <div class="footer">
        <a href="logout.php" class="btn-logout">
            <i class="bi bi-box-arrow-right me-2"></i>Sign Out
        </a>

        <?php if (isset($_SESSION['user']['role']) && strtolower($_SESSION['user']['role']) === 'admin'): ?>
            <div class="admin">
                <p><i class="bi bi-shield-check me-1"></i> You are signed in as an administrator.</p>
                <a href="manage.php" class="btn-manage">Go to Management Dashboard <i class="bi bi-arrow-right"></i></a>
            </div>
        <?php endif; ?>
    </div>

</div>
</body>
</html>