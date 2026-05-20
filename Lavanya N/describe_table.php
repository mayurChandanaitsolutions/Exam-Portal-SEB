<?php
require 'SEB_portal/config/db.php';
$result = $conn->query("DESCRIBE students;");
echo "Students table structure:\n";
while ($row = $result->fetch_assoc()) {
    echo $row['Field'] . ' (' . $row['Type'] . ') ' . ($row['Null'] == 'NO' ? 'NOT NULL' : '') . "\n";
}
$conn->close();
?>
