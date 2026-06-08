<?php
session_start();
session_destroy();
header("Location: login.php");
exit;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { background-color: #060B13; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #0B1220; }
        ::-webkit-scrollbar-thumb { background: #1E293B; border-radius: 3px; }
    </style>
    <title>Logged Out</title>
</head>
<body>
    <h1>You have successfully been logged out.</h1>
    <h2><a href="login.php">Click here to redirect to login page</a></h2>
</body>
</html>