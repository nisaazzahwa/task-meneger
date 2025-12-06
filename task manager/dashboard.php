<?php
require_once "config.php";
require_once "route.php";
$tugas = $konek->query("
    SELECT * FROM tugas 
    WHERE deadline >= CURDATE()
    ORDER BY deadline ASC
");

$kebiasaan = $konek->query("
    SELECT * FROM kebiasaan
    ORDER BY id_kebiasaan DESC
");
?>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h3 class="card-title">Deadline Terbaru</h3>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Tugas</th>
                    <th>Deadline</th>
                    <th>Prioritas</th>
                    <th>Status</th>
                    <th>Estimasi Waktu</th>
                    <th>Opsi</th>
                </tr>
            </thead>
            <tbody>

                <?php
                $no = 1;
                if ($tugas->num_rows > 0) {
                    while ($row = $tugas->fetch_assoc()) {
                        echo "
                    <tr>
                        <td>{$no}</td>
                        <td>" . htmlspecialchars($row['judul']) . "</td>
                        <td>" . htmlspecialchars($row['deadline']) . "</td>
                        <td>" . htmlspecialchars($row['prioritas']) . "</td>
                        <td>" . htmlspecialchars($row['status']) . "</td>
                        <td>" . htmlspecialchars($row['estimasi_waktu']) . "</td>
                        <td>
                            <a href='edit_tugas.php?id={$row['id_tugas']}' class='btn btn-warning btn-sm'>Edit</a>
                            <a href='hapus_tugas.php?id={$row['id_tugas']}' class='btn btn-danger btn-sm'>Hapus</a>
                        </td>
                    </tr>";
                        $no++;
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>Tidak ada tugas untuk hari ini</td></tr>";
                }
                ?>

            </tbody>
        </table>
    </div>
</div>

<br>


<div class="card">
    <div class="card-header bg-success text-white">
        <h3 class="card-title">Kebiasaan Terbaru</h3>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kebiasaan</th>
                    <th>Waktu</th>
                    <th>Deskripsi</th>
                    <th>Opsi</th>
                </tr>
            </thead>
            <tbody>

                <?php
                $no = 1;
                if ($kebiasaan->num_rows > 0) {
                    while ($row = $kebiasaan->fetch_assoc()) {
                        echo "
                    <tr>
                        <td>{$no}</td>
                        <td>" . htmlspecialchars($row['nama_kebiasaan']) . "</td>
                        <td>" . htmlspecialchars($row['waktu']) . "</td>
                        <td>" . htmlspecialchars($row['keterangan']) . "</td>
                        <td>
                            <a href='edit_kebiasaan.php?id={$row['id_kebiasaan']}' class='btn btn-warning btn-sm'>Edit</a>
                            <a href='hapus_kebiasaan.php?id={$row['id_kebiasaan']}' class='btn btn-danger btn-sm'>Hapus</a>
                        </td>
                    </tr>";
                        $no++;
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>Belum ada kebiasaan</td></tr>";
                }
                ?>

            </tbody>
        </table>
    </div>
</div>