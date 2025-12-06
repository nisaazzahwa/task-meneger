<?php
require_once "config.php";
$pesan ='';
$data= null;
//PENCARIAN
$keyword = $_POST['keyword'] ?? '';
$kategori = $_POST['kategori'] ?? '';

if (!empty($keyword)){
  $n=0;
  
}else{
  $n=0;
  if($kategori==1){
    $data=$konek->query("SELECT* FROM kebiasaan where nama_kebiasaan like '%$keyword%'");

  }elseif($kategori==2){
    $data=$konek->query("SELECT* FROM kebiasaan where deskripsi_kebiasaan like '%$keyword%'");

  }elseif($kategori==3){
    $data=$konek->query("SELECT* FROM kebiasaan where frequensi like '%$keyword%'");

  }elseif($kategori==4){
    $data=$konek->query("SELECT* FROM kebiasaan where Waktu like '%$keyword%'");
  }
}


//TAMBAH / EDIT
if (isset($_POST['simpan'])) {
  $id = intval($_POST['id_kebiasaan'] ?? 0);
  $Nama = trim($_POST['nama_kebiasaan'] ?? '');
  $Deskripsi = trim($_POST['deskripsi_kebiasaan'] ?? '');
  $Frequensi = trim($_POST['frequensi'] ?? '');
  $Waktu = trim($_POST['waktu'] ?? '');

  if (empty($Nama) || empty($Deskripsi) || empty($Frequensi) || empty($Waktu)) {
    $pesan = '<div class="alert alert-danger">Semua field wajib diisi!</div>';
  } else {
    if ($id > 0) {
      $stmt = $konek->prepare("UPDATE mhs SET Nama_Kebiasaan=?, Deskripsi=?, Frequensi=?, Waktu=?,  WHERE id_kebiasaan=?");
      $stmt->bind_param("sssssi", $Nama, $Deskripsi, $Frequensi, $Waktu, $id);
      $pesan = ($stmt->execute())
        ? '<div class="alert alert-success">Data berhasil diupdate!</div>'
        : '<div class="alert alert-danger">Gagal update data: ' . $konek->error . '</div>';
    } else {
      $stmt = $konek->prepare("INSERT INTO mhs (Nama_Kebiasaan, Deskripsi, Frequensi, waktu) VALUES (?, ?, ?, ?)");
      $stmt->bind_param("sssss", $Nama, $Deskripsi, $Frequensi, $Waktu);
      $pesan = ($stmt->execute())
        ? '<div class="alert alert-success">Data berhasil disimpan!</div>'
        : '<div class="alert alert-danger">Gagal simpan data: ' . $konek->error . '</div>';
    }
    $stmt->close();
  }
}



// HAPUS DATA 
if (isset($_GET['hapus'])) {
  $id = intval($_GET['hapus']);
  $hapus = $konek->prepare("DELETE FROM mhs WHERE id_kebiasaan=?");
  $hapus->bind_param("i", $id);
  if ($hapus->execute()) {
    $pesan = '<div class="alert alert-success">Data berhasil dihapus!</div>';
  } else {
    $pesan = '<div class="alert alert-danger">Gagal menghapus data!</div>';
  }
  $hapus->close();
}

//  AMBIL DATA 
$data = $konek->query("SELECT * FROM mhs ORDER BY waktu DESC");
?>

<main class="app-main">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h3 class="mb-0">Kebiasaan</h3>
        </div>
      </div>
    </div>
  <div class="app-content">
    <div class="container-fluid">

      <?= $pesan; ?>

      <!-- Tombol Tambah -->
      <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="fas fa-plus me-1"></i> Tambah Kebiasaan
      </button>
<!-- pencarian -->
      <table>
        <tr><td><a href="./page=add-mhs"class="btn btn-sucess bt-sm">cari</a>
      <td>
      <form method="post" action="#">
        <input type='text' name='keyword' placeholder="keyword" class='form-control' style="width:300px;display:inline;"value="<?=$keyword?>">
        <select name='kategori' class='form-control' style="width: 150px;display:inline;">
          <option value="1"<?php if ($kategori==1) echo "selected";?>>Kebiasaan</option>
          <option value="2"<?php if ($kategori==2) echo "selected";?>>Deskripsi</option>
          <option value="3"<?php if ($kategori==3) echo "selected";?>>Frequensi</option>
          <option value="4"<?php if ($kategori==4) echo "selected";?>>Waktu</option>
        </select>
        <input type="reset"name="reset" value="reset" class="btn btn-danger btn-sm">
        <input type="submit" name="cari" value="cari" class="btn btn-primary btn-sm">
      </form></td></tr></table>

      <!-- TABEL DATA -->
      <table class="table table-striped table-hover table-bordered align-middle text-center">
        <thead class="table-primary">
          <tr>
            <th>No</th>
            <th>Nama Kebiasaan</th>
            <th>Waktu</th> 
            <th>Opsi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1;
          while ($row = $data->fetch_assoc()) { ?>
            <tr>
              <td><?= $no++; ?></td>
              <td><?= htmlspecialchars($row['Nama Kebiasaan']); ?></td>
              <td><?= htmlspecialchars($row['Deskripsi']); ?></td>
              <td><?= htmlspecialchars($row['Frequensi']); ?></td>
              <td><?= htmlspecialchars($row['Waktu']); ?></td>
              <td>
                <button class="btn btn-info btn-sm text-white" data-bs-toggle="modal" data-bs-target="#modalDetail<?= $row['id_kebiasaan']; ?>">Detail</button>
                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id_kebiasaan']; ?>">Edit</button>
                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalHapus<?= $row['id_kebiasaan']; ?>">Hapus</button>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>

      <!-- MODAL TAMBAH -->
      <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <form method="post">
              <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Tambah Kebiasaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <div class="mb-3">
                  <label>Nama Kebiasaan</label>
                  <input type="text" class="form-control" name="Nama Kebiasaaan" required>
                </div>
                <div class="mb-3">
                  <label>Deskripsi</label>
                  <input type="text" class="form-control" name="Deskripsi" required>
                </div>
                <div class="mb-3">
                  <label>Waktu</label>
                  <input type="time" class="form-control" name="Waktu" required>
                </div>
                <div class="mb-3">
                  <label>Frequensi</label>
                  <select class="form-control" name="Frequensi" required>
                    <option value="">-- Pilih --</option>
                    <option value="Senin">Senin</option>
                    <option value="Selasa">Selasa</option>
                    <option value="Rabu">Rabu</option>
                    <option value="Kamis">Kamis</option>
                    <option value="Jumat">Jumat</option>
                    <option value="Sabtu">Sabtu</option>
                    <option value="Minggu">Minggu</option>
                  </select>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- MODAL DETAIL, EDIT, HAPUS DIPISAH DARI LOOP -->
      <?php
      $data->data_seek(0);
      while ($row = $data->fetch_assoc()) {
      ?>
        <!-- Modal Detail -->
        <div class="modal fade" id="modalDetail<?= $row['id_kebiasaan ']; ?>" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Detail Kebiasaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <table class="table table-bordered">
                  
                  <tr><th>Nama Kebiasaaan</th><td><?= htmlspecialchars($row['Nama_kebiasaaan']); ?></td></tr>
                  <tr><th>Deskripsi</th><td><?= htmlspecialchars($row['Deskripsi']); ?></td></tr>
                  <tr><th>Frequensi</th><td><?= htmlspecialchars($row['Frequensi']); ?></td></tr>
                  <tr><th>Waktu </th><td><?= htmlspecialchars($row['waktu']); ?></td></tr>
                </table>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal Edit -->
        <div class="modal fade" id="modalEdit<?= $row['id_kebiasaan']; ?>" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              <form method="post">
                <div class="modal-header bg-warning text-dark">
                  <h5 class="modal-title">Edit Kebiasaaan</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <input type="hidden" name="id_kebiasaan" value="<?= $row['id_kebiasaan']; ?>">
                  <div class="mb-3">
                    <label>Nama Kebiasaaan</label>
                    <input type="text" class="form-control" name="Nama_Kebiasaan" value="<?= $row['Nama_Kebiasaan']; ?>" required>
                  </div>
                  <div class="mb-3">
                    <label>Deskripsi</label>
                    <input type="text" class="form-control" name="Deskripsi" value="<?= $row['Deskripsi']; ?>" required>
                  </div>
                  <div class="mb-3">
                    <label>Waktu</label>
                    <input type="time" class="form-control" name="Waktu" value="<?= $row['Waktu']; ?>" required>
                  </div>
                  <div class="mb-3">
                    <label>Frequensi</label>
                    <select class="form-control" name="Frequensi" required>
                      <option value="">-- Pilih --</option>
                      <option value="Senin" <?= $row['Frequensi'] == 'Senin' ? 'selected' : ''; ?>>Senin</option>
                      <option value="Selasa" <?= $row['Frequensi'] == 'Selasa' ? 'selected' : ''; ?>>Selasa</option>
                      <option value="Rabu" <?= $row['Frequensi'] == 'Rabu' ? 'selected' : ''; ?>>Rabu</option>
                      <option value="Kamis" <?= $row['Frequensi'] == 'Kamis' ? 'selected' : ''; ?>>Kamis</option>
                      <option value="Jumat" <?= $row['Frequensi'] == 'Jumat' ? 'selected' : ''; ?>>Jumat</option>
                      <option value="Sabtu" <?= $row['Frequensi'] == 'Sabtu' ? 'selected' : ''; ?>>Sabtu</option>
                      <option value="Minggu" <?= $row['Frequensi'] == 'Minggu' ? 'selected' : ''; ?>>Minggu</option>
                    </select>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                  <button type="submit" name="simpan" class="btn btn-primary">Update</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Modal Hapus -->
        <div class="modal fade" id="modalHapus<?= $row['id_kebiasaan']; ?>" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Hapus Kebiasaaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                Yakin ingin menghapus <b><?= htmlspecialchars($row['Nama_Kebiasaan']); ?></b> (Nama_Kebiasaan: <?= htmlspecialchars($row['Nama_Kebiasaan']); ?>)?
              </div>
              <div class="modal-footer">
                <a href="index.php?p=mahasiswa&hapus=<?= $row['id_kebiasaan']; ?>" class="btn btn-danger">Hapus</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              </div>
            </div>
          </div>
        </div>
      <?php } ?>

    </div>
  </div>
</main>

<style>
  table td, table th {
    vertical-align: middle !important;
  }
  .btn {
    margin: 2px;
  }
</style>
