<?php
include 'config.php';
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$student = null;
$message = "";

if (isset($_POST['search_barcode'])) {
    $barcode = trim($_POST['barcode']);
    $stmt = $conn->prepare("SELECT * FROM students WHERE barcode=?");
    $stmt->bind_param("s", $barcode);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $student = $res->fetch_assoc();

        $check = $conn->prepare("SELECT id FROM attendance WHERE student_id=?");
        $check->bind_param("i", $student['id']);
        $check->execute();
        $checkRes = $check->get_result();
        if ($checkRes->num_rows > 0) {
            $message = "Attendance already marked for this student.";
            $student = null;
        }
    } else {
        $message = "Student not found for this barcode.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Scan Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container py-4">
    <a href="dashboard.php" class="btn btn-secondary mb-3">Back</a>
    <h2>Student Verification</h2>

    <?php if($message) echo "<div class='alert alert-warning'>$message</div>"; ?>

    <div class="card p-4 mb-4">
        <form method="POST" class="row g-3">
            <div class="col-md-9">
                <input type="text" name="barcode" class="form-control" placeholder="Scan or Enter Barcode" required>
            </div>
            <div class="col-md-3">
                <button type="submit" name="search_barcode" class="btn btn-primary w-100">Search Student</button>
            </div>
        </form>
    </div>

    <?php if($student) { ?>
    <div class="card p-4">
        <div class="row">
            <div class="col-md-6">
                <h4>Student Details</h4>
                <p><strong>Name:</strong> <?php echo $student['full_name']; ?></p>
                <p><strong>Reg No:</strong> <?php echo $student['reg_no']; ?></p>
                <p><strong>Exam:</strong> <?php echo $student['exam_name']; ?></p>
                <p><strong>Course:</strong> <?php echo $student['course_name']; ?></p>
                <p><strong>Status:</strong> <?php echo $student['status']; ?></p>
            </div>
            <div class="col-md-6">
                <h4>Capture Photo</h4>
                <video id="video" autoplay></video>
                <canvas id="canvas" class="mt-2" style="display:none;"></canvas>
                <button type="button" id="captureBtn" class="btn btn-dark mt-2">Capture Photo</button>
                <img id="preview" class="preview-img mt-2 d-block" style="display:none;">
            </div>
        </div>

        <form method="POST" action="mark_attendance.php" enctype="multipart/form-data" class="mt-4">
            <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
            <input type="hidden" name="captured_photo" id="captured_photo">

            <div class="mb-3">
                <label class="form-label">Upload Thumb Image</label>
                <input type="file" name="thumb_file" class="form-control" accept="image/*" required>
            </div>

            <button type="submit" class="btn btn-success">Mark Attendance & Auto Allocate Seat</button>
        </form>
    </div>
    <?php } ?>
</div>

<script>
const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const captureBtn = document.getElementById('captureBtn');
const preview = document.getElementById('preview');
const capturedPhoto = document.getElementById('captured_photo');

if (video) {
    navigator.mediaDevices.getUserMedia({ video: true })
    .then(stream => {
        video.srcObject = stream;
    })
    .catch(err => {
        console.log("Camera access denied", err);
    });
}

if (captureBtn) {
    captureBtn.addEventListener('click', function() {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        const dataURL = canvas.toDataURL('image/png');
        preview.src = dataURL;
        preview.style.display = 'block';
        capturedPhoto.value = dataURL;
    });
}
</script>
</body>
</html>
