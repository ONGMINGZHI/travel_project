<?php
require 'header.php';

if (strtolower($_SESSION['user']['role']) !== 'admin') {
    header("Location: index.php");
    exit();
}

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $user_id = (int)$_GET['id'];
    
    $deleteQuery = "UPDATE users SET deleted_at = :deleted_at WHERE user_id = :user_id";
    $stmt = $db->prepare($deleteQuery); 
    $stmt->execute([
        ':deleted_at' => date("Y-m-d H:i:s"),
        ':user_id' => $user_id
    ]);
    
    header('Location: users.php');
    exit();
}

$query = "SELECT * FROM users WHERE deleted_at IS NULL ORDER BY user_id ASC";
$stmt = $db->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Travel Explorer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            background-image: url('passport-stamps.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-color: #fdf6ec;
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
            min-height: 100vh;
            padding: 50px 16px 80px;
        }

        .header {
            max-width: 780px;
            margin: 0 auto 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .header-left .title {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #c17d3c;
            margin-bottom: 4px;
        }

        .header-left h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.9rem;
            color: #2d1f0e;
            line-height: 1.2;
        }

        .btn-add {
            background: #c17d3c;
            color: #fff;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 9px 20px;
            border-radius: 8px;
            text-decoration: none;
            border: none;
            transition: background 0.2s, transform 0.15s;
            white-space: nowrap;
        }

        .btn-add:hover {
            background: #a8682e;
            color: #fff;
            transform: translateY(-1px);
        }

        .table-card {
            max-width: 780px;
            margin: 0 auto;
            background: #fff;
            border: 1.5px solid #e8ddd0;
            border-radius: 16px;
            overflow: hidden;
        }

        .table {
            margin: 0;
            font-size: 0.88rem;
            color: #2d1f0e;
        }

        .table thead tr {
            background: #fdf6ec;
            border-bottom: 1.5px solid #e8ddd0;
        }

        .table thead th {
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #9e8a78;
            padding: 14px 20px;
            border: none;
        }

        .table tbody tr {
            border-bottom: 1px solid #f3ede4;
            transition: background 0.15s;
        }

        .table tbody tr:last-child { border-bottom: none; }

        .table tbody tr:hover { background: #fdfaf7; }

        .table tbody td, .table tbody th {
            padding: 14px 20px;
            vertical-align: middle;
            border: none;
            color: #2d1f0e;
        }

        .badge-role {
            font-size: 0.72rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 999px;
            letter-spacing: 0.04em;
        }

        .badge-user  { background: #ecfdf5; color: #166534; border: 1px solid #bbf7d0; }
        .badge-admin { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }

        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            font-size: 0.85rem;
            border: 1.5px solid;
            transition: background 0.15s, transform 0.15s;
            text-decoration: none;
        }

        .btn-action:hover { transform: translateY(-1px); }

        .btn-edit   { background: #f0fdf4; border-color: #bbf7d0; color: #166534; }
        .btn-edit:hover { background: #dcfce7; color: #166534; }

        .btn-pwd    { background: #fffbeb; border-color: #fde68a; color: #92400e; }
        .btn-pwd:hover { background: #fef3c7; color: #92400e; }

        .btn-delete { background: #fff0f0; border-color: #fecaca; color: #991b1b; }
        .btn-delete:hover { background: #fee2e2; color: #991b1b; }

        .footer {
            max-width: 780px;
            margin: 20px auto 0;
            text-align: center;
        }

        .btn-back {
            background: #fff;
            border: 1.5px solid #e0d5c8;
            color: #5a4a3a;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 9px 22px;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.2s, border-color 0.2s;
            display: inline-block;
        }

        .btn-back:hover {
            background: #fdf0e0;
            border-color: #c17d3c;
            color: #c17d3c;
        }
    </style>
</head>
<body>

<div class="page-wrapper">

    <div class="header">
        <div class="header-left">
            <p class="title">Administration</p>
            <h1>Manage Users</h1>
        </div>
        <a href="users-add.php" class="btn-add">
            <i class="bi bi-person-plus-fill me-1"></i> Add New User
        </a>
    </div>

    <div class="table-card">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-4" style="color:#9e8a78;">
                            No users found in database.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td style="color:#9e8a78; font-size:0.8rem;">#<?= $user['user_id'] ?></td>
                            <td style="font-weight:600;"><?= htmlspecialchars($user['username']) ?></td>
                            <td style="color:#7a6652;"><?= htmlspecialchars($user['email']) ?></td>
                            <td>
                                <?php if ($user['role'] === 'admin'): ?>
                                    <span class="badge-role badge-admin">Admin</span>
                                <?php else: ?>
                                    <span class="badge-role badge-user">User</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="users-edit.php?id=<?= $user['user_id'] ?>"
                                        class="btn-action btn-edit" title="Edit User">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="users-changepwd.php?id=<?= $user['user_id'] ?>"
                                        class="btn-action btn-pwd" title="Change Password">
                                        <i class="bi bi-key"></i>
                                    </a>
                                    <a href="users.php?action=delete&id=<?= $user['user_id'] ?>"
                                        class="btn-action btn-delete" title="Delete User"
                                        onclick="return confirm('Are you sure you want to delete this user?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <a href="manage.php" class="btn-back">
            <i class="bi bi-arrow-left me-1"></i> Back to Manage
        </a>
    </div>

</div>
</body>
</html>