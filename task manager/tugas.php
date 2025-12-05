<?php
require_once 'config.php';

$aksi = $_GET['aksi'] ?? ''; 
$id = $_GET['id'] ?? '';

if ($aksi == 'hapus' && $id) {
  mysqli_query($konek, "DELETE FROM dosen WHERE id_user='$id'");
  echo "<script>alert('Data dosen berhasil dihapus!'); window.location='?p=dosen';</script>";
  exit;
}
?>

<div class="container mt-4">
<?php

// FORM TAMBAH DOSEN
if ($aksi == 'tambah') {
  if (isset($_POST['simpan'])) {
    $nip = $_POST['NIP'];
    $nama = $_POST['Nama'];
    $alamat = $_POST['mata_kuliah'];

    $query = "INSERT INTO dosen (NIP, Nama, mata_kuliah) VALUES ('$nip', '$nama', '$mata_kuliah')";
    if (mysqli_query($konek, $query)) {
      echo "<script>alert('Data dosen berhasil ditambahkan!'); window.location='?p=dosen';</script>";
    } else {
      echo "<div class='alert alert-danger'>Gagal menambah data!</div>";
    }
  }
  ?>
  <h3>Tambah Dosen</h3>
  <form method="POST">
    <div class="mb-3">
      <label>NIP</label>
      <input type="text" name="NIP" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Nama</label>
      <input type="text" name="Nama" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Mata kuliah</label>
      <input type="text" name="mata_kuliah" class="form-control" required>
    </div>
    <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
    <a href="?p=dosen" class="btn btn-secondary">Kembali</a>
  </form>

  <?php
//edit dosen
} elseif ($aksi == 'edit' && $id) {
  $data = mysqli_fetch_assoc(mysqli_query($konek, "SELECT * FROM dosen WHERE id_user='$id'"));
  if (isset($_POST['update'])) {
    $nip = $_POST['NIP'];
    $nama = $_POST['Nama'];
    $alamat = $_POST['mata_kuliah'];

    $update = "UPDATE dosen SET NIP='$nip', Nama='$nama', mata_kuliah='$mata_kuliah' WHERE id_user='$id'";
    if (mysqli_query($konek, $update)) {
      echo "<script>alert('Data berhasil diperbarui!'); window.location='?p=dosen';</script>";
    } else {
      echo "<div class='alert alert-danger'>Gagal memperbarui data!</div>";
    }
  }
  ?>
  <h3>Edit Dosen</h3>
  <form method="POST">
    <div class="mb-3">
      <label>NIP</label>
      <input type="text" name="NIP" value="<?= htmlspecialchars($data['NIP']) ?>" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Nama</label>
      <input type="text" name="Nama" value="<?= htmlspecialchars($data['Nama']) ?>" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Mata kuliah</label>
      <input type="text" name="mata_kuliah" value="<?= htmlspecialchars($data['mata_kuliah']) ?>" class="form-control" required>
    </div>
    <button type="submit" name="update" class="btn btn-warning">Update</button>
    <a href="?p=dosen" class="btn btn-secondary">Kembali</a>
  </form>

  <?php

// HALAMAN DETAIL DOSEN
} elseif ($aksi == 'detail' && $id) {
  $data = mysqli_fetch_assoc(mysqli_query($konek, "SELECT * FROM dosen WHERE id_user='$id'"));
  ?>
  <h3>Detail Dosen</h3>
  <table class="table table-bordered">
    <tr><th>NIP</th><td><?= htmlspecialchars($data['NIP']) ?></td></tr>
    <tr><th>Nama</th><td><?= htmlspecialchars($data['Nama']) ?></td></tr>
    <tr><th>Mata kuliah</th><td><?= htmlspecialchars($data['mata_kuliah']) ?></td></tr>
  </table>
  <a href="?p=dosen" class="btn btn-secondary">Kembali</a>

  <?php
// TAMPIL DATA DOSEN 

} else {
  ?>
  <h3>Data Dosen</h3>
  <a href="?p=dosen&aksi=tambah" class="btn btn-success mb-3">+ Tambah Dosen</a>

  <table class="table table-bordered table-striped">
    <thead class="table-primary">
      <tr>
        <th>No</th>
        <th>NIP</th>
        <th>Nama</th>
        <th>Mata kuliah</th>
        <th>Opsi</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $no = 1;
      $query = mysqli_query($konek, "SELECT * FROM dosen");
      if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
          echo "<tr>";
          echo "<td>{$no}</td>";
          echo "<td>" . htmlspecialchars($row['NIP']) . "</td>";
          echo "<td>" . htmlspecialchars($row['Nama']) . "</td>";
          echo "<td>" . htmlspecialchars($row['mata_kuliah']) . "</td>";
          echo "<td>
                  <a href='?p=dosen&aksi=detail&id=" . urlencode($row['id_user']) . "' class='btn btn-info btn-sm'>Detail</a>
                  <a href='?p=dosen&aksi=edit&id=" . urlencode($row['id_user']) . "' class='btn btn-warning btn-sm'>Edit</a>
                  <a href='?p=dosen&aksi=hapus&id=" . urlencode($row['id_user']) . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin mau hapus data ini?\")'>Hapus</a>
                </td>";
          echo "</tr>";
          $no++;
        }
      } else {
        echo "<tr><td colspan='5' class='text-center'>Belum ada data dosen</td></tr>";
      }
      ?>
    </tbody>
  </table>
<?php } ?>
</div>
