<?php
include 'config.php';
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$student_id = intval($_GET['student_id']);
$system_id = intval($_GET['system_id']);

$student = $conn->query("SELECT * FROM students WHERE id=$student_id")->fetch_assoc();
$system = $conn->query("SELECT * FROM systems WHERE id=$system_id")->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Attendance Success</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <div class="card p-4 text-center">
        <h2 class="text-success">Student Verified Successfully</h2>
        <p class="mt-3"><strong>Name:</strong> <?php echo $student['full_name']; ?></p>
        <p><strong>Register Number:</strong> <?php echo $student['reg_no']; ?></p>
        <p><strong>Exam:</strong> <?php echo $student['exam_name']; ?></p>
        <hr>
        <h4>Seat Allotted</h4>
        <p><strong>Room No:</strong> <?php echo $system['room_no']; ?></p>
        <p><strong>Seat No:</strong> <?php echo $system['seat_no']; ?></p>
        <p><strong>System No:</strong> <?php echo $system['system_no']; ?></p>
        <a href="scan_student.php" class="btn btn-primary mt-3">Verify Next Student</a>
    </div>
</div>
</body>
</html>