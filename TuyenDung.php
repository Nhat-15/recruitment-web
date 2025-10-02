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
  <title>Tin tuyển dụng mới</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">

  <div class="container">
    <h3 class="mb-4">
      <span class="me-2">📢</span> Tin tuyển dụng mới
    </h3>

    <div class="row">
      <?php
      $sql = "SELECT * FROM jobs ORDER BY created_at DESC";
      $result = $conn->query($sql);
      while($row = $result->fetch_assoc()) {
          echo "
          <div class='col-md-4 mb-3'>
            <div class='card shadow-sm'>
              <div class='card-body'>
                <h5 class='card-title'>".$row['title']."</h5>
                <p class='card-text text-muted'>".$row['requirement']."</p>
                <a href='ungtuyen.php?job_id=".$row['id']."' class='btn btn-primary'>Ứng tuyển</a>

              </div>
            </div>
          </div>";
      }
      ?>
    </div>
  </div>

</body>
</html>
