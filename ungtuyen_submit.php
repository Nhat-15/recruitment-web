<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
include("includes/db.php");
?>

<?php
include("db.php");
?>
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
<?php


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $job_id   = intval($_POST['job_id']);
    $fullname = $_POST['fullname'];
    $email    = $_POST['email'];
    $phone    = $_POST['phone'];
    $message  = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO applications (job_id, fullname, email, phone, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $job_id, $fullname, $email, $phone, $message);

    if ($stmt->execute()) {
        echo "<script>alert('Ứng tuyển thành công!'); window.location='Tuyendung.php';</script>";
    } else {
        echo "Lỗi: " . $stmt->error;
    }
    $stmt->close();
}
?>
