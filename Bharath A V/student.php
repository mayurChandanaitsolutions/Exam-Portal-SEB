<?php

$msg = "";
if (isset($_POST['add_student'])) {
    $barcode = $_POST['barcode'];
    $reg_no = $_POST['reg_no'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $exam_name = $_POST['exam_name'];
    $course_name = $_POST['course_name'];

    $stmt = $conn->prepare("INSERT INTO students(barcode, reg_no, full_name, email, phone, exam_name, course_name, status) VALUES(?,?,?,?,?,?,?, 'Approved')");
    $stmt->bind_param("sssssss", $barcode, $reg_no, $full_name, $email, $phone, $exam_name, $course_name);
    if ($stmt->execute()) {
        $msg = "Student added successfully";
    } else {
        $msg = "Failed to add student";
    }
}

$list = $conn->query("SELECT * FROM students ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h2>Student Management</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">Back</a>
    <?php if($msg) echo "<div class='alert alert-info'>$msg</div>"; ?>

    <div class="card p-3 mb-4">
        <form method="POST" class="row g-3">
            <div class="col-md-3"><input type="text" name="barcode" class="form-control" placeholder="Barcode" required></div>
            <div class="col-md-3"><input type="text" name="reg_no" class="form-control" placeholder="Register Number" required></div>
            <div class="col-md-3"><input type="text" name="full_name" class="form-control" placeholder="Full Name" required></div>
            <div class="col-md-3"><input type="email" name="email" class="form-control" placeholder="Email"></div>
            <div class="col-md-3"><input type="text" name="phone" class="form-control" placeholder="Phone"></div>
            <div class="col-md-3"><input type="text" name="exam_name" class="form-control" placeholder="Exam Name" required></div>
            <div class="col-md-3"><input type="text" name="course_name" class="form-control" placeholder="Course Name"></div>
            <div class="col-md-3"><button type="submit" name="add_student" class="btn btn-primary w-100">Add Student</button></div>
        </form>
    </div>

    <table class="table table-bordered table-striped">
        <tr>
            <th>ID</th><th>Barcode</th><th>Reg No</th><th>Name</th><th>Exam</th><th>Status</th>
        </tr>
        <?php while($row = $list->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['barcode']; ?></td>
            <td><?php echo $row['reg_no']; ?></td>
            <td><?php echo $row['full_name']; ?></td>
            <td><?php echo $row['exam_name']; ?></td>
            <td><?php echo $row['status']; ?></td>
        </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>