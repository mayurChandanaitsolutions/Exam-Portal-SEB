<?php
session_start();

// DB CONNECTION
$conn = new mysqli("localhost","root","","seb_portal");

if($conn->connect_error){
    die("Connection Failed");
}

// SEND OTP (AUTO DETECT EMAIL OR PHONE)
if(isset($_POST['send_otp'])){

    $value = $_POST['value'];

    $_SESSION['value'] = $value;
    $_SESSION['otp'] = rand(1000,9999);

    // CHECK EMAIL OR PHONE
    if(filter_var($value, FILTER_VALIDATE_EMAIL)){
        $_SESSION['method'] = "email";
        $msg = "📧 Email OTP (Demo): ".$_SESSION['otp'];
    } else {
        $_SESSION['method'] = "phone";
        $msg = "📱 Phone OTP (Demo): ".$_SESSION['otp'];
    }
}

// RESET PASSWORD
if(isset($_POST['reset'])){

    if($_POST['otp'] != $_SESSION['otp']){
        $msg = "❌ Invalid OTP";
    } else {

        $newpass = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $value = $_SESSION['value'];

        if($_SESSION['method']=="email"){
            $sql = "UPDATE students SET password='$newpass' WHERE email='$value'";
        } else {
            $sql = "UPDATE students SET password='$newpass' WHERE phone='$value'";
        }

        if($conn->query($sql)){
            $msg = "✅ Password Reset Successful";
            unset($_SESSION['otp']);
        } else {
            $msg = "❌ Reset Failed";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Reset Password</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background: linear-gradient(to right,#141e30,#243b55);
}
</style>
</head>

<body>

<div class="container mt-5">
<div class="row justify-content-center">
<div class="col-md-5">

<div class="card p-4 shadow">

<h4 class="text-center">Reset Password</h4>

<?php if(isset($msg)){ ?>
<div class="alert alert-info"><?php echo $msg; ?></div>
<?php } ?>

<!-- STEP 1 -->
<form method="POST">

<input class="form-control mb-2" name="value" placeholder="Enter Email or Phone" required>

<button type="submit" name="send_otp" class="btn btn-warning w-100">Send OTP</button>

</form>

<hr>

<!-- STEP 2 -->
<form method="POST">

<input class="form-control mb-2" name="otp" placeholder="Enter OTP" required>

<input type="password" class="form-control mb-2" name="new_password" placeholder="New Password" required>

<button type="submit" name="reset" class="btn btn-success w-100">Reset Password</button>

</form>

<div class="text-center mt-3">
<a href="login.php">← Back to Login</a>
</div>

</div>
</div>
</div>
</div>

</body>
</html>