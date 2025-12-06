<?php
require_once 'config.php';

$aksi = $_GET['aksi'] ?? ''; 
$id   = $_GET['id'] ?? '';


if ($aksi == 'hapus' && $id) {
  mysqli_query($konek, "DELETE FROM tugas WHERE id_tugas='$id'");
  echo "<script>alert('Data tugas berhasil dihapus!'); window.location='?p=tugas';</script>";
  exit;
}
?>

<div class="container mt-4">

<?php

if ($aksi == 'tambah') {

  if (isset($_POST['simpan'])) {

    $nama_tugas      = $_POST['nama_tugas'];
    $deskripsi       = $_POST['deskripsi'];
    $deadline        = $_POST['deadline'];
    $prioritas       = $_POST['prioritas'];
    $status          = $_POST['status'];
    $estimasi_waktu  = $_POST['estimasi_waktu'];

    $query = "INSERT INTO tugas (nama_tugas, deskripsi, deadline, prioritas, status, ekstimasi_waktu)
              VALUES ('$nama_tugas', '$deskripsi', '$deadline', '$prioritas', '$status', '$estimasi_waktu')";

    if (mysqli_query($konek, $query)) {
      echo "<script>alert('Data tugas berhasil ditambahkan!'); window.location='?p=tugas';</script>";
    } else {
      echo "<div class='alert alert-danger'>Gagal menambah data: " . mysqli_error($konek) . "</div>";
    }
  }
?>

<h3>Tambah Tugas</h3>

<form method="POST">

  <div class="mb-3">
    <label>Nama Tugas</label>
    <input type="text" name="nama_tugas" class="form-control" required>
  </div>

  <div class="mb-3">
    <label>Deskripsi</label>
    <textarea name="deskripsi" class="form-control" required></textarea>
  </div>

  <div class="mb-3">
    <label>Deadline</label>
    <input type="datetime-local" name="deadline" class="form-control" required>
  </div>

  <div class="mb-3">
    <label>Prioritas</label>
    <select name="prioritas" class="form-control" required>
      <option value="rendah">Rendah</option>
      <option value="sedang">Sedang</option>
      <option value="tinggi">Tinggi</option>
    </select>
  </div>

  <div class="mb-3">
    <label>Status</label>
    <select name="status" class="form-control" required>
      <option value="belum">Belum</option>
      <option value="selesai">Selesai</option>
    </select>
  </div>

  <div class="mb-3">
    <label>Estimasi Waktu</label>
    <input type="time" name="estimasi_waktu" class="form-control" required>
  </div>

  <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
  <a href="?p=tugas" class="btn btn-secondary">Kembali</a>

</form>

<?php

} elseif ($aksi == 'edit' && $id) {

  $data = mysqli_fetch_assoc(mysqli_query($konek, "SELECT * FROM tugas WHERE id_tugas='$id'"));

  if (isset($_POST['update'])) {

    $nama_tugas      = $_POST['nama_tugas'];
    $deskripsi       = $_POST['deskripsi'];
    $deadline        = $_POST['deadline'];
    $prioritas       = $_POST['prioritas'];
    $status          = $_POST['status'];
    $estimasi_waktu  = $_POST['estimasi_waktu'];

    $update = "UPDATE tugas SET 
                nama_tugas='$nama_tugas',
                deskripsi='$deskripsi',
                deadline='$deadline',
                prioritas='$prioritas',
                status='$status',
                ekstimasi_waktu='$estimasi_waktu'
               WHERE id_tugas='$id'";

    if (mysqli_query($konek, $update)) {
      echo "<script>alert('Data berhasil diperbarui!'); window.location='?p=tugas';</script>";
    } else {
      echo "<div class='alert alert-danger'>Gagal memperbarui data!</div>";
    }
  }
?>

<h3>Edit Tugas</h3>

<form method="POST">

  <div class="mb-3">
    <label>Nama Tugas</label>
    <input type="text" name="nama_tugas" value="<?= htmlspecialchars($data['nama_tugas']) ?>" class="form-control" required>
  </div>

  <div class="mb-3">
    <label>Deskripsi</label>
    <textarea name="deskripsi" class="form-control" required><?= htmlspecialchars($data['deskripsi']) ?></textarea>
  </div>

  <div class="mb-3">
    <label>Deadline</label>
    <input type="datetime-local" name="deadline" value="<?= date('Y-m-d\TH:i', strtotime($data['deadline'])) ?>" class="form-control" required>
  </div>

  <div class="mb-3">
    <label>Prioritas</label>
    <select name="prioritas" class="form-control" required>
      <option value="rendah"  <?= ($data['prioritas']=='rendah')?'selected':'' ?> >Rendah</option>
      <option value="sedang"  <?= ($data['prioritas']=='sedang')?'selected':'' ?> >Sedang</option>
      <option value="tinggi"  <?= ($data['prioritas']=='tinggi')?'selected':'' ?> >Tinggi</option>
    </select>
  </div>

  <div class="mb-3">
    <label>Status</label>
    <select name="status" class="form-control" required>
      <option value="belum"   <?= ($data['status']=='belum')?'selected':'' ?> >Belum</option>
      <option value="selesai" <?= ($data['status']=='selesai')?'selected':'' ?> >Selesai</option>
    </select>
  </div>

  <div class="mb-3">
    <label>Estimasi Waktu</label>
    <input type="time" name="estimasi_waktu" value="<?= htmlspecialchars($data['ekstimasi_waktu']) ?>" class="form-control" required>
  </div>

  <button type="submit" name="update" class="btn btn-warning">Update</button>
  <a href="?p=tugas" class="btn btn-secondary">Kembali</a>

</form>

<?php

} elseif ($aksi == 'detail' && $id) {

  $data = mysqli_fetch_assoc(mysqli_query($konek, "SELECT * FROM tugas WHERE id_tugas='$id'"));
?>

<h3>Detail Tugas</h3>

<table class="table table-bordered">
  <tr><th>Nama Tugas</th><td><?= htmlspecialchars($data['nama_tugas']) ?></td></tr>
  <tr><th>Deskripsi</th><td><?= htmlspecialchars($data['deskripsi']) ?></td></tr>
  <tr><th>Deadline</th><td><?= htmlspecialchars($data['deadline']) ?></td></tr>
  <tr><th>Prioritas</th><td><?= htmlspecialchars($data['prioritas']) ?></td></tr>
  <tr><th>Status</th><td><?= htmlspecialchars($data['status']) ?></td></tr>
  <!-- FIXED: Changed data key to 'ekstimasi_waktu' -->
  <tr><th>Estimasi Waktu</th><td><?= htmlspecialchars($data['ekstimasi_waktu']) ?></td></tr>
</table>

<a href="?p=tugas" class="btn btn-secondary">Kembali</a>

<?php

} else { ?>

<h3>Data Tugas</h3>

<a href="?p=tugas&aksi=tambah" class="btn btn-success mb-3">+ Tambah Tugas</a>

<table class="table table-bordered table-striped">
  <thead class="table-primary">
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
$query = mysqli_query($konek, "SELECT * FROM tugas ORDER BY id_tugas DESC");

if (mysqli_num_rows($query) > 0) {
  while ($row = mysqli_fetch_assoc($query)) {

    echo "<tr>";
    echo "<td>{$no}</td>";
    echo "<td>" . htmlspecialchars($row['nama_tugas']) . "</td>";
    echo "<td>" . date('d M Y H:i', strtotime($row['deadline'])) . "</td>";
    echo "<td>" . htmlspecialchars($row['prioritas']) . "</td>";
    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
    echo "<td>" . htmlspecialchars($row['ekstimasi_waktu']) . "</td>";
    echo "<td>
            <a href='?p=tugas&aksi=detail&id=" . urlencode($row['id_tugas']) . "' class='btn btn-info btn-sm'>Detail</a>
            <a href='?p=tugas&aksi=edit&id=" . urlencode($row['id_tugas']) . "' class='btn btn-warning btn-sm'>Edit</a>
            <a href='?p=tugas&aksi=hapus&id=" . urlencode($row['id_tugas']) . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin mau hapus data ini?\")'>Hapus</a>
          </td>";

    echo "</tr>";

    $no++;
  }
} else {
  echo "<tr><td colspan='7' class='text-center'>Belum ada data tugas</td></tr>";
}
?>

  </tbody>
</table>

<?php 
} 
?>
</div>
