<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
include("db.php");

$result = $conn->query("SELECT name, department, position, salary FROM employees");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Báo cáo lương</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
  <h2 class="mb-4">💰 Báo cáo lương nhân viên</h2>
  <table class="table table-striped table-bordered">
    <thead class="table-dark">
      <tr>
        <th>Tên</th><th>Phòng ban</th><th>Chức vụ</th><th>Lương (VNĐ)</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= htmlspecialchars($row['department']) ?></td>
          <td><?= htmlspecialchars($row['position']) ?></td>
          <td><?= number_format($row['salary'], 0, ',', '.') ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <a href="Dashboard.php" class="btn btn-secondary">⬅ Quay lại Dashboard</a>
</body>
</html>
