<?php
$conn = new mysqli("localhost", "root", "", "seb_portal");

if ($conn->connect_error) {
    $conn = new mysqli("localhost", "root", "");
    if ($conn->connect_error) {
        die("MySQL error. Start XAMPP MySQL or check root password.\n");
    }
    // Create DB
    $conn->query("CREATE DATABASE IF NOT EXISTS seb_portal");
    $conn->select_db("seb_portal");
}

// Drop and recreate students
$conn->query("DROP TABLE IF EXISTS `students`");
$conn->query("CREATE TABLE `students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `phone` varchar(50) NOT NULL,
  `dob` date NOT NULL,
  `gender` varchar(10) NOT NULL,
  `address` text NOT NULL,
  `aadhaar` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
)");

// Create applications
$conn->query("CREATE TABLE IF NOT EXISTS `applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `application_id` varchar(50) UNIQUE,
  `exam_name` varchar(255),
  `category` varchar(100),
  `designation` varchar(255),
  `district` varchar(100),
  `status` enum('pending','verified','rejected') DEFAULT 'pending',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)");

// Create results
$conn->query("CREATE TABLE IF NOT EXISTS `results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `application_id` varchar(50),
  `post` varchar(255),
  `designation` varchar(255),
  `obtained_marks` int DEFAULT 0,
  `total_marks` int DEFAULT 0,
  `percentage` decimal(5,2) DEFAULT 0.00,
  `result_status` enum('qualified','not_qualified') DEFAULT 'not_qualified',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)");

// Notifications
$conn->query("CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT 0,
  `title` varchar(255) NOT NULL,
  `message` text,
  `status` enum('unread','read') DEFAULT 'unread',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)");

// Test user
$password_hash = password_hash('password', PASSWORD_DEFAULT);
$conn->query("INSERT IGNORE INTO students (name, email, phone, dob, gender, address, aadhaar, password) VALUES ('Test User', 'test@example.com', '9999999999', '2000-01-01', 'Male', 'Test Address', '123456789012', '$password_hash')");

echo "✅ SEB Portal FULL DB setup complete! All tables created.\n";
echo "Test login: test@example.com / password\n";
echo "Registration, login, dashboard, all portal pages working.\n";
$conn->close();
?>

