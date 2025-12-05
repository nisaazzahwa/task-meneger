<?php
$konek = new mysqli("localhost", "root","", "siakademika");
if ($konek) {
} else {
    echo "Koneksi Gagal!";
}
?>