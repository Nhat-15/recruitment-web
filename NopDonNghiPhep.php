<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
include("db.php");

// Khi submit form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee_id = intval($_POST['employee_id']);
    $reason = $conn->real_escape_string($_POST['reason']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $sql = "INSERT INTO leave_requests (employee_id, reason, start_date, end_date, status)
            VALUES ($employee_id, '$reason', '$start_date', '$end_date', 'pending')";
    if ($conn->query($sql)) {
        echo "<script>alert('Gửi đơn nghỉ phép thành công!');window.location='LeaveRequests.php';</script>";
    } else {
        echo "Lỗi: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Nộp đơn nghỉ phép</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
  <h2 class="mb-4">📝 Nộp đơn nghỉ phép</h2>
  
  <form method="POST" class="card p-4 shadow-sm">
    <div class="mb-3">
      <label for="employee_id" class="form-label">Chọn nhân viên</label>
      <select name="employee_id" id="employee_id" class="form-select" required>
        <option value="">-- Chọn nhân viên --</option>
        <?php
        $employees = $conn->query("SELECT id, name FROM employees ORDER BY name");
        while ($row = $employees->fetch_assoc()) {
            echo "<option value='{$row['id']}'>{$row['name']}</option>";
        }
        ?>
      </select>
    </div>

    <div class="mb-3">
      <label for="reason" class="form-label">Lý do</label>
      <input type="text" name="reason" id="reason" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="start_date" class="form-label">Ngày bắt đầu</label>
      <input type="date" name="start_date" id="start_date" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="end_date" class="form-label">Ngày kết thúc</label>
      <input type="date" name="end_date" id="end_date" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary">📤 Gửi đơn</button>
    <a href="LeaveRequests.php" class="btn btn-secondary">⬅ Quay lại</a>
  </form>
</body>
</html>
