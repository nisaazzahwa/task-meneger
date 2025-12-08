<?php
// SECURITY: Only Admin
if ($role != 'admin') {
    echo "<div class='alert alert-danger text-center mt-5'>ACCESS DENIED.</div>";
    exit();
}

// HANDLE ADD NEW USER
if (isset($_POST['add_user'])) {
    $new_user = $_POST['username'];
    $new_email = $_POST['email'];
    $new_pass = md5($_POST['password']);
    $new_role = $_POST['role']; // 'user' or 'admin'

    // Check duplicate
    $check = $konek->query("SELECT id FROM users WHERE username='$new_user' OR email='$new_email'");
    if ($check->num_rows > 0) {
        echo "<script>alert('Username or Email already exists!');</script>";
    } else {
        $stmt = $konek->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $new_user, $new_email, $new_pass, $new_role);
        if ($stmt->execute()) {
            echo "<script>alert('New User Added Successfully!'); window.location='?p=users_management';</script>";
        } else {
            echo "<script>alert('Error adding user.');</script>";
        }
    }
}

// FETCH ALL USERS
$users_query = $konek->query("SELECT * FROM users ORDER BY id ASC");
?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            
            <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                <h3>Users Management</h3>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="bi bi-person-plus-fill"></i> tambah user baru
                </button>
            </div>

            <!-- TABLE 1: USER DIRECTORY -->
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h3 class="card-title">Users Data</h3>
                </div>
                <div class="card-body">
                    <table id="table1" class="table table-bordered table-striped datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            while($u = $users_query->fetch_assoc()) {
                                echo "<tr>
                                    <td>{$no}</td>
                                    <td>".htmlspecialchars($u['username'])."</td>
                                    <td>".htmlspecialchars($u['email'])."</td>
                                    <td>
                                        <span class='badge ".($u['role']=='admin'?'text-bg-danger':'text-bg-primary')."'>
                                            {$u['role']}
                                        </span>
                                    </td>
                                </tr>";
                                $no++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TABLE 2: ACTIVITY MATRIX -->
            <div class="card mt-4">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">User Activitas Matrix</h3>
                </div>
                <div class="card-body">
                    <table id="table2" class="table table-bordered table-striped datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Username</th>
                                <th>Kebiasaan</th>
                                <th>Tugas</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $users_list = $konek->query("SELECT * FROM users");
                            $row_no = 1;

                            while ($user = $users_list->fetch_assoc()) {
                                $uid = $user['id'];
                                $uname = htmlspecialchars($user['username']);

                                $habits = [];
                                $h_sql = $konek->query("SELECT * FROM kebiasaan WHERE user_id='$uid'");
                                while($h = $h_sql->fetch_assoc()) { $habits[] = $h; }

                                $tasks = [];
                                $t_sql = $konek->query("SELECT * FROM tugas WHERE user_id='$uid'");
                                while($t = $t_sql->fetch_assoc()) { $tasks[] = $t; }

                                $max_rows = max(count($habits), count($tasks));
                                if ($max_rows == 0) $max_rows = 1; 

                                for ($i = 0; $i < $max_rows; $i++) {
                                    $h_name = isset($habits[$i]) ? htmlspecialchars($habits[$i]['nama_kebiasaan']) : '';
                                    $h_id   = isset($habits[$i]) ? $habits[$i]['id_kebiasaan'] : '';
                                    $t_name = isset($tasks[$i]) ? htmlspecialchars($tasks[$i]['nama_tugas']) : '';
                                    $t_id   = isset($tasks[$i]) ? $tasks[$i]['id_tugas'] : '';

                                    echo "<tr>
                                        <td>{$row_no}</td>
                                        <td>{$uname}</td>
                                        <td>{$h_name}</td>
                                        <td>{$t_name}</td>
                                        <td>
                                            <a href='?p=user_detail&uid={$uid}&hid={$h_id}&tid={$t_id}' class='btn btn-info btn-sm text-white'>
                                                <i class='bi bi-eye'></i> Detail
                                            </a>
                                        </td>
                                    </tr>";
                                    $row_no++;
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>
</div>

<!-- MODAL: ADD NEW USER -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title">tambah User / Admin baru</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Role</label>
                <select name="role" class="form-select">
                    <option value="user">User (Normal)</option>
                    <option value="admin">Admin (Super User)</option>
                </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" name="add_user" class="btn btn-primary">Save User</button>
          </div>
      </form>
    </div>
  </div>
</div>