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
        <title>TravelDB - Authentication Notice</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    </head>
    <body class="bg-[#060B13] h-screen flex items-center justify-center font-sans antialiased px-4">
        <div class="max-w-md w-full bg-[#0B1220] border border-slate-800 p-8 rounded-3xl shadow-2xl text-center space-y-6 relative overflow-hidden">
            <div class="absolute -top-10 -left-10 w-32 h-32 bg-amber-500/10 blur-3xl rounded-full"></div>

            <div class="w-16 h-16 mx-auto bg-amber-500/10 border border-amber-500/20 text-amber-500 rounded-2xl flex items-center justify-center text-2xl">
                <i class="bi ' . $icon . '"></i>
            </div>

            <div class="space-y-2">
                <h1 class="text-xl font-bold text-slate-100 tracking-tight">' . htmlspecialchars($message) . '</h1>
                <p class="text-sm text-slate-400">Please verify your account coordinates and attempt the sequence again.</p>
            </div>

            <div class="pt-2">
                <a href="login.php" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 hover:text-white font-medium text-sm rounded-xl transition-colors">
                    <i class="bi bi-arrow-left"></i> Go Back
                </a>
            </div>
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
    // If they refresh or visit while already logged in, route them based on their existing role
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
    <title>TravelDB - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #060B13; }
    </style>
</head>
<body class="text-slate-200 font-sans antialiased h-screen flex items-center justify-center px-4">

    <div class="max-w-md w-full bg-[#0B1220] border border-slate-800 p-8 rounded-3xl shadow-2xl space-y-6 relative overflow-hidden">
        <div class="absolute -top-16 -right-16 w-36 h-36 bg-amber-500/10 blur-3xl rounded-full"></div>
        <div class="absolute -bottom-16 -left-16 w-36 h-36 bg-blue-500/5 blur-3xl rounded-full"></div>

        <div class="text-center space-y-2 relative">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-amber-500/10 border border-amber-500/20 text-amber-500 rounded-2xl text-xl mb-1">
                <i class="bi bi-send-fill transform rotate-45"></i>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-white">Login here</h1>
        </div>

        <form method="POST" action="login.php" class="space-y-4 relative">
            <div class="space-y-1.5">
                <label for="username" class="text-xs font-semibold text-slate-400 ml-1">Username</label>
                <div class="relative">
                    <i class="bi bi-person absolute left-4 top-1/2 -translate-y-1/2 text-slate-500"></i>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        placeholder="Enter your username" 
                        required
                        class="w-full bg-[#121B2D] border border-slate-800 rounded-xl pl-11 pr-4 py-3 text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:border-amber-500 transition-colors"
                    >
                </div>
            </div>

            <div class="space-y-1.5">
                <label for="password" class="text-xs font-semibold text-slate-400 ml-1">Password</label>
                <div class="relative">
                    <i class="bi bi-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-500"></i>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="••••••••" 
                        required
                        class="w-full bg-[#121B2D] border border-slate-800 rounded-xl pl-11 pr-4 py-3 text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:border-amber-500 transition-colors"
                    >
                </div>
            </div>

            <button 
                type="submit" 
                class="w-full bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-sm py-3 px-4 rounded-xl transition-colors shadow-lg shadow-amber-500/10 mt-2"
            >
                Sign In
            </button>
        </form>

        <div class="flex items-center justify-between pt-2 border-t border-slate-800/60 text-xs relative">
            <a href="index.php" class="text-slate-400 hover:text-white transition-colors flex items-center gap-1.5 font-medium">
                <i class="bi bi-arrow-left"></i> Go back
            </a>
            <a href="register.php" class="text-amber-500 hover:underline transition-colors font-semibold flex items-center gap-1">
                Don't have an account? Sign up <i class="bi bi-chevron-right text-[10px]"></i>
            </a>
        </div>
    </div>

</body>
</html>