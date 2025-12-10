<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);
require_once "config.php";

if (!isset($_SESSION['user'])) {
    header("Location: ../index.php"); 
    exit();
}

$username = $_SESSION['user'];
$user_id = 0;
$role = $_SESSION['role'] ?? 'user';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $u_query = $konek->query("SELECT id, role FROM users WHERE username='$username'");
    if ($u_query && $u_query->num_rows > 0) {
        $u_row = $u_query->fetch_assoc();
        $user_id = $u_row['id'];
        $role = $u_row['role'];
        $_SESSION['user_id'] = $user_id;
        $_SESSION['role'] = $role;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Task Manager</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <!-- 1. STANDARD FONTS & ICONS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="asset/css/adminlte.css" />

    <!-- 2. DATATABLES CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" />
</head>

<!-- FIXED BODY CLASSES: Removed 'sidebar-open' so it works on mobile -->
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    
    <div class="app-wrapper">
        
        <!-- HEADER -->
        <nav class="app-header navbar navbar-expand bg-body">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item"> 
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"><i class="bi bi-list"></i></a> 
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <span class="d-none d-md-inline fw-bold"><?= htmlspecialchars($username) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <li class="user-footer">
                                <a href="../logout.php" class="btn btn-default btn-flat float-end">Sign out</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
        
        <!-- SIDEBAR -->
        <aside class="app-sidebar bg-primary shadow" data-bs-theme="dark">
            <div class="sidebar-brand">
                <a href="./" class="brand-link">
                    <span class="brand-text fw-light">Task Manager</span>
                </a>
            </div>
            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">
                        <li class="nav-header">MENU UTAMA</li>
                        <li class="nav-item">
                            <a href="?p=dashboard" class="nav-link">
                                <i class="nav-icon bi bi-speedometer2"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="?p=kebiasaan" class="nav-link">
                                <i class="nav-icon bi bi-calendar-check"></i>
                                <p>Kebiasaan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="?p=tugas" class="nav-link">
                                <i class="nav-icon bi bi-list-task"></i>
                                <p>Tugas</p>
                            </a>
                        </li>

                        <!-- ADMIN ONLY LINK -->
                        <?php if ($role == 'admin'): ?>
                        <li class="nav-header">ADMINISTRATOR</li>
                        <li class="nav-item">
                            <a href="?p=users_management" class="nav-link">
                                <i class="nav-icon bi bi-people-fill"></i>
                                <p>Users Management</p>
                            </a>
                        </li>
                        <?php endif; ?>

                    </ul>
                </nav>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="app-main">
            <?php require_once "route.php"; ?>
        </main>

    </div>

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"></script>
    <script src="asset/js/adminlte.js"></script>

    <!-- 4. DATATABLES JS & JQUERY -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarWrapper = document.querySelector('.sidebar-wrapper');
            if (sidebarWrapper && OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined) {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: { theme: 'os-theme-light', autoHide: 'leave', clickScroll: true },
                });
            }
        });

        // Initialize DataTables with Responsive Settings
        $(document).ready(function() {
            $('.datatable').DataTable({
                responsive: true,
                autoWidth: false, 
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                }
            });
        });
    </script>
</body>
</html>
