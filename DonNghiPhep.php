<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
include("db.php");

// Nếu admin duyệt đơn
if (isset($_GET['approve'])) {
    $id = intval($_GET['approve']);
    $conn->query("UPDATE leave_requests SET status='Approved' WHERE id=$id");
}
// Nếu admin từ chối
if (isset($_GET['reject'])) {
    $id = intval($_GET['reject']);
    $conn->query("UPDATE leave_requests SET status='Rejected' WHERE id=$id");
}

$result = $conn->query("SELECT * FROM leave_requests ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đơn nghỉ phép</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
  <h2 class="mb-4">📩 Danh sách đơn nghỉ phép</h2>
  <table class="table table-bordered table-hover">
    <thead class="table-dark">
      <tr>
        <th>ID</th><th>Nhân viên</th><th>Từ ngày</th><th>Đến ngày</th><th>Lý do</th><th>Trạng thái</th><th>Hành động</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['employee_name']) ?></td>
          <td><?= $row['start_date'] ?></td>
          <td><?= $row['end_date'] ?></td>
          <td><?= htmlspecialchars($row['reason']) ?></td>
          <td>
            <?php if ($row['status'] == 'Pending'): ?>
              <span class="badge bg-warning">Chờ duyệt</span>
            <?php elseif ($row['status'] == 'Approved'): ?>
              <span class="badge bg-success">Đã duyệt</span>
            <?php else: ?>
              <span class="badge bg-danger">Từ chối</span>
            <?php endif; ?>
          </td>
          <td>
            <a href="?approve=<?= $row['id'] ?>" class="btn btn-sm btn-success">✔ Duyệt</a>
            <a href="?reject=<?= $row['id'] ?>" class="btn btn-sm btn-danger">✖ Từ chối</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <a href="Dashboard.php" class="btn btn-secondary">⬅ Quay lại Dashboard</a>
</body>
</html>
