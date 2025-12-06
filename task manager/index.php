<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// 1. Redirect to task manager if already logged in
if (isset($_SESSION['user'])) {
    header("Location: task manager/index.php");
    exit();
}

require_once 'task manager/config.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($password)) {
        $username = $konek->real_escape_string($username);
        $password_hash = md5($password);

        // 2. Modified Query to get ID as well
        $sql = "SELECT id, username FROM users WHERE (username='$username' OR email='$username') AND password='$password_hash'";
        $result = $konek->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            
            // 3. CRITICAL FIX: Save User ID to session
            $_SESSION['user'] = $row['username'];
            $_SESSION['user_id'] = $row['id']; 

            header("Location: task manager/index.php");
            exit();
        } else {
            $message = 'Username or password is incorrect!';
            $message_type = 'danger';
        }
    } else {
        $message = 'Please enter username and password!';
        $message_type = 'danger';
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Login | Task Manager</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="task manager/asset/css/adminlte.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
  </head>
  <body class="login-page bg-body-secondary">
    <div class="login-box">
      <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <h1 class="mb-0"><b>Task</b>Manager</h1>
        </div>
        <div class="card-body login-card-body">
          <p class="login-box-msg">Sign in to start your session</p>

          <?php if ($message): ?>
            <div class="alert alert-<?php echo htmlspecialchars($message_type); ?>" role="alert">
              <?php echo htmlspecialchars($message); ?>
            </div>
          <?php endif; ?>

          <form action="index.php" method="post">
            <div class="input-group mb-3">
              <div class="form-floating">
                <input id="loginUsername" type="text" name="username" class="form-control" placeholder="" required />
                <label for="loginUsername">Username or Email</label>
              </div>
              <div class="input-group-text"><span class="bi bi-person"></span></div>
            </div>
            <div class="input-group mb-3">
              <div class="form-floating">
                <input id="loginPassword" type="password" name="password" class="form-control" placeholder="" required />
                <label for="loginPassword">Password</label>
              </div>
              <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
            </div>
            <div class="row">
              <div class="col-8 d-flex align-items-center">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="flexCheckDefault" />
                  <label class="form-check-label" for="flexCheckDefault"> Remember Me </label>
                </div>
              </div>
              <div class="col-4">
                <div class="d-grid gap-2">
                  <button type="submit" name="login" class="btn btn-primary">Sign In</button>
                </div>
              </div>
            </div>
          </form>
          <p class="mb-1 mt-2">
            <a href="change_password.php">I forgot my password</a>
          </p>
          <p class="mb-0">
            <a href="register.php" class="text-center">Register a new user</a>
          </p>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script src="task manager/asset/js/adminlte.js"></script>
  </body>
</html>
