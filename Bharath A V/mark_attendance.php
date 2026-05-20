<?php
include 'config.php';
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = intval($_POST['student_id']);
    $admin_id = $_SESSION['admin_id'];
    $captured_photo = $_POST['captured_photo'];

    $systemQuery = $conn->query("SELECT * FROM systems WHERE status='Available' ORDER BY id ASC LIMIT 1");
    if ($systemQuery->num_rows == 0) {
        die("No systems available");
    }

    $system = $systemQuery->fetch_assoc();
    $system_id = $system['id'];

    $photo_path = "";
    if (!empty($captured_photo)) {
        $img = str_replace('data:image/png;base64,', '', $captured_photo);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        $photo_path = 'uploads/photos/student_' . time() . '.png';
        file_put_contents($photo_path, $data);
    }

    $thumb_path = "";
    if (isset($_FILES['thumb_file']) && $_FILES['thumb_file']['error'] == 0) {
        $ext = pathinfo($_FILES['thumb_file']['name'], PATHINFO_EXTENSION);
        $thumb_path = 'uploads/thumbs/thumb_' . time() . '.' . $ext;
        move_uploaded_file($_FILES['thumb_file']['tmp_name'], $thumb_path);
    }

    $stmt = $conn->prepare("INSERT INTO attendance(student_id, admin_id, photo_capture, thumb_capture, attendance_status, allocated_system_id) VALUES(?,?,?,?, 'Present', ?)");
    $stmt->bind_param("iissi", $student_id, $admin_id, $photo_path, $thumb_path, $system_id);
    $stmt->execute();

    $update = $conn->prepare("UPDATE systems SET status='Occupied' WHERE id=?");
    $update->bind_param("i", $system_id);
    $update->execute();

    header("Location: seat_success.php?system_id=" . $system_id . "&student_id=" . $student_id);
    exit();
}