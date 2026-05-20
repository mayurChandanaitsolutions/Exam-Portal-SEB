<?php
// admin/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SEB Portal - Admin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .seb-header { background-color: #0d6efd; color: white; padding: 15px 0; }
        .seb-footer { background-color: #f8f9fa; padding: 20px 0; margin-top: auto; border-top: 1px solid #dee2e6; }
        body { display: flex; flex-direction: column; min-height: 100vh; }
        .main-content { flex: 1; }
    </style>
</head>
<body>
    <header class="seb-header shadow-sm">
        <div class="container d-flex justify-content-between align-items-center">
            <h4 class="m-0">SEB Admin Portal</h4>
            <?php if(isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                <a href="logout.php" class="btn btn-light btn-sm">Logout</a>
            <?php endif; ?>
        </div>
    </header>
    <div class="main-content container mt-4">