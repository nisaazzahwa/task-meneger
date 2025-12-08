<?php
$p = $_GET['p'] ?? 'dashboard';

switch ($p) {
    case 'dashboard':
        require_once "dashboard.php";
        break;
    case 'kebiasaan':
        require_once "kebiasaan.php";
        break;
    case 'tugas':
        require_once "tugas.php";
        break;
    case 'users_management':
        require_once "users_management.php";
        break;
    case 'user_detail':
        require_once "user_detail.php";
        break;
    default:
        require_once "dashboard.php";
        break;
}
?>
