<?php
require_once 'config.php';
$aksi = $_GET['aksi'] ?? '';
$id = $_GET['id'] ?? '';

if ($aksi == 'hapus' && $id) {
    mysqli_query($konek, "DELETE FROM jadwal WHERE id_jadwal='$id'");
    echo "<script>alert('Jadwal berhasil dihapus!'); window.location='/?p=jadwal';</script>";
    exit;
}
?>

<div class="container mt-4">
    <?php

    // FORM TAMBAH JADWAL
    if ($aksi == 'tambah') {
        if (isset($_POST['simpan'])) {
            $kode_mk = $_POST['kode_mk'];
            $nama_mk = $_POST['nama_mk'];
            $dosen_pengampu = $_POST['dosen_pengampu'];
            $hari = $_POST['hari'];
            $jam_mulai = $_POST['jam_mulai'];
            $jam_selesai = $_POST['jam_selesai'];
            $ruang = $_POST['ruang'];

            $query = "INSERT INTO jadwal (kode_mk, nama_mk, dosen_pengampu, hari, jam_mulai, jam_selesai, ruang)
            VALUES ('$kode_mk', '$nama_mk', '$dosen_pengampu', '$hari', '$jam_mulai', '$jam_selesai', '$ruang')";
            if (mysqli_query($konek, $query)) {
                echo "<script>alert('Jadwal berhasil ditambahkan!'); window.location='?p=jadwal';</script>";
            } else {
                echo "<div class='alert alert-danger'>Gagal menambah jadwal!</div>";
            }
        }
    ?>
        <h3>Tambah Jadwal Kuliah</h3>
        <form method="POST">
            <div class="mb-3">
                <label>Kode MK</label>
                <input type="text" name="kode_mk" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Nama MK</label>
                <input type="text" name="nama_mk" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Dosen Pengampu</label>
                <input type="text" name="dosen_pengampu" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Hari</label>
                <select name="hari" class="form-select" required>
                    <option value="">-- Pilih Hari --</option>
                    <option>Senin</option>
                    <option>Selasa</option>
                    <option>Rabu</option>
                    <option>Kamis</option>
                    <option>Jumat</option>
                    <option>Sabtu</option>
                </select>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Jam Mulai</label>
                    <input type="time" name="jam_mulai" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Jam Selesai</label>
                    <input type="time" name="jam_selesai" class="form-control" required>
                </div>
            </div>
            <div class="mb-3">
                <label>Ruang</label>
                <input type="text" name="ruang" class="form-control" required>
            </div>
            <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
            <a href="?p=jadwal" class="btn btn-secondary">Kembali</a>
        </form>

    <?php

        // FORM EDIT JADWAL
    } elseif ($aksi == 'edit' && $id) {
        $data = mysqli_fetch_assoc(mysqli_query($konek, "SELECT * FROM jadwal WHERE id_jadwal='$id'"));

        if (isset($_POST['update'])) {
            $kode_mk = $_POST['kode_mk'];
            $nama_mk = $_POST['nama_mk'];
            $dosen_pengampu = $_POST['dosen_pengampu'];
            $hari = $_POST['hari'];
            $jam_mulai = $_POST['jam_mulai'];
            $jam_selesai = $_POST['jam_selesai'];
            $ruang = $_POST['ruang'];

            $update = "UPDATE jadwal SET 
kode_mk='$kode_mk',
nama_mk='$nama_mk',
dosen_pengampu='$dosen_pengampu',
hari='$hari',
    jam_mulai='$jam_mulai',
    jam_selesai='$jam_selesai',
    ruang='$ruang'
    WHERE id_jadwal='$id'";

            if (mysqli_query($konek, $update)) {
                echo "<script>alert('Jadwal berhasil diperbarui!'); window.location='?p=jadwal';</script>";
            } else {
                echo "<div class='alert alert-danger'>Gagal memperbarui jadwal!</div>";
            }
        }
    ?>
        <h3>Edit Jadwal Kuliah</h3>
        <form method="POST">
            <div class="mb-3">
                <label>Kode MK</label>
                <input type="text" name="kode_mk" value="<?= htmlspecialchars($data['kode_mk']) ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Nama MK</label>
                <input type="text" name="nama_mk" value="<?= htmlspecialchars($data['nama_mk']) ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Dosen Pengampu</label>
                <input type="text" name="dosen_pengampu" value="<?= htmlspecialchars($data['dosen_pengampu']) ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Hari</label>
                <select name="hari" class="form-select" required>
                    <option><?= htmlspecialchars($data['hari']) ?></option>
                    <option>Senin</option>
                    <option>Selasa</option>
                    <option>Rabu</option>
                    <option>Kamis</option>
                    <option>Jumat</option>
                    <option>Sabtu</option>
                </select>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Jam Mulai</label>
                    <input type="time" name="jam_mulai" value="<?= htmlspecialchars($data['jam_mulai']) ?>" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Jam Selesai</label>
                    <input type="time" name="jam_selesai" value="<?= htmlspecialchars($data['jam_selesai']) ?>" class="form-control" required>
                </div>
            </div>
            <div class="mb-3">
                <label>Ruang</label>
                <input type="text" name="ruang" value="<?= htmlspecialchars($data['ruang']) ?>" class="form-control" required>
            </div>
            <button type="submit" name="update" class="btn btn-warning">Update</button>
            <a href="?p=jadwal" class="btn btn-secondary">Kembali</a>
        </form>

    <?php

        // DETAIL JADWAL

    } elseif ($aksi == 'detail' && $id) {
        $data = mysqli_fetch_assoc(mysqli_query($konek, "SELECT * FROM jadwal WHERE id_jadwal='$id'"));
    ?>
        <h3>Detail Jadwal Kuliah</h3>
        <table class="table table-bordered">
            <tr>
                <th>Kode MK</th>
                <td><?= htmlspecialchars($data['kode_mk']) ?></td>
            </tr>
            <tr>
                <th>Nama MK</th>
                <td><?= htmlspecialchars($data['nama_mk']) ?></td>
            </tr>
            <tr>
                <th>Dosen Pengampu</th>
                <td><?= htmlspecialchars($data['dosen_pengampu']) ?></td>
            </tr>
            <tr>
                <th>Hari</th>
                <td><?= htmlspecialchars($data['hari']) ?></td>
            </tr>
            <tr>
                <th>Jam</th>
                <td><?= htmlspecialchars($data['jam_mulai']) ?> - <?= htmlspecialchars($data['jam_selesai']) ?></td>
            </tr>
            <tr>
                <th>Ruang</th>
                <td><?= htmlspecialchars($data['ruang']) ?></td>
            </tr>
        </table>
        <a href="?p=jadwal" class="btn btn-secondary">Kembali</a>

    <?php
        // TAMPILAN UTAMA
    } else {
    ?>
        <h3>Data Jadwal Kuliah</h3>
        <a href="?p=jadwal&aksi=tambah" class="btn btn-success mb-3">+ Tambah Jadwal</a>

        <table class="table table-bordered table-striped">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>Kode MK</th>
                    <th>Nama MK</th>
                    <th>Dosen</th>
                    <th>Hari</th>
                    <th>Jam</th>
                    <th>Ruang</th>
                    <th>Opsi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $query = mysqli_query($konek, "SELECT * FROM jadwal ORDER BY hari, jam_mulai ASC");
                if (mysqli_num_rows($query) > 0) {
                    while ($row = mysqli_fetch_assoc($query)) {
                        echo "<tr>";
                        echo "<td>{$no}</td>";
                        echo "<td>" . htmlspecialchars($row['kode_mk']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nama_mk']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['dosen_pengampu']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['hari']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['jam_mulai']) . " - " . htmlspecialchars($row['jam_selesai']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['ruang']) . "</td>";
                        echo "<td>
                <a href='?p=jadwal&aksi=detail&id=" . urlencode($row['id_jadwal']) . "' class='btn btn-info btn-sm'>Detail</a>
                <a href='?p=jadwal&aksi=edit&id=" . urlencode($row['id_jadwal']) . "' class='btn btn-warning btn-sm'>Edit</a>
                <a href='?p=jadwal&aksi=hapus&id=" . urlencode($row['id_jadwal']) . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin mau hapus jadwal ini?\")'>Hapus</a>
                </td>";
                        echo "</tr>";
                        $no++;
                    }
                } else {
                    echo "<tr><td colspan='8' class='text-center'>Belum ada jadwal kuliah</td></tr>";
                }
                ?>
            </tbody>
        </table>
    <?php } ?>
</div>