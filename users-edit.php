<?php
require('header.php');

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    $query = "SELECT * FROM users WHERE user_id=:user_id";
    $stmt = $db->prepare($query);
    $stmt->execute([
        ':user_id' => $user_id
    ]);
    $user = $stmt->fetch();
}

if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['role']) && isset($_POST['user_id'])){

    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $user_id = $_POST['user_id'];}

        $updateQuery = "UPDATE users SET username=:username, email=:email, role=:role WHERE user_id=:user_id";
        $stmt = $db->prepare($updateQuery);
        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':role' => $role,
            ':user_id' => $user_id
        ]);

    header("Location:users-edit.php");
    exit;
?>
  
<!DOCTYPE html>
<html>
  <head>
    <title>Simple CMS</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css"
    />
    <style type="text/css">
      body {
        background: #f1f1f1;
      }
    </style>
  </head>
  <body>
    <div class="container mx-auto my-5" style="max-width: 700px;">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h1">Edit User</h1>
      </div>
      <div class="card mb-2 p-4">
        <form method="POST" id="updateUserForm">
          <div class="mb-3">
            <div class="row">
              <div class="col">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required value="<?= $user['username'] ?>" />
              </div>
              <div class="col">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required value="<?= $user['email'] ?>" />
              </div>
            </div>
          </div>
          <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-control" id="role" name="role" required>
              <option value="">Select an option</option>
              <option value="user"<?= $user['role'] == "user" ? "selected" : ""?>>User</option>
              <option value="admin"<?= $user['role'] == "admin" ? "selected" : ""?>>Admin</option>
            </select>
          </div>
            <input type="hidden" name="user_id" value="<?= $user_id ?>">
          <div class="d-grid">
            <button type="submit" class="btn btn-primary">Update</button>
          </div>
        </form>
      </div>
      <div class="text-center">
        <a href="users.php" class="btn btn-link btn-sm"
          ><i class="bi bi-arrow-left"></i> Back to Users</a
        >
      </div>
    </div>
  </body>
</html>