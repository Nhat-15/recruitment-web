<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
include("includes/db.php");
?>

<?php include("db.php"); ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="Dashboard.php">🏢 HRMS</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="Dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="QLnhansu.php">Quản lý nhân sự</a></li>
        <li class="nav-item"><a class="nav-link" href="jobs.php">Tuyển dụng</a></li>
      </ul>
      <span class="navbar-text text-white me-3">
        Xin chào, <?php echo $_SESSION['user']; ?> (<?php echo $_SESSION['role']; ?>)
      </span>
      <a href="logout.php" class="btn btn-outline-light btn-sm">Đăng xuất</a>
    </div>
  </div>
</nav>


<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Ứng tuyển</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">

<div class="container">
  <h3 class="mb-4">📄 Ứng tuyển vào vị trí</h3>

  <?php
  if(isset($_GET['job_id'])) {
      $job_id = intval($_GET['job_id']);
      $job = $conn->query("SELECT * FROM jobs WHERE id=$job_id")->fetch_assoc();
      echo "<h4 class='mb-3 text-primary'>".$job['title']."</h4>";
  } else {
      echo "<p class='text-danger'>Không tìm thấy công việc!</p>";
      exit;
  }
  ?>

  <form action="ungtuyen_submit.php" method="POST" class="card p-4 shadow-sm">
    <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">

    <div class="mb-3">
      <label class="form-label">Họ và tên</label>
      <input type="text" name="fullname" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Số điện thoại</label>
      <input type="text" name="phone" class="form-control">
    </div>

    <div class="mb-3">
      <label class="form-label">Lời nhắn</label>
      <textarea name="message" class="form-control" rows="3"></textarea>
    </div>

    <button type="submit" class="btn btn-success">Gửi ứng tuyển</button>
  </form>
</div>

</body>
</html>
