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
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0f172a, #1e293b);
            min-height: 100vh;
            color: white;
            overflow-x: hidden;
        }

        .glass-card {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.12);
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0,0,0,0.35);
        }

        .manage-btn {
            transition: 0.3s;
        }

        .manage-btn:hover {
            transform: scale(1.05);
        }
        .top-glow {
            position: absolute;
            width: 350px;
            height: 350px;
            background: #3b82f6;
            filter: blur(120px);
            opacity: 0.25;
            top: -100px;
            left: -100px;
            z-index: -1;
        }

        .bottom-glow {
            position: absolute;
            width: 350px;
            height: 350px;
            background: #8b5cf6;
            filter: blur(120px);
            opacity: 0.25;
            bottom: -100px;
            right: -100px;
            z-index: -1;
        }
    </style>
    </head>
    <body>
    <div class="w-100 min-vh-100 position-relative overflow-hidden">
        
        <div class="top-glow"></div>
        <div class="bottom-glow"></div>

        <div class="container py-5">
            
            <div class="text-center mb-5 position-relative">
                <h1 class="display-4 fw-bold">Travel</h1>
            </div>

            <div class="row g-4 justify-content-center">
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="glass-card rounded-4 p-4 h-100 text-center shadow-lg">
                        <div>
                            <div class="mb-4">
                                <div class="bg-primary bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center" style="width:80px;height:80px;">
                                    <i class="bi bi-globe-americas fs-1"></i>
                                </div>
                            </div>
                            <h3 class="fw-bold mb-3">Countries</h3>
                            <p class="text-light opacity-75 mb-4">Explore countries.</p>
                        </div>
                        <a href="countries.php" class="btn btn-primary w-100 rounded-pill manage-btn py-2 mt-auto">Open Countries</a>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="glass-card rounded-4 p-4 h-100 text-center shadow-lg">
                        <div>
                            <div class="mb-4">
                                <div class="bg-danger bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center" style="width:80px;height:80px;">
                                    <i class="bi bi-bookmark-heart fs-1"></i>
                                </div>
                            </div>
                            <h3 class="fw-bold mb-3">Bookmarks</h3>
                            <p class="text-light opacity-75 mb-4">See bookmarks.</p>
                        </div>
                        <a href="bookmarks.php" class="btn btn-danger w-100 rounded-pill manage-btn py-2 mt-auto">Open Bookmarks</a>
                    </div>
                </div>
            </div>

            <div class="mt-5 p-4 border-secondary border-opacity-25 rounded bg-none bg-opacity-5 text-center">
                <a href="logout.php" class="btn btn-success px-4">Logout</a>
            </div>

            <?php if (isset($_SESSION['user']['role']) && strtolower($_SESSION['user']['role']) === 'admin'): ?>
                <div class="mt-4 p-4 border border-warning rounded bg-warning bg-opacity-10 text-center">
                    <p class="text-warning mb-2"><i class="bi bi-shield-lock-fill"></i> You are logged in as an administrator.</p>
                    <a href="manage.php" class="btn btn-warning">Go to Management Dashboard</a>
                </div>
            <?php endif; ?>

        </div> 
    </div> 
</body>
</html>