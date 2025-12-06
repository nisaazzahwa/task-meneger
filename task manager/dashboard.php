<?php
require_once "config.php";

/* =====================
   PROSES HAPUS KEBIASAAN
   ===================== */
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $konek->query("DELETE FROM kebiasaan WHERE id_kebiasaan=$id");
    echo "<script>alert('Kebiasaan berhasil dihapus!');window.location='?';</script>";
}

/* =====================
   PROSES SELESAI KEBIASAAN
   ===================== */
if (isset($_GET['selesai'])) {
    $id = intval($_GET['selesai']);
    $konek->query("UPDATE kebiasaan SET status='selesai' WHERE id_kebiasaan=$id");
    echo "<script>alert('Kebiasaan ditandai selesai!');window.location='?';</script>";
}

/* =====================
   QUERY TUGAS & KEBIASAAN
   ===================== */
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

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">

            <!-- CARD TUGAS -->
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
                                            <!-- Opsi lain bisa ditambah nanti -->
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

            <!-- CARD KEBIASAAN -->
            <div class="card">
                <div class="card-header bg-primary text-white">
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
                                <th>Status</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $no = 1;
                            if ($kebiasaan->num_rows > 0) {
                                while ($row = $kebiasaan->fetch_assoc()) {

                                    // warna status
                                    $badge = $row['status'] == "selesai"
                                        ? "<span class='badge badge-success'>Selesai</span>"
                                        : "<span class='badge badge-warning'>Belum</span>";

                                    echo "
                                    <tr>
                                        <td>{$no}</td>
                                        <td>" . htmlspecialchars($row['nama_kebiasaan']) . "</td>
                                        <td>" . htmlspecialchars($row['waktu']) . "</td>
                                        <td>" . htmlspecialchars($row['Deskripsi']) . "</td>
                                        <td>{$badge}</td>
                                        <td>
                                            <a href='?hapus={$row['id_kebiasaan']}'
                                                class='btn btn-danger btn-sm'
                                                onclick=\"return confirm('Yakin ingin menghapus?')\">
                                                Hapus
                                            </a>

                                            <a href='?selesai={$row['id_kebiasaan']}'
                                                class='btn btn-success btn-sm'
                                                onclick=\"return confirm('Tandai sebagai selesai?')\">
                                                Selesai
                                            </a>
                                        </td>
                                    </tr>";
                                    $no++;
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center'>Belum ada kebiasaan</td></tr>";
                            }
                            ?>

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>
</div>
