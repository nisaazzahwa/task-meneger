<?php
$aksi = $_GET['aksi'] ?? '';
$id_action = $_GET['id'] ?? '';

// --- TASK ACTIONS ---
if ($aksi == 'selesai_tugas' && $id_action) {
    $today = date('Y-m-d');
    $konek->query("UPDATE tugas SET status='selesai', tanggal_selesai='$today' WHERE id_tugas='$id_action' AND user_id='$user_id'");
    echo "<script>window.location='?p=dashboard';</script>";
}
if ($aksi == 'hapus_tugas' && $id_action) {
    $konek->query("DELETE FROM tugas WHERE id_tugas='$id_action' AND user_id='$user_id'");
    echo "<script>window.location='?p=dashboard';</script>";
}

// --- HABIT ACTIONS ---
if ($aksi == 'checkin_habit' && $id_action) {
    $today = date('Y-m-d');
    $check = $konek->query("SELECT id FROM riwayat_kebiasaan WHERE id_kebiasaan='$id_action' AND tanggal='$today' AND user_id='$user_id'");
    if ($check->num_rows == 0) {
        $konek->query("INSERT INTO riwayat_kebiasaan (user_id, id_kebiasaan, tanggal) VALUES ('$user_id', '$id_action', '$today')");
    }
    // Optional: Hide after check-in
    $konek->query("UPDATE kebiasaan SET status='selesai' WHERE id_kebiasaan='$id_action' AND user_id='$user_id'");
    echo "<script>window.location='?p=dashboard';</script>";
}
if ($aksi == 'hapus_habit' && $id_action) {
    $konek->query("DELETE FROM kebiasaan WHERE id_kebiasaan='$id_action' AND user_id='$user_id'");
    echo "<script>window.location='?p=dashboard';</script>";
}


// CHART 1: TASKS
$query_chart_tugas = $konek->query("
    SELECT tanggal_selesai, SUM(TIME_TO_SEC(ekstimasi_waktu)/3600) as total_jam
    FROM tugas 
    WHERE user_id = '$user_id' AND status = 'selesai' AND tanggal_selesai IS NOT NULL
    GROUP BY tanggal_selesai ORDER BY tanggal_selesai ASC LIMIT 7
");
$tugas_dates = []; $tugas_data = [];
while($row = $query_chart_tugas->fetch_assoc()) {
    $tugas_dates[] = date('d M', strtotime($row['tanggal_selesai']));
    $tugas_data[] = round($row['total_jam'], 1);
}

// CHART 2: HABITS
$query_chart_habit = $konek->query("
    SELECT tanggal, COUNT(*) as total_done
    FROM riwayat_kebiasaan 
    WHERE user_id = '$user_id' GROUP BY tanggal ORDER BY tanggal ASC LIMIT 7
");
$habit_dates = []; $habit_data = [];
while($row = $query_chart_habit->fetch_assoc()) {
    $habit_dates[] = date('d M', strtotime($row['tanggal']));
    $habit_data[] = $row['total_done'];
}

// TABLES (Pending Items)
$tugas = $konek->query("SELECT * FROM tugas WHERE user_id='$user_id' AND status='belum' ORDER BY deadline ASC");
$kebiasaan = $konek->query("SELECT * FROM kebiasaan WHERE user_id='$user_id' AND status='belum' ORDER BY id_kebiasaan DESC");
?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">

            <!-- 1. LINE CHARTS ROW -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Task Progress (Hours)</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="taskChart" style="height: 250px;"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-success">Habit Consistency</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="habitChart" style="height: 250px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. TABLES ROW -->
            <div class="row">
                <!-- TUGAS TABLE -->
                <div class="col-md-12">
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
                                        <th>Estimasi</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $no = 1; if ($tugas->num_rows > 0) { while ($row = $tugas->fetch_assoc()) { 
                                    echo "<tr>
                                        <td>{$no}</td>
                                        <td>".htmlspecialchars($row['nama_tugas'])."</td>
                                        <td>".date('d M H:i', strtotime($row['deadline']))."</td>
                                        <td>".htmlspecialchars($row['prioritas'])."</td>
                                        <td>".htmlspecialchars($row['ekstimasi_waktu'])."</td>
                                        <td>
                                            <a href='?p=dashboard&aksi=selesai_tugas&id={$row['id_tugas']}' class='btn btn-success btn-sm' onclick=\"return confirm('Selesai?')\">Selesai</a>
                                            <a href='?p=dashboard&aksi=hapus_tugas&id={$row['id_tugas']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Hapus?')\">Hapus</a>
                                        </td>
                                    </tr>"; $no++;
                                }} else { echo "<tr><td colspan='6' class='text-center'>Tidak ada tugas pending.</td></tr>"; } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- KEBIASAAN TABLE -->
                <div class="col-md-12 mt-3">
                    <div class="card">
                       <div class="card-header bg-success text-white">
                            <h3 class="card-title">Daftar Kebiasaan</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Waktu</th>
                                        <th>Deskripsi</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $no = 1; if ($kebiasaan->num_rows > 0) { while ($row = $kebiasaan->fetch_assoc()) { 
                                    echo "<tr>
                                        <td>{$no}</td>
                                        <td>".htmlspecialchars($row['nama_kebiasaan'])."</td>
                                        <td>".htmlspecialchars($row['waktu'])."</td>
                                        <td>".htmlspecialchars($row['deskripsi_kebiasaan'])."</td>
                                        <td>
                                            <a href='?p=dashboard&aksi=checkin_habit&id={$row['id_kebiasaan']}' class='btn btn-info btn-sm' style='color:white;'>Check-in</a>
                                            <a href='?p=dashboard&aksi=hapus_habit&id={$row['id_kebiasaan']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Hapus?')\">Hapus</a>
                                        </td>
                                    </tr>"; $no++;
                                }} else { echo "<tr><td colspan='5' class='text-center'>Belum ada kebiasaan.</td></tr>"; } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<!-- SCRIPTS FOR CHARTS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctxTask = document.getElementById('taskChart').getContext('2d');
    new Chart(ctxTask, { type: 'line', data: { labels: <?= json_encode($tugas_dates) ?>, datasets: [{ label: 'Hours', data: <?= json_encode($tugas_data) ?>, borderColor: '#4e73df', backgroundColor: 'rgba(78, 115, 223, 0.1)', tension: 0.3, fill: true }] }, options: { maintainAspectRatio: false, scales: { y: { beginAtZero: true } } } });

    const ctxHabit = document.getElementById('habitChart').getContext('2d');
    new Chart(ctxHabit, { type: 'line', data: { labels: <?= json_encode($habit_dates) ?>, datasets: [{ label: 'Habits', data: <?= json_encode($habit_data) ?>, borderColor: '#1cc88a', backgroundColor: 'rgba(28, 200, 138, 0.1)', tension: 0.3, fill: true }] }, options: { maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } } });
</script>
