<?php
require_once 'config.php';
require_once 'route.php';

$aksi = $_GET['aksi'] ?? ''; 
$id   = $_GET['id'] ?? '';

if ($aksi == 'hapus' && $id) {
  mysqli_query($konek, "DELETE FROM kebiasaan WHERE id_kebiasaan='$id'");
  echo "<script>alert('Data kebiasaan berhasil dihapus!'); window.location='?p=kebiasaan';</script>";
  exit;
}
?>

<div class="container mt-4">

<?php


if ($aksi == 'tambah') {

  if (isset($_POST['simpan'])) {

    $nama_kebiasaan = $_POST['nama_kebiasaan'];
    $deskripsi      = $_POST['deskripsi']; 
    $waktu          = $_POST['waktu'];
    $frequensi      = $_POST['frequensi'];

    $query = "INSERT INTO kebiasaan (nama_kebiasaan, deskripsi_kebiasaan, waktu, frequensi)
              VALUES ('$nama_kebiasaan', '$deskripsi', '$waktu', '$frequensi')";

    if (mysqli_query($konek, $query)) {
      echo "<script>alert('Data kebiasaan berhasil ditambahkan!'); window.location='?p=kebiasaan';</script>";
    } else {
      echo "<div class='alert alert-danger'>Gagal menambah data! " . mysqli_error($konek) . "</div>";
    }
  }
?>

<h3>Tambah Kebiasaan</h3>

<form method="POST">

  <div class="mb-3">
    <label>Nama Kebiasaan</label>
    <input type="text" name="nama_kebiasaan" class="form-control" required>
  </div>

  <div class="mb-3">
    <label>Deskripsi</label>
    <textarea name="deskripsi" class="form-control" required></textarea>
  </div>

  <div class="mb-3">
    <label>Waktu</label>
    <input type="time" name="waktu" class="form-control" required>
  </div>

  <div class="mb-3">
    <label>Frequensi</label>
    <select name="frequensi" class="form-control" required>
      <option value="">-- Pilih --</option>
      <option value="senin">Senin</option>
      <option value="selasa">Selasa</option>
      <option value="rabu">Rabu</option>
      <option value="kamis">Kamis</option>
      <option value="jumat">Jumat</option>
      <option value="sabtu">Sabtu</option>
      <option value="minggu">Minggu</option>
    </select>
  </div>

  <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
  <a href="?p=kebiasaan" class="btn btn-secondary">Kembali</a>

</form>

<?php


} elseif ($aksi == 'edit' && $id) {

  $data = mysqli_fetch_assoc(mysqli_query($konek, "SELECT * FROM kebiasaan WHERE id_kebiasaan='$id'"));

  if (isset($_POST['update'])) {

    $nama_kebiasaan = $_POST['nama_kebiasaan'];
    $deskripsi      = $_POST['deskripsi'];
    $waktu          = $_POST['waktu'];
    $frequensi      = $_POST['frequensi'];

    $update = "UPDATE kebiasaan SET 
                nama_kebiasaan='$nama_kebiasaan',
                deskripsi_kebiasaan='$deskripsi',
                waktu='$waktu',
                frequensi='$frequensi'
               WHERE id_kebiasaan='$id'";

    if (mysqli_query($konek, $update)) {
      echo "<script>alert('Data berhasil diperbarui!'); window.location='?p=kebiasaan';</script>";
    } else {
      echo "<div class='alert alert-danger'>Gagal memperbarui data!</div>";
    }
  }
?>

<h3>Edit Kebiasaan</h3>

<form method="POST">

  <div class="mb-3">
    <label>Nama Kebiasaan</label>
    <input type="text" name="nama_kebiasaan" value="<?= htmlspecialchars($data['nama_kebiasaan']) ?>" class="form-control" required>
  </div>

  <div class="mb-3">
    <label>Deskripsi</label>
    <textarea name="deskripsi" class="form-control" required><?= htmlspecialchars($data['deskripsi_kebiasaan']) ?></textarea>
  </div>

  <div class="mb-3">
    <label>Waktu</label>
    <input type="time" name="waktu" value="<?= htmlspecialchars($data['waktu']) ?>" class="form-control" required>
  </div>

  <div class="mb-3">
    <label>Frequensi</label>
    <select name="frequensi" class="form-control" required>
      <option value="">-- Pilih --</option>
      <?php
      $days = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];
      foreach($days as $day) {
        $selected = ($data['frequensi'] == $day) ? 'selected' : '';
        echo "<option value='$day' $selected>" . ucfirst($day) . "</option>";
      }
      ?>
    </select>
  </div>

  <button type="submit" name="update" class="btn btn-warning">Update</button>
  <a href="?p=kebiasaan" class="btn btn-secondary">Kembali</a>

</form>

<?php


} elseif ($aksi == 'detail' && $id) {

  $data = mysqli_fetch_assoc(mysqli_query($konek, "SELECT * FROM kebiasaan WHERE id_kebiasaan='$id'"));
?>

<h3>Detail Kebiasaan</h3>

<table class="table table-bordered">
  <tr><th>Nama Kebiasaan</th><td><?= htmlspecialchars($data['nama_kebiasaan']) ?></td></tr>
  <tr><th>Deskripsi</th><td><?= htmlspecialchars($data['deskripsi_kebiasaan']) ?></td></tr>
  <tr><th>Waktu</th><td><?= htmlspecialchars($data['waktu']) ?></td></tr>
  <tr><th>Frequensi</th><td><?= htmlspecialchars($data['frequensi']) ?></td></tr>
</table>

<a href="?p=kebiasaan" class="btn btn-secondary">Kembali</a>

<?php


} else { 

  $keyword = $_POST['keyword'] ?? '';
  $kategori = $_POST['kategori'] ?? '';
  
  $where = "";
  if (isset($_POST['cari']) && !empty($keyword)) {
      if ($kategori == 1) {
          $where = " WHERE nama_kebiasaan LIKE '%$keyword%'";
      } elseif ($kategori == 2) {
          $where = " WHERE deskripsi_kebiasaan LIKE '%$keyword%'";
      } elseif ($kategori == 3) {
          $where = " WHERE frequensi LIKE '%$keyword%'";
      } elseif ($kategori == 4) {
          $where = " WHERE waktu LIKE '%$keyword%'";
      }
  }
?>

<h3>Data Kebiasaan</h3>

<a href="?p=kebiasaan&aksi=tambah" class="btn btn-success mb-3">+ Tambah Kebiasaan</a>

<table class="table table-bordered table-striped">
  <thead class="table-primary">
    <tr>
      <th>No</th>
      <th>Nama Kebiasaan</th>
      <th>Waktu</th>
      <th>Opsi</th>
    </tr>
  </thead>
  <tbody>

<?php
$no = 1;
$query = mysqli_query($konek, "SELECT * FROM kebiasaan $where ORDER BY waktu DESC");

if (mysqli_num_rows($query) > 0) {
  while ($row = mysqli_fetch_assoc($query)) {

    echo "<tr>";
    echo "<td>{$no}</td>";
    echo "<td>" . htmlspecialchars($row['nama_kebiasaan']) . "</td>";
    echo "<td>" . htmlspecialchars($row['deskripsi_kebiasaan']) . "</td>";
    echo "<td>" . htmlspecialchars($row['frequensi']) . "</td>";
    echo "<td>" . htmlspecialchars($row['waktu']) . "</td>";

    echo "<td>
            <a href='?p=kebiasaan&aksi=detail&id=" . urlencode($row['id_kebiasaan']) . "' class='btn btn-info btn-sm'>Detail</a>
            <a href='?p=kebiasaan&aksi=edit&id=" . urlencode($row['id_kebiasaan']) . "' class='btn btn-warning btn-sm'>Edit</a>
            <a href='?p=kebiasaan&aksi=hapus&id=" . urlencode($row['id_kebiasaan']) . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin mau hapus data ini?\")'>Hapus</a>
          </td>";

    echo "</tr>";

    $no++;
  }
} else {
  echo "<tr><td colspan='6' class='text-center'>Belum ada data kebiasaan</td></tr>";
}
?>

  </tbody>
</table>

<?php 
} 
?>
</div>

