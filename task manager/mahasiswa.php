<?php
require_once "config.php";
$pesan ='';
//PENCARIAN
$keyword = $_POST['keyword'] ?? '';
$kategori = $_POST['kategori'] ?? '';

if (!empty($keyword)){
  $n=0;
  
}else{
  $n=0;
  if($kategori==1){
    $data=$konek->query("SELECT* FROM mhs where Nim like '%$keyword%'");

  }elseif($kategori==2){
    $data=$konek->query("SELECT* FROM mhs where Nama like '%$keyword%'");

  }elseif($kategori==3){
    $data=$konek->query("SELECT* FROM mhs where prodi like '%$keyword%'");
  }elseif($kategori==4){

    if($keyword=="informatika"){
      $keyword2=1;
    }elseif($keyword=="arab"){
      $keyword2=2;
    }elseif($keyword=="persikologi"){
      $keyword2=3;
    }
    $data=$konek->query("SELECT* FROM mhs where Alamat like '%$keyword%'");
  }
}


//TAMBAH / EDIT
if (isset($_POST['simpan'])) {
  $id = intval($_POST['id_user'] ?? 0);
  $Nim = trim($_POST['Nim'] ?? '');
  $Nama = trim($_POST['Nama'] ?? '');
  $Prodi = trim($_POST['Prodi'] ?? '');
  $Alamat = trim($_POST['Alamat'] ?? '');
  $Gender = trim($_POST['Gender'] ?? '');

  if (empty($Nim) || empty($Nama) || empty($Prodi) || empty($Alamat) || empty($Gender)) {
    $pesan = '<div class="alert alert-danger">Semua field wajib diisi!</div>';
  } else {
    if ($id > 0) {
      $stmt = $konek->prepare("UPDATE mhs SET Nim=?, Nama=?, Alamat=?, prodi=?, gender=? WHERE id_user=?");
      $stmt->bind_param("sssssi", $Nim, $Nama, $Alamat, $Prodi, $Gender, $id);
      $pesan = ($stmt->execute())
        ? '<div class="alert alert-success">Data berhasil diupdate!</div>'
        : '<div class="alert alert-danger">Gagal update data: ' . $konek->error . '</div>';
    } else {
      $stmt = $konek->prepare("INSERT INTO mhs (Nim, Nama, Alamat, prodi, gender, waktu) VALUES (?, ?, ?, ?, ?, NOW())");
      $stmt->bind_param("sssss", $Nim, $Nama, $Alamat, $Prodi, $Gender);
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
  $hapus = $konek->prepare("DELETE FROM mhs WHERE id_user=?");
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
          <h3 class="mb-0">Data Mahasiswa</h3>
        </div>
      </div>
    </div>
  <div class="app-content">
    <div class="container-fluid">

      <?= $pesan; ?>

      <!-- Tombol Tambah -->
      <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="fas fa-plus me-1"></i> Tambah Mahasiswa
      </button>
<!-- pencarian -->
      <table>
        <tr><td><a href="./page=add-mhs"class="btn btn-sucess bt-sm">cari</a>
      <td>
      <form method="post" action="#">
        <input type='text' name='keyword' placeholder="keyword" class='form-control' style="width:300px;display:inline;"value="<?=$keyword?>">
        <select name='kategori' class='form-control' style="width: 150px;display:inline;">
          <option value="1"<?php if ($kategori==1) echo "selected";?>>NIM</option>
          <option value="2"<?php if ($kategori==2) echo "selected";?>>Nama</option>
          <option value="3"<?php if ($kategori==3) echo "selected";?>>prodi</option>
          <option value="4"<?php if ($kategori==4) echo "selected";?>>Alamat</option>
        </select>
        <input type="reset"name="reset" value="reset" class="btn btn-danger btn-sm">
        <input type="submit" name="cari" value="cari" class="btn btn-primary btn-sm">
      </form></td></tr></table>

      <!-- TABEL DATA -->
      <table class="table table-striped table-hover table-bordered align-middle text-center">
        <thead class="table-primary">
          <tr>
            <th>No</th>
            <th>NIM</th>
            <th>Nama</th>
            <th>Alamat</th>
            <th>Prodi</th>
            
            <th>Opsi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1;
          while ($row = $data->fetch_assoc()) { ?>
            <tr>
              <td><?= $no++; ?></td>
              <td><?= htmlspecialchars($row['Nim']); ?></td>
              <td><?= htmlspecialchars($row['Nama']); ?></td>
              <td><?= htmlspecialchars($row['Alamat']); ?></td>
              <td><?= htmlspecialchars($row['prodi']); ?></td>
              <td>
                <button class="btn btn-info btn-sm text-white" data-bs-toggle="modal" data-bs-target="#modalDetail<?= $row['id_user']; ?>">Detail</button>
                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id_user']; ?>">Edit</button>
                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalHapus<?= $row['id_user']; ?>">Hapus</button>
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
                <h5 class="modal-title">Tambah Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <div class="mb-3">
                  <label>NIM</label>
                  <input type="text" class="form-control" name="Nim" required>
                </div>
                <div class="mb-3">
                  <label>Nama</label>
                  <input type="text" class="form-control" name="Nama" required>
                </div>
                <div class="mb-3">
                  <label>Alamat</label>
                  <input type="text" class="form-control" name="Alamat" required>
                </div>
                <div class="mb-3">
                  <label>Prodi</label>
                  <input type="text" class="form-control" name="Prodi" required>
                </div>
                <div class="mb-3">
                  <label>Gender</label>
                  <select class="form-control" name="Gender" required>
                    <option value="">-- Pilih --</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
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
        <div class="modal fade" id="modalDetail<?= $row['id_user']; ?>" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Detail Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <table class="table table-bordered">
                  <tr><th>NIM</th><td><?= htmlspecialchars($row['Nim']); ?></td></tr>
                  <tr><th>Nama</th><td><?= htmlspecialchars($row['Nama']); ?></td></tr>
                  <tr><th>Alamat</th><td><?= htmlspecialchars($row['Alamat']); ?></td></tr>
                  <tr><th>Program Studi</th><td><?= htmlspecialchars($row['prodi']); ?></td></tr>
                  <tr><th>Gender</th><td><?= $row['gender'] == 'L' ? 'Laki-laki' : 'Perempuan'; ?></td></tr>
                  <tr><th>Waktu Input</th><td><?= htmlspecialchars($row['waktu']); ?></td></tr>
                </table>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal Edit -->
        <div class="modal fade" id="modalEdit<?= $row['id_user']; ?>" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              <form method="post">
                <div class="modal-header bg-warning text-dark">
                  <h5 class="modal-title">Edit Mahasiswa</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <input type="hidden" name="id_user" value="<?= $row['id_user']; ?>">
                  <div class="mb-3">
                    <label>NIM</label>
                    <input type="text" class="form-control" name="Nim" value="<?= $row['Nim']; ?>" required>
                  </div>
                  <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" class="form-control" name="Nama" value="<?= $row['Nama']; ?>" required>
                  </div>
                  <div class="mb-3">
                    <label>Alamat</label>
                    <input type="text" class="form-control" name="Alamat" value="<?= $row['Alamat']; ?>" required>
                  </div>
                  <div class="mb-3">
                    <label>Prodi</label>
                    <input type="text" class="form-control" name="Prodi" value="<?= $row['prodi']; ?>" required>
                  </div>
                  <div class="mb-3">
                    <label>Gender</label>
                    <select class="form-control" name="Gender" required>
                      <option value="L" <?= $row['gender'] == 'L' ? 'selected' : ''; ?>>Laki-laki</option>
                      <option value="P" <?= $row['gender'] == 'P' ? 'selected' : ''; ?>>Perempuan</option>
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
        <div class="modal fade" id="modalHapus<?= $row['id_user']; ?>" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Hapus Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                Yakin ingin menghapus <b><?= htmlspecialchars($row['Nama']); ?></b> (NIM: <?= htmlspecialchars($row['Nim']); ?>)?
              </div>
              <div class="modal-footer">
                <a href="index.php?p=mahasiswa&hapus=<?= $row['id_user']; ?>" class="btn btn-danger">Hapus</a>
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
