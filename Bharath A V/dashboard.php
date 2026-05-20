<?php
include 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$students = $conn->query("SELECT COUNT(*) AS total FROM students")->fetch_assoc()['total'];
$systems = $conn->query("SELECT COUNT(*) AS total FROM systems")->fetch_assoc()['total'];
$available = $conn->query("SELECT COUNT(*) AS total FROM systems WHERE status='Available'")->fetch_assoc()['total'];
$occupied = $conn->query("SELECT COUNT(*) AS total FROM systems WHERE status='Occupied'")->fetch_assoc()['total'];
$attendance = $conn->query("SELECT COUNT(*) AS total FROM attendance")->fetch_assoc()['total'];

$allotted_students = $conn->query("
    SELECT 
        students.full_name,
        students.reg_no,
        students.exam_name,
        students.phone,
        students.email,
        attendance.attendance_status,
        attendance.verification_time,
        systems.system_no,
        systems.room_no,
        systems.seat_no
    FROM attendance
    INNER JOIN students ON attendance.student_id = students.id
    LEFT JOIN systems ON attendance.allocated_system_id = systems.id
    ORDER BY attendance.id DESC
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
    <a class="navbar-brand" href="#">Exam Portal Admin</a>
    <div class="ms-auto text-white">
        Welcome, <?php echo $_SESSION['admin_name']; ?> |
        <a href="admin_logout.php" class="text-warning text-decoration-none">Logout</a>
    </div>
</nav>

<div class="container py-4">
    <h2 class="mb-4">Dashboard</h2>

    <div class="row g-4 mb-4">
        <div class="col-md-2">
            <div class="card dashboard-card bg-primary text-white p-3">
                <h6>Total Students</h6>
                <h3><?php echo $students; ?></h3>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card dashboard-card bg-success text-white p-3">
                <h6>Total Systems</h6>
                <h3><?php echo $systems; ?></h3>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card dashboard-card bg-info text-white p-3">
                <h6>Available</h6>
                <h3><?php echo $available; ?></h3>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card dashboard-card bg-danger text-white p-3">
                <h6>Occupied</h6>
                <h3><?php echo $occupied; ?></h3>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card dashboard-card bg-warning text-dark p-3">
                <h6>Attendance</h6>
                <h3><?php echo $attendance; ?></h3>
            </div>
        </div>
    </div>

    <div class="mb-4 d-flex gap-2 flex-wrap">
        <a href="scan_student.php" class="btn btn-primary">Scan / Verify Student</a>
        <a href="students.php" class="btn btn-outline-dark">Manage Students</a>
        <a href="systems.php" class="btn btn-outline-success">Manage Systems</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Students Allotted Seat Details</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Student Name</th>
                        <th>Reg No</th>
                        <th>Exam</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Attendance</th>
                        <th>System No</th>
                        <th>Room No</th>
                        <th>Seat No</th>
                        <th>Verified Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    if ($allotted_students->num_rows > 0) {
                        while ($row = $allotted_students->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['reg_no']); ?></td>
                        <td><?php echo htmlspecialchars($row['exam_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['attendance_status']); ?></td>
                        <td><?php echo htmlspecialchars($row['system_no']); ?></td>
                        <td><?php echo htmlspecialchars($row['room_no']); ?></td>
                        <td><?php echo htmlspecialchars($row['seat_no']); ?></td>
                        <td><?php echo htmlspecialchars($row['verification_time']); ?></td>
                    </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='11' class='text-center'>No students verified and allotted yet.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>