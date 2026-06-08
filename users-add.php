<?php
require('header.php');

$query = "INSERT INTO users (username, email, password, role)VALUES (:username, :email,   :password, :role)";

if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) &&isset($_POST['confirm_password']) && isset($_POST['role'])) {

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

    if ($password != $confirm_password) {
        echo "Passwords do not match!";
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
            echo "Failed to add user.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
 <head>
    <title>Add User</title>
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
            background: #f1f1f1;
        }
    </style>
    

</head>

<body>
    <div class="container mx-auto my-5" style="max-width: 700px;">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h1 class="h1">Add New User</h1>
        </div>
        <div class="card mb-2 p-4">
            <form method="POST" id="addUserForm">
                <input type="hidden" name="action" value="addNewUser">
                <div class="mb-3">
                    <div class="row">
                        <div class="col">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required/>
                        </div>
                        <div class="col">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required/>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="row">
                        <div class="col">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required/>
                        </div>
                        <div class="col">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input
                                type="password"
                                class="form-control"
                                id="confirm_password"
                                name="confirm_password" required />
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-control" id="role" name="role" required>
                        <option value="">Select an option</option>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
        <div class="text-center">
            <a href="users.php" class="btn btn-link btn-sm"><i class="bi bi-arrow-left"></i> Back to Users</a>
        </div>
    </div>
</body>

</html>