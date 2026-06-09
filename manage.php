<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) || strtolower($_SESSION['user']['role']) !== 'admin') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage - Admin Dashboard</title>
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

<div class="top-glow"></div>
<div class="bottom-glow"></div>

<div class="container py-5">
    <div class="text-center mb-5 relative">
        <h1 class="display-4 fw-bold">Admin Dashboard</h1>
        <a href="index.php" class="text-slate-400 hover:text-white transition-colors text-sm font-medium"><i class="bi bi-arrow-left"></i> Back to Main Site</a>
    </div>

    <div class="row g-4 justify-content-center">
        
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="glass-card rounded-4 p-4 h-100 text-center shadow-lg flex flex-col justify-between">
                <div>
                    <div class="mb-4">
                        <div class="bg-danger bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center" style="width:80px;height:80px;">
                            <i class="bi bi-people-fill fs-1"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-3">Users</h3>
                    <p class="text-light opacity-75 mb-4">Manage users system settings.</p>
                </div>
                <a href="users.php" class="btn btn-danger w-100 rounded-pill manage-btn py-2 mt-auto">Open</a>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="glass-card rounded-4 p-4 h-100 text-center shadow-lg flex flex-col justify-between">
                <div>
                    <div class="mb-4">
                        <div class="bg-primary bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center" style="width:80px;height:80px;">
                            <i class="bi bi-globe-americas fs-1"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-3">Data</h3>
                    <p class="text-light opacity-75 mb-4">Manage countries, cities and hotels.</p>
                </div>
                <a href="countries.php" class="btn btn-primary w-100 rounded-pill manage-btn py-2 mt-auto">Open</a>
            </div>
        </div>

    </div> 
</div>

</body>
</html>