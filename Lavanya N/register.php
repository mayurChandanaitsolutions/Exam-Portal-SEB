<?php
session_start();

// DB CONNECTION
$conn = new mysqli("localhost", "root", "", "seb_portal");

if($conn->connect_error){
    die("Connection Failed: " . $conn->connect_error);
}

// SEND OTP
if(isset($_POST['send_otp'])){
    $method = $_POST['otp_method'];
    $_SESSION['otp'] = rand(1000,9999);
    $_SESSION['method'] = $method;

    if($method == "email"){
        $_SESSION['value'] = $_POST['email'];
        $msg = "📧 Email OTP (Demo): ".$_SESSION['otp'];
        $msg_type = "success";
    } else {
        $_SESSION['value'] = $_POST['phone'];
        $msg = "📱 Phone OTP (Demo): ".$_SESSION['otp'];
        $msg_type = "success";
    }
}

// REGISTER
if(isset($_POST['register'])){
    if(!isset($_SESSION['otp'])){
        $msg = "❌ Please send OTP first";
        $msg_type = "danger";
    }
    elseif($_POST['otp'] != $_SESSION['otp']){
        $msg = "❌ Invalid OTP";
        $msg_type = "danger";
    }
    else{
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $dob = $_POST['dob'];
        $gender = $_POST['gender'];

        $current_address = $_POST['current_address'];
        $permanent_address = $_POST['permanent_address'];

        // COUNTRY, STATE, CITY LOGIC
        $country = ($_POST['country_select'] == "other") ? $_POST['country_other'] : $_POST['country_select'];
        $state = ($_POST['state_select'] == "other") ? $_POST['state_other'] : $_POST['state_select'];
        $city = ($_POST['city_select'] == "other") ? $_POST['city_other'] : $_POST['city_select'];

        $pincode = $_POST['pincode'];
        $aadhaar = $_POST['aadhaar'];

        // PASSWORD HASH
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // PHOTO UPLOAD
        $photo = $_FILES['photo']['name'];
        $tmp = $_FILES['photo']['tmp_name'];
        $ext = strtolower(pathinfo($photo, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png'];

        if(!in_array($ext, $allowed)){
            $msg = "❌ Only JPG, JPEG, PNG formats are allowed";
            $msg_type = "danger";
        }
        else{
            $newname = time().".".$ext;
            // Ensure the uploads directory exists
            if (!file_exists('../uploads')) {
                mkdir('../uploads', 0777, true);
            }
            
            move_uploaded_file($tmp, "../uploads/".$newname);
            $address = $current_address." | ".$permanent_address.", ".$city.", ".$state.", ".$country." - ".$pincode;

            $stmt = $conn->prepare("INSERT INTO students (name, email, phone, dob, gender, address, aadhaar, password, photo) VALUES (?,?,?,?,?,?,?,?,?)");
            $stmt->bind_param("sssssssss", $name, $email, $phone, $dob, $gender, $address, $aadhaar, $password, $newname);

            if($stmt->execute()){
                $msg = "✅ Registration Successful!";
                $msg_type = "success";
                unset($_SESSION['otp']); // Clear OTP on success
            } else {
                $msg = "Error: ".$conn->error;
                $msg_type = "danger";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal | Advanced Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hidden-input { display: none; margin-top: 10px; }
        .section-title { font-size: 1.1rem; color: #0d6efd; border-bottom: 2px solid #e9ecef; padding-bottom: 5px; margin-bottom: 15px; margin-top: 20px; }
    </style>
</head>
<body class="bg-light py-5">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h3 class="mb-0">Student Registration</h3>
                    <p class="mb-0 small">Please fill in the details below to create your account.</p>
                </div>
                
                <div class="card-body p-4">
                    <?php if(isset($msg)){ ?>
                        <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show" role="alert">
                            <?php echo $msg; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php } ?>

                    <form method="POST" enctype="multipart/form-data">

                        <h4 class="section-title mt-0">Personal Details</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" name="name" placeholder="John Doe" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" name="dob" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" name="email" placeholder="john@example.com" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" name="phone" placeholder="+1234567890" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Gender</label>
                                <select class="form-select" name="gender" required>
                                    <option value="" selected disabled>Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Aadhaar Number</label>
                                <input type="text" class="form-control" name="aadhaar" placeholder="12-digit number" required>
                            </div>
                        </div>

                        <h4 class="section-title">Address Details</h4>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Current Address</label>
                                <input type="text" class="form-control" name="current_address" placeholder="Street, Apartment, or Floor" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Permanent Address</label>
                                <input type="text" class="form-control" name="permanent_address" placeholder="Street, Apartment, or Floor" required>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Country</label>
                                <select class="form-select" name="country_select" id="country_select" onchange="toggleOther('country_select', 'country_other')" required>
                                    <option value="" selected disabled>Select...</option>
                                    <option value="India">India</option>
                                    <option value="USA">USA</option>
                                    <option value="UK">UK</option>
                                    <option value="other">Other (Specify)</option>
                                </select>
                                <input type="text" class="form-control hidden-input" name="country_other" id="country_other" placeholder="Enter Country">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">State</label>
                                <select class="form-select" name="state_select" id="state_select" onchange="toggleOther('state_select', 'state_other')" required>
                                    <option value="" selected disabled>Select...</option>
                                    <option value="Karnataka">Karnataka</option>
                                    <option value="Tamil Nadu">Tamil Nadu</option>
                                    <option value="Maharashtra">Maharashtra</option>
                                    <option value="other">Other (Specify)</option>
                                </select>
                                <input type="text" class="form-control hidden-input" name="state_other" id="state_other" placeholder="Enter State">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">City</label>
                                <select class="form-select" name="city_select" id="city_select" onchange="toggleOther('city_select', 'city_other')" required>
                                    <option value="" selected disabled>Select...</option>
                                    <option value="Bangalore">Bangalore</option>
                                    <option value="Mysore">Mysore</option>
                                    <option value="Chennai">Chennai</option>
                                    <option value="Mumbai">Mumbai</option>
                                    <option value="other">Other (Specify)</option>
                                </select>
                                <input type="text" class="form-control hidden-input" name="city_other" id="city_other" placeholder="Enter City">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Pincode / Zip</label>
                                <input type="text" class="form-control" name="pincode" placeholder="e.g. 560001" required>
                            </div>
                        </div>

                        <h4 class="section-title">Account Setup & Verification</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Profile Photo</label>
                                <input type="file" class="form-control" name="photo" accept=".jpg, .jpeg, .png" required>
                                <small class="text-muted">Allowed: JPG, JPEG, PNG.</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Create Password</label>
                                <input type="password" class="form-control" name="password" placeholder="••••••••" required>
                            </div>
                        </div>

                        <div class="p-3 bg-light border rounded mt-4">
                            <label class="form-label fw-bold">Step 1: Generate OTP</label>
                            <div class="input-group mb-3">
                                <select class="form-select" name="otp_method">
                                    <option value="email">Send via Email</option>
                                    <option value="phone">Send via Phone</option>
                                </select>
                                <button type="submit" name="send_otp" class="btn btn-warning px-4" formnovalidate>Send OTP</button>
                            </div>

                            <label class="form-label fw-bold">Step 2: Verify & Submit</label>
                            <input type="text" class="form-control mb-3" name="otp" placeholder="Enter 4-digit OTP generated above">
                            
                            <div class="d-flex gap-2">
                                <button type="submit" name="register" class="btn btn-success w-100">Complete Registration</button>
                                <button type="reset" class="btn btn-secondary w-50">Reset Form</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Function to show/hide the "Other" input fields based on dropdown selection
    function toggleOther(selectId, inputId) {
        var selectElement = document.getElementById(selectId);
        var inputElement = document.getElementById(inputId);
        
        if(selectElement.value === 'other') {
            inputElement.style.display = 'block';
            inputElement.setAttribute('required', 'required'); // Make it required when visible
        } else {
            inputElement.style.display = 'none';
            inputElement.removeAttribute('required'); // Remove requirement when hidden
            inputElement.value = ''; // Clear value
        }
    }
</script>

</body>
</html>