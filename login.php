<?php
session_start();
include("db.php");
$error = "";

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = md5($_POST['password']); 

    $sql = "SELECT * FROM users WHERE username=? AND password=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $_SESSION['user'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: Dashboard.php");
        exit;
    } else {
        $error = "Sai tên đăng nhập hoặc mật khẩu!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đăng nhập hệ thống</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="height: 100vh;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <div class="card shadow-lg">
          <div class="card-header bg-primary text-white text-center">
            <h4>Đăng nhập</h4>
          </div>
          <div class="card-body">
            <?php if ($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
            <form method="post">
              <div class="mb-3">
                <label class="form-label">Tên đăng nhập</label>
                <input type="text" name="username" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Mật khẩu</label>
                <input type="password" name="password" class="form-control" required>
              </div>
              <button type="submit" name="login" class="btn btn-primary w-100">Đăng nhập</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
