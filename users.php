<?php
require('header.php');
 $query = "SELECT * FROM users WHERE deleted_at IS NULL ORDER BY user_id DESC";
        $stmt = $db->prepare($query);
        $stmt->execute([]);
        $users = $stmt->fetchAll();

 $deleteQuery = "UPDATE users SET deleted_at=:deleted_at WHERE user_id=:user_id";
        $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;
        $stmt = $db->prepare($deleteQuery);
        $stmt->execute([
            ':deleted_at' => date("Y-m-d H:i:s"),
            ':user_id' => $user_id
        ]);

    header('Location: users.php');
    exit;

?>
<!DOCTYPE html>
<html>
<head>
    <title>Users</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"
        crossorigin="anonymous" />
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" />
    <style type="text/css">
        body {
            background: #F1F1F1;
        }
    </style>
</head>

<body>
    <div class="container mx-auto my-5" style="max-width: 700px;">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h1 class="h1">Manage Users</h1>
            <div class="text-end">
                <a href="users-add.php" class="btn btn-primary btn-sm">Add New User</a>
            </div>
        </div>
        <div class="card mb-2 p-4">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Role</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">No users found in database.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <?php
                            $role_badge = "";
                            switch ($user['role']) {
                                case 'user':
                                    $role_badge = "bg-success";
                                    break;
                                case 'admin':
                                    $role_badge = "bg-primary";
                                    break;
                            }
                            ?>
                            <tr>
                                <th scope="row"><?= $user['user_id'] ?></th>
                                <td><?= $user['username'] ?></td>
                                <td><?= $user['email'] ?></td>
                                <td><span class="badge <?= $role_badge ?>"><?= ucwords($user['role']) ?></span></td>
                                <td class="text-end">
                                    <div class="buttons">
                                        <a
                                            href="users-edit.php?id=<?= $user['user_id'] ?>"
                                            class="btn btn-success btn-sm me-2"
                                            title="Edit User"><i class="bi bi-pencil"></i></a>
                                        <a
                                            href="users-changepwd.php?id=<?= $user['user_id'] ?>"
                                            class="btn btn-warning btn-sm me-2"
                                            title="Change Password"><i class="bi bi-key"></i></a>
                                        <a
                                            href="users.php?action=delete&id=<?= $user['user_id'] ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to delete this user?')"
                                            title="Delete User"><i class="bi bi-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="text-center">
            <a href="manage.php" class="btn btn-link btn-sm"><i class="bi bi-arrow-left"></i> Back to Manage</a>
        </div>
    </div>
</body>

</html>