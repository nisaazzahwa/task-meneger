<?php
$p = $_GET['p']??'';

switch ($p) {
    case 'dosen':
        require_once "dosen.php";
        break;
    case 'mahasiswa':
        require_once "mahasiswa.php";
        break;
    case 'add-mahasiswa':
        require_once "add-mahasiswa.php";
        break;
    case 'delete':
        require_once "delete.php";
        break;
    case 'prodi':
        require_once "prodi.php";
        break;
    case 'jadwal_kuliah':
        require_once "jadwal_kuliah.php";
        break;
    case'prodi':
        require_once "prodi.phpp";
        break;

    default:
        require_once "Dashboard.php";
        break;
}
?>