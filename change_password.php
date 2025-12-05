
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once 'index/db_connect.php';

$message = '';
$message_type = '';
$step = 1; // Default state: Step 1 (Verify User)

// Check if user is already verified in this session to stay on Step 2
if (isset($_SESSION['reset_user_id'])) {
    $step = 2;
}

// --- LOGIC FOR STEP 1: VERIFY USER ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_user'])) {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';

    if (!empty($username) && !empty($email)) {
        $username = $conn->real_escape_string($username);
        $email = $conn->real_escape_string($email);

        // Check if user exists with matching username AND email
        $sql = "SELECT id FROM users WHERE username='$username' AND email='$email'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // User found, save ID in session and move to Step 2
            $_SESSION['reset_user_id'] = $row['id'];
            $step = 2;
            $message = 'User verified. Please enter your new password.';
            $message_type = 'success';
        } else {
            $message = 'No account found matching that Username and Email.';
            $message_type = 'danger';
        }
    } else {
        $message = 'Please enter both Username and Email.';
        $message_type = 'danger';
    }
}

// --- LOGIC FOR STEP 2: RESET PASSWORD ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_password'])) {
    $new_pass = $_POST['new_password'] ?? '';
    $confirm_pass = $_POST['confirm_password'] ?? '';

    if (!empty($new_pass) && !empty($confirm_pass)) {
        if ($new_pass === $confirm_pass) {
            if (isset($_SESSION['reset_user_id'])) {
                $user_id = $_SESSION['reset_user_id'];
                $password_hash = md5($new_pass);

                // Update Password
                $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->bind_param('si', $password_hash, $user_id);
                
                if ($stmt->execute()) {
                    // Success: Clear session and redirect to login
                    unset($_SESSION['reset_user_id']);
                    echo "<script>alert('Password updated successfully! Please login.'); window.location.href='index.php';</script>";
                    exit();
                } else {
                    $message = 'Error updating password: ' . $conn->error;
                    $message_type = 'danger';
                }
                $stmt->close();
            } else {
                // Session expired or invalid access
                $step = 1;
                $message = 'Session expired. Please verify your details again.';
                $message_type = 'danger';
            }
        } else {
            $message = 'Passwords do not match!';
            $message_type = 'danger';
            $step = 2; // Keep them on step 2
        }
    } else {
        $message = 'Please fill in all fields.';
        $message_type = 'danger';
        $step = 2;
    }
}
?>
<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>AdminLTE 4 | Forgot Password</title>
    <!--begin::Accessibility Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
    <!--end::Accessibility Meta Tags-->
    <!--begin::Primary Meta Tags-->
    <meta name="title" content="AdminLTE 4 | Forgot Password" />
    <meta name="author" content="ColorlibHQ" />
    <!--end::Primary Meta Tags-->
    <!--begin::Fonts-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
      media="print"
      onload="this.media='all'"
    />
    <!--end::Fonts-->
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="assets/css/adminlte.css" />
    <!--end::Required Plugin(AdminLTE)-->
  </head>
  <!--begin::Body-->
  <body class="login-page bg-body-secondary">
    <div class="login-box">
      <div class="card card-outline card-primary">
        <div class="card-header">
          <a
            href="index.php"
            class="link-dark text-center link-offset-2 link-opacity-100 link-opacity-50-hover"
          >
            <h1 class="mb-0"><b>Admin</b>LTE</h1>
          </a>
        </div>
        <div class="card-body login-card-body">
          
          <?php if ($step == 1): ?>
              <!-- STEP 1: VERIFY USER -->
              <p class="login-box-msg">Forgot your password? Enter your details.</p>

              <?php if ($message): ?>
                <div class="alert alert-<?php echo htmlspecialchars($message_type); ?>" role="alert">
                  <?php echo htmlspecialchars($message); ?>
                </div>
              <?php endif; ?>

              <form action="change_password.php" method="post">
                <div class="input-group mb-1">
                  <div class="form-floating">
                    <input id="verifyUsername" type="text" name="username" class="form-control" placeholder="" required />
                    <label for="verifyUsername">Username</label>
                  </div>
                  <div class="input-group-text"><span class="bi bi-person"></span></div>
                </div>
                <div class="input-group mb-1">
                  <div class="form-floating">
                    <input id="verifyEmail" type="email" name="email" class="form-control" placeholder="" required />
                    <label for="verifyEmail">Email</label>
                  </div>
                  <div class="input-group-text"><span class="bi bi-envelope"></span></div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <div class="d-grid gap-2">
                      <button type="submit" name="verify_user" class="btn btn-primary">Verify Account</button>
                    </div>
                  </div>
                </div>
              </form>

          <?php elseif ($step == 2): ?>
              <!-- STEP 2: RESET PASSWORD -->
              <p class="login-box-msg">You are one step away from your new password, recover your password now.</p>

              <?php if ($message): ?>
                <div class="alert alert-<?php echo htmlspecialchars($message_type); ?>" role="alert">
                  <?php echo htmlspecialchars($message); ?>
                </div>
              <?php endif; ?>

              <form action="change_password.php" method="post">
                <div class="input-group mb-1">
                  <div class="form-floating">
                    <input id="newPassword" type="password" name="new_password" class="form-control" placeholder="" required />
                    <label for="newPassword">New Password</label>
                  </div>
                  <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
                </div>
                <div class="input-group mb-1">
                  <div class="form-floating">
                    <input id="confirmPassword" type="password" name="confirm_password" class="form-control" placeholder="" required />
                    <label for="confirmPassword">Confirm Password</label>
                  </div>
                  <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <div class="d-grid gap-2">
                      <button type="submit" name="reset_password" class="btn btn-primary">Change Password</button>
                    </div>
                  </div>
                </div>
              </form>
          <?php endif; ?>

          <p class="mt-3 mb-1">
            <a href="index.php">Login</a>
          </p>
          <p class="mb-0">
            <a href="register.php" class="text-center">Register a new membership</a>
          </p>
        </div>
        <!-- /.login-card-body -->
      </div>
    </div>
    <!-- /.login-box -->
    
    <!--begin::Required Plugin(Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(Bootstrap 5)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <script src="assets/js/adminlte.js"></script>
    <!--end::Required Plugin(AdminLTE)-->
  </body>
  <!--end::Body-->
</html>
