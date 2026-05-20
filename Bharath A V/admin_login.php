<?php include 'config.php';
$msg = "";

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admins WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['full_name'];
            header("Location: dashboard.php");
            exit();
        } else {
            $msg = "Invalid password";
        }
    } else {
        $msg = "Admin not found";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Sign In</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card auth-card p-4">
                <h2 class="text-center mb-4">Admin Sign In</h2>
                <?php if(isset($_GET['registered'])) echo "<div class='alert alert-success'>Registration successful. Please login.</div>"; ?>
                <?php if($msg) echo "<div class='alert alert-danger'>$msg</div>"; ?>
                <form method="POST">
                    <div class="mb-3"><input type="email" name="email" class="form-control" placeholder="Email" required></div>
                    <div class="mb-3"><input type="password" name="password" class="form-control" placeholder="Password" required></div>
                    <button type="submit" name="login" class="btn btn-success w-100">Login</button>
                </form>
                <p class="mt-3 text-center">New Admin? <a href="admin_signup.php">Create Account</a></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>