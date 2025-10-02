<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
include("db.php");
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
    <title>Thêm nhân sự</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
    <h2 class="mb-4">➕ Thêm nhân sự</h2>
    <form method="post" class="card p-4 shadow-sm">
        <input type="text" name="name" placeholder="Tên" class="form-control mb-2" required>
        <input type="text" name="department" placeholder="Phòng ban" class="form-control mb-2">
        <input type="text" name="position" placeholder="Chức vụ" class="form-control mb-2">
        <input type="number" step="0.01" name="salary" placeholder="Lương" class="form-control mb-2">
        <input type="date" name="hire_date" class="form-control mb-2">
        <button type="submit" name="save" class="btn btn-success">💾 Lưu</button>
        <a href="QLnhansu.php" class="btn btn-secondary">Hủy</a>
    </form>

    <?php
    if(isset($_POST['save'])){
        $sql = "INSERT INTO employees(name,department,position,salary,hire_date)
                VALUES('{$_POST['name']}','{$_POST['department']}','{$_POST['position']}',
                       '{$_POST['salary']}','{$_POST['hire_date']}')";
        if($conn->query($sql)){
            header("Location: QLnhansu.php");
        } else {
            echo "<p class='text-danger mt-3'>❌ Lỗi: ".$conn->error."</p>";
        }
    }
    ?>
</body>
</html>
