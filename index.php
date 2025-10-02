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
  <title>HRMS - Hệ thống quản lý nhân sự</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .navbar { box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    .card { border-radius: 12px; }
    footer { background: #0d6efd; color: white; padding: 15px; margin-top: 30px; }
    footer a { color: #ffc107; text-decoration: none; }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php"><i class="fa-solid fa-people-group"></i> HRMS</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="Dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="QLnhansu.php">Quản lý nhân sự</a></li>
        <li class="nav-item"><a class="nav-link" href="Tuyendung.php">Tuyển dụng</a></li>
        <li class="nav-item"><a class="nav-link" href="Ungvien.php">Ứng viên</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Đăng xuất</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Banner -->
<div id="banner" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="https://source.unsplash.com/1600x500/?office,team" class="d-block w-100" alt="Banner">
      <div class="carousel-caption d-none d-md-block">
        <h2>Chào mừng đến với HRMS</h2>
        <p>Hệ thống quản lý nhân sự chuyên nghiệp cho doanh nghiệp</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="https://source.unsplash.com/1600x500/?business,meeting" class="d-block w-100" alt="Banner 2">
      <div class="carousel-caption d-none d-md-block">
        <h2>Tuyển dụng nhanh chóng</h2>
        <p>Kết nối nhân tài và công việc phù hợp</p>
      </div>
    </div>
  </div>
</div>

<div class="container my-5">

  <!-- Tin tuyển dụng -->
  <h3 class="mb-4"><i class="fa-solid fa-bullhorn text-danger"></i> Tin tuyển dụng mới</h3>
  <div class="row">
    <?php
    $jobs = $conn->query("SELECT * FROM jobs ORDER BY created_at DESC LIMIT 4");
    if ($jobs->num_rows > 0) {
      while($row = $jobs->fetch_assoc()) {
    $title = htmlspecialchars($row['title']);
    // thử cả 2 tên cột nếu chưa chắc
    $req = htmlspecialchars($row['requirement'] ?? $row['requirements'] ?? '');

    $id = (int)$row['id'];

    echo "
    <div class='col-md-3 mb-3'>
      <div class='card shadow-sm h-100'>
        <div class='card-body'>
          <h5 class='card-title'><i class='fa-solid fa-briefcase'></i> {$title}</h5>
          <p class='card-text'>Yêu cầu: {$req}</p>
          <a href='ungtuyen.php?job_id={$id}' class='btn btn-primary btn-sm'>Ứng tuyển</a>
        </div>
      </div>
    </div>";
}

    } else {
      echo "<p>Chưa có tin tuyển dụng nào.</p>";
    }
    ?>
  </div>

  <!-- Ứng viên mới -->
  <h3 class="mt-5 mb-4"><i class="fa-solid fa-user-check text-success"></i> Ứng viên mới nhất</h3>
  <div class="row">
    <?php
    $apps = $conn->query("SELECT applications.*, jobs.title 
                          FROM applications 
                          JOIN jobs ON applications.job_id = jobs.id 
                          ORDER BY applications.created_at DESC LIMIT 4");
    if ($apps->num_rows > 0) {
      while($app = $apps->fetch_assoc()) {
        echo "
        <div class='col-md-3 mb-3'>
          <div class='card shadow-sm h-100'>
            <div class='card-body'>
              <h6 class='card-title'><i class='fa-solid fa-user'></i> ".$app['fullname']."</h6>
              <p class='mb-1'><i class='fa-solid fa-envelope'></i> ".$app['email']."</p>
              <p class='mb-1'><i class='fa-solid fa-briefcase'></i> ".$app['title']."</p>
              <small class='text-muted'>".$app['created_at']."</small>
            </div>
          </div>
        </div>";
      }
    } else {
      echo "<p>Chưa có ứng viên nào.</p>";
    }
    ?>
  </div>

</div>

<!-- Footer -->
<footer class="text-center">
  <p>© 2025 HRMS - Hệ thống quản lý nhân sự. <br>
  Liên hệ: <a href="mailto:hr@company.com">hr@company.com</a> | Hotline: 0123-456-789</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
