<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
include("db.php");

// Lấy ID nhân viên từ URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Lấy thông tin nhân viên
$employee = $conn->query("SELECT * FROM employees WHERE id=$id")->fetch_assoc();

// Nếu không tồn tại nhân viên
if (!$employee) {
    die("❌ Nhân viên không tồn tại!");
}

// Lấy hợp đồng
$contracts = $conn->query("SELECT * FROM contracts WHERE employee_id=$id ORDER BY start_date DESC");

// Lấy quá trình thăng chức
$promotions = $conn->query("SELECT * FROM promotions WHERE employee_id=$id ORDER BY promotion_date DESC");

// Lấy đánh giá hiệu suất
$reviews = $conn->query("SELECT * FROM performance_reviews WHERE employee_id=$id ORDER BY review_date DESC");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết nhân sự</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">

<a href="QLnhansu.php" class="btn btn-secondary mb-3">⬅ Quay lại danh sách</a>

<h2>📌 Hồ sơ nhân sự: <?php echo $employee['name']; ?></h2>
<div class="card mb-4">
  <div class="card-body">
    <p><strong>👤 Tên:</strong> <?php echo $employee['name']; ?></p>
    <p><strong>🏢 Phòng ban:</strong> <?php echo $employee['department']; ?></p>
    <p><strong>💼 Chức vụ:</strong> <?php echo $employee['position']; ?></p>
    <p><strong>💰 Lương:</strong> <?php echo number_format($employee['salary']); ?> VND</p>
    <p><strong>📅 Ngày vào:</strong> <?php echo $employee['hire_date']; ?></p>
    <p><strong>📞 SĐT:</strong> <?php echo $employee['phone'] ?? 'N/A'; ?></p>
    <p><strong>📧 Email:</strong> <?php echo $employee['email'] ?? 'N/A'; ?></p>
  </div>
</div>

<!-- Hợp đồng -->
<h4>📄 Hợp đồng lao động</h4>
<table class="table table-bordered">
  <thead class="table-dark">
    <tr><th>Loại hợp đồng</th><th>Ngày bắt đầu</th><th>Ngày kết thúc</th><th>Lương</th></tr>
  </thead>
  <tbody>
  <?php while ($c = $contracts->fetch_assoc()): ?>
    <tr>
      <td><?php echo $c['contract_type']; ?></td>
      <td><?php echo $c['start_date']; ?></td>
      <td><?php echo $c['end_date']; ?></td>
      <td><?php echo number_format($c['salary']); ?> VND</td>
    </tr>
  <?php endwhile; ?>
  </tbody>
</table>

<!-- Quá trình thăng chức -->
<h4>📈 Quá trình thăng chức</h4>
<table class="table table-bordered">
  <thead class="table-dark">
    <tr><th>Ngày</th><th>Chức vụ cũ</th><th>Chức vụ mới</th><th>Ghi chú</th></tr>
  </thead>
  <tbody>
  <?php while ($p = $promotions->fetch_assoc()): ?>
    <tr>
      <td><?php echo $p['promotion_date']; ?></td>
      <td><?php echo $p['old_position']; ?></td>
      <td><?php echo $p['new_position']; ?></td>
      <td><?php echo $p['note']; ?></td>
    </tr>
  <?php endwhile; ?>
  </tbody>
</table>

<!-- Đánh giá hiệu suất -->
<h4>⭐ Đánh giá hiệu suất</h4>
<table class="table table-bordered">
  <thead class="table-dark">
    <tr><th>Ngày đánh giá</th><th>Điểm</th><th>Nhận xét</th></tr>
  </thead>
  <tbody>
  <?php while ($r = $reviews->fetch_assoc()): ?>
    <tr>
      <td><?php echo $r['review_date']; ?></td>
      <td><?php echo $r['score']; ?>/100</td>
      <td><?php echo $r['comments']; ?></td>
    </tr>
  <?php endwhile; ?>
  </tbody>
</table>

</body>
</html>
