<?php include 'config.php';
$msg = "";

if (isset($_POST['signup'])) {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password != $confirm_password) {
        $msg = "Passwords do not match";
    } else {
        $check = $conn->prepare("SELECT id FROM admins WHERE email=?");
        $check->bind_param("s", $email);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $msg = "Email already registered";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO admins(full_name,email,phone,password) VALUES(?,?,?,?)");
            $stmt->bind_param("ssss", $full_name, $email, $phone, $hashed);
            if ($stmt->execute()) {
                header("Location: admin_login.php?registered=1");
                exit();
            } else {
                $msg = "Registration failed";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card auth-card p-4">
                <h2 class="text-center mb-4">Admin Sign Up</h2>
                <?php if($msg) echo "<div class='alert alert-danger'>$msg</div>"; ?>
                <form method="POST">
                    <div class="mb-3"><input type="text" name="full_name" class="form-control" placeholder="Full Name" required></div>
                    <div class="mb-3"><input type="email" name="email" class="form-control" placeholder="Email" required></div>
                    <div class="mb-3"><input type="text" name="phone" class="form-control" placeholder="Phone Number" required></div>
                    <div class="mb-3"><input type="password" name="password" class="form-control" placeholder="Password" required></div>
                    <div class="mb-3"><input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required></div>
                    <button type="submit" name="signup" class="btn btn-primary w-100">Register</button>
                </form>
                <p class="mt-3 text-center">Already have an account? <a href="admin_login.php">Sign In</a></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>