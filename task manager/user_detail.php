<?php
// SECURITY: Admin Only
if ($role != 'admin') {
    echo "<div class='alert alert-danger'>Access Denied.</div>";
    exit();
}

$uid = $_GET['uid'] ?? 0;
$hid = $_GET['hid'] ?? '';
$tid = $_GET['tid'] ?? '';

// 1. FETCH USER INFO
$user = mysqli_fetch_assoc($konek->query("SELECT * FROM users WHERE id='$uid'"));

// 2. FETCH HABIT INFO (If exists)
$habit = null;
$habit_history_count = 0;
if (!empty($hid)) {
    $habit = mysqli_fetch_assoc($konek->query("SELECT * FROM kebiasaan WHERE id_kebiasaan='$hid'"));
    $h_count_sql = $konek->query("SELECT COUNT(*) as total FROM riwayat_kebiasaan WHERE id_kebiasaan='$hid'");
    $habit_history_count = $h_count_sql->fetch_assoc()['total'];
}

// 3. FETCH TASK INFO (If exists)
$task = null;
if (!empty($tid)) {
    $task = mysqli_fetch_assoc($konek->query("SELECT * FROM tugas WHERE id_tugas='$tid'"));
}
?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            
            <a href="?p=users_management" class="btn btn-secondary mt-3 mb-3"><i class="bi bi-arrow-left"></i> Kembali</a>

            <div class="row">
                
                <!-- CARD 1: USER INFO -->
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="bi bi-person-circle"></i> User Details</h3>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <tr><th width="200">Username</th> <td><?= htmlspecialchars($user['username']) ?></td></tr>
                                <tr><th>Email</th> <td><?= htmlspecialchars($user['email']) ?></td></tr>
                                <tr><th>Role</th> <td><span class="badge text-bg-info"><?= $user['role'] ?></span></td></tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- CARD 2: HABIT DETAILS (Only if this row has a habit) -->
                <?php if ($habit): ?>
                <div class="col-md-6 mt-3">
                    <div class="card card-success card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="bi bi-calendar-check"></i> Kebiasaan Data</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr><th>Name</th> <td><?= htmlspecialchars($habit['nama_kebiasaan']) ?></td></tr>
                                <tr><th>Deskripsi</th> <td><?= htmlspecialchars($habit['deskripsi_kebiasaan']) ?></td></tr>
                                <tr><th>Frequensi</th> <td><?= ucwords(str_replace('_', ' ', $habit['frequensi'])) ?></td></tr>
                                <tr><th>Reminder Waktu</th> <td><?= htmlspecialchars($habit['waktu']) ?></td></tr>
                                <tr><th>Status</th> <td><?= $habit['status'] ?></td></tr>
                                <tr><th>Total Check-ins</th> <td><strong><?= $habit_history_count ?> Waktu</strong></td></tr>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- CARD 3: TASK DETAILS (Only if this row has a task) -->
                <?php if ($task): ?>
                <div class="col-md-6 mt-3">
                    <div class="card card-warning card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="bi bi-list-task"></i> Tugas Data</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr><th>Name</th> <td><?= htmlspecialchars($task['nama_tugas']) ?></td></tr>
                                <tr><th>Deskripsi</th> <td><?= htmlspecialchars($task['deskripsi']) ?></td></tr>
                                <tr><th>Deadline</th> <td><?= date('d M Y H:i', strtotime($task['deadline'])) ?></td></tr>
                                <tr><th>prioritas</th> <td><?= htmlspecialchars($task['prioritas']) ?></td></tr>
                                <tr><th>Time Estimasi</th> <td><?= htmlspecialchars($task['ekstimasi_waktu']) ?></td></tr>
                                <tr><th>Status</th> <td>
                                    <span class="badge <?= $task['status'] == 'selesai' ? 'text-bg-success' : 'text-bg-warning' ?>">
                                        <?= $task['status'] ?>
                                    </span>
                                </td></tr>
                                <?php if($task['tanggal_selesai']): ?>
                                <tr><th>Tanggal Selesai</th> <td><?= date('d M Y', strtotime($task['tanggal_selesai'])) ?></td></tr>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </section>
</div>