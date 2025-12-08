<?php
// Note: $user_id is available globally from index.php

$aksi = $_GET['aksi'] ?? ''; 
$id   = $_GET['id'] ?? '';

if ($aksi == 'hapus' && $id) {
  $konek->query("DELETE FROM kebiasaan WHERE id_kebiasaan='$id' AND user_id='$user_id'");
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

    $query = "INSERT INTO kebiasaan (user_id, nama_kebiasaan, deskripsi_kebiasaan, waktu, frequensi)
              VALUES ('$user_id', '$nama_kebiasaan', '$deskripsi', '$waktu', '$frequensi')";

    if ($konek->query($query)) {
      echo "<script>alert('Data kebiasaan berhasil ditambahkan!'); window.location='?p=kebiasaan';</script>";
    } else {
      echo "<div class='alert alert-danger'>Gagal menambah data! " . $konek->error . "</div>";
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
      <option value="setiap hari">Setiap Hari</option>
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

  $data = mysqli_fetch_assoc($konek->query("SELECT * FROM kebiasaan WHERE id_kebiasaan='$id' AND user_id='$user_id'"));

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
               WHERE id_kebiasaan='$id' AND user_id='$user_id'";

    if ($konek->query($update)) {
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
      $days = ['setiap hari', 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];
      foreach($days as $day) {
        $selected = ($data['frequensi'] == $day) ? 'selected' : '';
        $label = ucwords(str_replace('_', ' ', $day));
        echo "<option value='$day' $selected>$label</option>";
      }
      ?>
    </select>
  </div>
  <button type="submit" name="update" class="btn btn-warning">Update</button>
  <a href="?p=kebiasaan" class="btn btn-secondary">Kembali</a>
</form>

<?php


} elseif ($aksi == 'detail' && $id) {

  $data = mysqli_fetch_assoc($konek->query("SELECT * FROM kebiasaan WHERE id_kebiasaan='$id' AND user_id='$user_id'"));
?>

<h3>Detail Kebiasaan</h3>
<table class="table table-bordered">
  <tr><th>Nama Kebiasaan</th><td><?= htmlspecialchars($data['nama_kebiasaan']) ?></td></tr>
  <tr><th>Deskripsi</th><td><?= htmlspecialchars($data['deskripsi_kebiasaan']) ?></td></tr>
  <tr><th>Waktu</th><td><?= htmlspecialchars($data['waktu']) ?></td></tr>
  <tr>
      <th>Frequensi</th>
      <td><?= ucwords(htmlspecialchars($data['frequensi'])) ?></td>
  </tr>
</table>
<a href="?p=kebiasaan" class="btn btn-secondary">Kembali</a>

<?php


} else { ?>

<h3>Data Kebiasaan</h3>
<a href="?p=kebiasaan&aksi=tambah" class="btn btn-success mb-3">+ Tambah Kebiasaan</a>

<table class="table table-bordered table-striped datatable">
  <thead class="table-primary">
    <tr>
      <th>No</th>
      <th>Nama Kebiasaan</th>
      <th>Deskripsi</th>
      <th>Frequensi</th>
      <th>Waktu</th>
      <th>Opsi</th>
    </tr>
  </thead>
  <tbody>

<?php
$no = 1;
$query = $konek->query("SELECT * FROM kebiasaan WHERE user_id='$user_id' ORDER BY id_kebiasaan DESC");

if ($query->num_rows > 0) {
  while ($row = $query->fetch_assoc()) {
    echo "<tr>";
    echo "<td>{$no}</td>";
    echo "<td>" . htmlspecialchars($row['nama_kebiasaan']) . "</td>";
    echo "<td>" . htmlspecialchars($row['deskripsi_kebiasaan']) . "</td>";
    echo "<td>" . ucwords(htmlspecialchars($row['frequensi'])) . "</td>";
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

<?php } ?>
</div>
