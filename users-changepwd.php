<?php
require("header.php");

if(!isset($_GET['id'])){
    header("Location: users.php");
    exit;
}

$user_id = $_GET['id'];

if(isset($_POST['password']) && isset($_POST['confirm_password']) && isset($_POST['user_id'])){

    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $user_id = $_POST['user_id'];

    // Check if passwords match
    if($password == $confirm_password){
        $updateQuery = "UPDATE users SET password=:password WHERE user_id=:user_id";
        $stmt = $db->prepare($updateQuery);
        $stmt->execute([
            ":password" => password_hash($password, PASSWORD_BCRYPT),
            ":user_id" => $user_id
        ]);
        header("Location: users.php");
        exit;    
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>

    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
</head>

<body>

<div class="container mx-auto my-5" style="max-width:700px;">

    <h1 class="mb-4">Change Password</h1>

    <div class="card p-4">

        <form method="POST" id=changePasswordForm>

            <div class="mb-3">
                <label class="form-label">New Password</label>

                <input
                    type="password"
                    class="form-control"
                    id="password"
                    name="password"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Confirm Password</label>

                <input
                    type="password"
                    class="form-control"
                    id="confirm_password"
                    name="confirm_password"
                    required
                >
            </div>

            <input type="hidden" name="user_id" value="<?= $user_id ?>">

            <div class="d-gruser_id">
                <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to change your password?')">
                    Change Password
                </button>
            </div>

        </form>

    </div>

</div>
</body>
</html>