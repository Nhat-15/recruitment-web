<?php
session_start();
include("db.php");

// Lấy danh sách nhân viên từ bảng employees
$employees = $conn->query("SELECT id, name FROM employees ORDER BY name ASC");

// Check-in
if (isset($_POST['checkin'])) {
    $employee_id = intval($_POST['employee_id']);
    if ($employee_id > 0) {
        $stmt = $conn->prepare("
            INSERT INTO attendance (username, checkin_time) 
            VALUES (
                (SELECT name FROM employees WHERE id = ?),
                NOW()
            )
        ");
        $stmt->bind_param("i", $employee_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Check-out
if (isset($_POST['checkout'])) {
    $employee_id = intval($_POST['employee_id']);
    if ($employee_id > 0) {
        $stmt = $conn->prepare("
            UPDATE attendance 
            SET checkout_time = NOW() 
            WHERE username = (SELECT name FROM employees WHERE id = ?) 
              AND DATE(checkin_time) = CURDATE() 
              AND checkout_time IS NULL
            ORDER BY checkin_time DESC 
            LIMIT 1
        ");
        $stmt->bind_param("i", $employee_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Lấy lịch sử chấm công
$result = $conn->query("
    SELECT username, checkin_time, checkout_time 
    FROM attendance 
    ORDER BY checkin_time DESC 
    LIMIT 20
");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Chấm công</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
  <h2 class="mb-4">🕒 Chấm công nhân viên</h2>

  <!-- Form chọn nhân viên -->
  <form method="post" class="mb-3 row g-3">
    <div class="col-md-4">
      <select name="employee_id" class="form-select" required>
        <option value="">-- Chọn nhân viên --</option>
        <?php while($emp = $employees->fetch_assoc()): ?>
          <option value="<?= $emp['id'] ?>"><?= htmlspecialchars($emp['name']) ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="col-md-8">
      <button type="submit" name="checkin" class="btn btn-success">✅ Check-in</button>
      <button type="submit" name="checkout" class="btn btn-danger">⏹ Check-out</button>
    </div>
  </form>

  <!-- Bảng lịch sử -->
  <table class="table table-bordered">
    <thead class="table-dark">
      <tr>
        <th>Nhân viên</th>
        <th>Giờ vào</th>
        <th>Giờ ra</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['username']) ?></td>
          <td><?= $row['checkin_time'] ?></td>
          <td><?= $row['checkout_time'] ?: "Chưa checkout" ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <a href="Dashboard.php" class="btn btn-secondary">⬅ Quay lại Dashboard</a>
</body>
</html>
