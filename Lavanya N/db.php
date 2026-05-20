<?php
// Central DB config for the portal.
// If you changed your XAMPP MySQL host/user/password/port, update them here.
$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "";
$DB_NAME = "seb_portal";
$DB_PORT = 3306;

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    die(
        "Connection Failed. " .
        "Host={$DB_HOST}, Port={$DB_PORT}, User={$DB_USER}, DB={$DB_NAME}. " .
        "Error: " . $e->getMessage() .
        ". Please start XAMPP MySQL and ensure port 3306 is available."
    );
}
?>
