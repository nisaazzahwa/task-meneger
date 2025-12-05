<?php
require_once 'config.php';

$aksi = $_GET['aksi'] ?? ''; 
$id = $_GET['id'] ?? '';

if ($aksi == 'hapus' && $id) {
  mysqli_query($konek, "DELETE FROM tugas WHERE id_tugas='$id'");
  echo "<script>alert('Data tugas berhasil dihapus!'); window.location='?p=tugas';</script>";
  exit;
}
?>

<div class="container mt-4">
<?php


// FORM TAMBAH TUGAS
if ($aksi == 'tambah') {
  if (isset($_POST['simpan'])) {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $deadline = $_POST['deadline'];

    $query = "INSERT INTO tugas (judul, deskripsi, deadline) 
              VALUES ('$judul', '$deskripsi', '$deadline')";
    if (mysqli_query($konek, $query)) {
      echo "<script>alert('Data tugas berhasil ditambahkan!'); window.location='?p=tugas';</script>";
    } else {
      echo "<div class='alert alert-danger'>Gagal menambah data!</div>";
    }
  }
  ?>

  <h3>Tambah Tugas</h3>
  <form method="POST">
    <div class="mb-3">
      <label>Judul Tugas</label>
      <input type="text" name="judul" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Deskripsi</label>
      <textarea name="deskripsi" class="form-control" required></textarea>
    </div>
    <div class="mb-3">
      <label>Deadline</label>
      <input type="date" name="deadline" class="form-control" required>
    </div>
    <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
    <a href="?p=tugas" class="btn btn-secondary">Kembali</a>
  </form>

  <?php

// EDIT TUGAS
} elseif ($aksi == 'edit' && $id) {

  $data = mysqli_fetch_assoc(mysqli_query($konek, "SELECT * FROM tugas WHERE id_tugas='$id'"));

  if (isset($_POST['update'])) {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $deadline = $_POST['deadline'];

    $update = "UPDATE tugas SET 
                judul='$judul',
                deskripsi='$deskripsi',
                deadline='$deadline'
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
      <label>Judul Tugas</label>
      <input type="text" name="judul" value="<?= htmlspecialchars($data['judul']) ?>" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Deskripsi</label>
      <textarea name="deskripsi" class="form-control" required><?= htmlspecialchars($data['deskripsi']) ?></textarea>
    </div>
    <div class="mb-3">
      <label>Deadline</label>
      <input type="date" name="deadline" value="<?= htmlspecialchars($data['deadline']) ?>" class="form-control" required>
    </div>
    <button type="submit" name="update" class="btn btn-warning">Update</button>
    <a href="?p=tugas" class="btn btn-secondary">Kembali</a>
  </form>

  <?php

// DETAIL TUGAS
} elseif ($aksi == 'detail' && $id) {

  $data = mysqli_fetch_assoc(mysqli_query($konek, "SELECT * FROM tugas WHERE id_tugas='$id'"));
  ?>

  <h3>Detail Tugas</h3>
  <table class="table table-bordered">
    <tr><th>Judul Tugas</th><td><?= htmlspecialchars($data['judul']) ?></td></tr>
    <tr><th>Deskripsi</th><td><?= htmlspecialchars($data['deskripsi']) ?></td></tr>
    <tr><th>Deadline</th><td><?= htmlspecialchars($data['deadline']) ?></td></tr>
  </table>
  <a href="?p=tugas" class="btn btn-secondary">Kembali</a>

  <?php

// TAMPIL DATA TUGAS
} else { ?>

  <h3>Data Tugas</h3>
  <a href="?p=tugas&aksi=tambah" class="btn btn-success mb-3">+ Tambah Tugas</a>

  <table class="table table-bordered table-striped">
    <thead class="table-primary">
      <tr>
        <th>No</th>
        <th>Judul</th>
        <th>Deskripsi</th>
        <th>Deadline</th>
        <th>Opsi</th>
      </tr>
    </thead>
    <tbody>

      <?php
      $no = 1;
      $query = mysqli_query($konek, "SELECT * FROM tugas");

      if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
          echo "<tr>";
          echo "<td>{$no}</td>";
          echo "<td>" . htmlspecialchars($row['judul']) . "</td>";
          echo "<td>" . htmlspecialchars($row['deskripsi']) . "</td>";
          echo "<td>" . htmlspecialchars($row['deadline']) . "</td>";
          echo "<td>
                  <a href='?p=tugas&aksi=detail&id=" . urlencode($row['id_tugas']) . "' class='btn btn-info btn-sm'>Detail</a>
                  <a href='?p=tugas&aksi=edit&id=" . urlencode($row['id_tugas']) . "' class='btn btn-warning btn-sm'>Edit</a>
                  <a href='?p=tugas&aksi=hapus&id=" . urlencode($row['id_tugas']) . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin mau hapus data ini?\")'>Hapus</a>
                </td>";
          echo "</tr>";
          $no++;
        }
      } else {
        echo "<tr><td colspan='5' class='text-center'>Belum ada data tugas</td></tr>";
      }
      ?>

    </tbody>
  </table>

<?php } ?>
</div>
