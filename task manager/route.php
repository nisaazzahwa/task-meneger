<?php
$p = $_GET['p']??'';

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
    case 'chart':
        require_once "chart.php";
        break;
    case 'delete':
        require_once "delete.php";
        break;
    default:
        require_once "Dashboard.php";
        break;
}
?>
