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
    case 'delete':
        require_once "delete.php";
        break;
    default:
        require_once "dashboard.php";
        break;
}
?>
