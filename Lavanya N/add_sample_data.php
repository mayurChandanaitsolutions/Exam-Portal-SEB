<?php
/**
 * Fixed sample data script - uses shared DB config and prepared statements
 * Run: php add_sample_data.php
 */

// Load shared DB config (now with detailed error reporting)
require_once __DIR__ . '/SEB_portal/config/db.php';

echo "✅ DB connected (Host:localhost:3306, DB:seb_portal)\n";

// Get test student ID (1)
$test_student_id = 1;

// Check if sample application exists
$app_check = $conn->query("SELECT id FROM applications WHERE student_id = $test_student_id LIMIT 1");
if ($app_check->num_rows == 0) {
    $app_id = 'SEB' . time() . '001';
    $exam_name = 'State Civil Services Exam 2026';
    $category = 'General';
    $designation = 'Sub-Inspector';
    $district = 'Hyderabad';
    $status = 'verified';
    
    $stmt = $conn->prepare("INSERT INTO applications (student_id, application_id, exam_name, category, designation, district, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssss", $test_student_id, $app_id, $exam_name, $category, $designation, $district, $status);
    $stmt->execute();
    echo "➕ Created sample application: $app_id\n";
} else {
    $row = $app_check->fetch_assoc();
    $app_id = $conn->query("SELECT application_id FROM applications WHERE id = " . $row['id'])->fetch_assoc()['application_id'];
}

// Check if sample result exists
$result_check = $conn->query("SELECT id FROM results WHERE student_id = $test_student_id LIMIT 1");
if ($result_check->num_rows == 0) {
    $post = 'State Civil Services';
    $obtained_marks = 156;
    $total_marks = 200;
    $percentage = 78.00;
    $result_status = 'qualified';
    
    $stmt = $conn->prepare("INSERT INTO results (student_id, application_id, post, designation, obtained_marks, total_marks, percentage, result_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssidds", $test_student_id, $app_id, $post, $designation, $obtained_marks, $total_marks, $percentage, $result_status);
    $stmt->execute();
    echo "➕ Created sample result\n";
}

echo "✅ Sample data added successfully!\n";
echo "Login: test@example.com / password\n";
echo "Check Results page. Application ID: $app_id\n";

$conn->close();
?>

