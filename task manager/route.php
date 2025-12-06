<?php
$p = $_GET['p']??'';

switch ($p) {
    case 'dashboard':
        require_once "Dashboard.php";
        break; 
    case 'kebiasaan':
        require_once "Kebiasaan.php";
        break;  
    case 'tugas':
        require_once "Tugas.php";
        break;
  
    default:
        require_once "Dashboard.php";
        break;
}
?>
