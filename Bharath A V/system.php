<?php
include 'config.php';
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$msg = "";
if (isset($_POST['add_system'])) {
    $system_no = $_POST['system_no'];
    $room_no = $_POST['room_no'];
    $seat_no = $_POST['seat_no'];

    $stmt = $conn->prepare("INSERT INTO systems(system_no, room_no, seat_no, status) VALUES(?,?,?, 'Available')");
    $stmt->bind_param("sss", $system_no, $room_no, $seat_no);
    if ($stmt->execute()) $msg = "System added successfully";
}

$list = $conn->query("SELECT * FROM systems ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Systems</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h2>System Management</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">Back</a>
    <?php if($msg) echo "<div class='alert alert-success'>$msg</div>"; ?>

    <div class="card p-3 mb-4">
        <form method="POST" class="row g-3">
            <div class="col-md-3"><input type="text" name="system_no" class="form-control" placeholder="System No" required></div>
            <div class="col-md-3"><input type="text" name="room_no" class="form-control" placeholder="Room No" required></div>
            <div class="col-md-3"><input type="text" name="seat_no" class="form-control" placeholder="Seat No" required></div>
            <div class="col-md-3"><button type="submit" name="add_system" class="btn btn-success w-100">Add System</button></div>
        </form>
    </div>

    <table class="table table-bordered">
        <tr><th>ID</th><th>System No</th><th>Room No</th><th>Seat No</th><th>Status</th></tr>
        <?php while($row = $list->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['system_no']; ?></td>
            <td><?php echo $row['room_no']; ?></td>
            <td><?php echo $row['seat_no']; ?></td>
            <td><?php echo $row['status']; ?></td>
        </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>