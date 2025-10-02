<?php 
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
include("db.php");

// ===================== THỐNG KÊ =====================
// Tổng nhân sự
$totalEmployees = $conn->query("SELECT COUNT(*) AS total FROM employees")->fetch_assoc()['total'] ?? 0;

// Tổng phòng ban
$totalDepartments = $conn->query("SELECT COUNT(DISTINCT department) AS total FROM employees")->fetch_assoc()['total'] ?? 0;

// Vị trí tuyển
$totalJobs = $conn->query("SELECT COUNT(*) AS total FROM jobs")->fetch_assoc()['total'] ?? 0;

// Hồ sơ ứng tuyển
$totalApplicants = $conn->query("SELECT COUNT(*) AS total FROM applicants")->fetch_assoc()['total'] ?? 0;

// Nhân sự mới nhất
$latestEmployees = $conn->query("SELECT name, position, department, hire_date FROM employees ORDER BY hire_date DESC LIMIT 5");

// Ứng viên mới nhất
$latestApplicants = $conn->query("SELECT name, job_id, applied_at FROM applicants ORDER BY applied_at DESC LIMIT 5");

// Thống kê nhân sự theo phòng ban
$deptStats = $conn->query("SELECT department, COUNT(*) as count FROM employees GROUP BY department");
$deptLabels = [];
$deptCounts = [];
while ($row = $deptStats->fetch_assoc()) {
    $deptLabels[] = $row['department'];
    $deptCounts[] = $row['count'];
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - HRMS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body { background-color: #f5f6fa; }
    .sidebar { height: 100vh; background: #2c3e50; color: #fff; position: fixed; }
    .sidebar a { color: #fff; text-decoration: none; display: block; padding: 12px; }
    .sidebar a:hover { background: #34495e; }
    .card { border-radius: 12px; }
    .main { margin-left: 220px; }
  </style>
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-2 sidebar p-0">
      <h4 class="text-center py-3 border-bottom">🏢 HRMS</h4>
      <a href="Dashboard.php"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
      <a href="QLnhansu.php"><i class="fa-solid fa-users"></i> Quản lý nhân sự</a>
      <a href="ThemNhanSu.php"><i class="fa-solid fa-user-plus"></i> Thêm nhân sự</a>
      <a href="jobs.php"><i class="fa-solid fa-briefcase"></i> Tuyển dụng</a>
      <a href="ungvien.php"><i class="fa-solid fa-id-card"></i> Hồ sơ ứng tuyển</a>
      <a href="#"><i class="fa-solid fa-sack-dollar"></i> Quản lý lương</a>
      <a href="#"><i class="fa-solid fa-business-time"></i> Chấm công</a>
      <a href="#"><i class="fa-solid fa-gear"></i> Cài đặt</a>
      <a href="DonNghiPhep.php"><i class="fa-solid fa-envelope-open-text"></i> Đơn nghỉ phép</a>
      <a href="BaoCaoLuong.php"><i class="fa-solid fa-sack-dollar"></i> Báo cáo lương</a>
      <a href="ChamCong.php"><i class="fa-solid fa-business-time"></i> Chấm công</a>
      <a href="ChamCong.php" class="btn btn-warning">⏰ Chấm công</a>
      <a href="logout.php" class="text-danger"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
    </div>

    <!-- Main content -->
    <div class="col-md-10 main p-4">
      <h2 class="mb-4"><i class="fa-solid fa-chart-line"></i> Dashboard</h2>
      <p>Xin chào, <b><?php echo $_SESSION['user']; ?></b> (<?php echo $_SESSION['role']; ?>)</p>

      <!-- Stats -->
      <div class="row mb-4">
        <div class="col-md-3">
          <div class="card text-center shadow-sm p-3 text-bg-primary">
            <h5><i class="fa-solid fa-users"></i> Nhân sự</h5>
            <h3><?php echo $totalEmployees; ?></h3>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card text-center shadow-sm p-3 text-bg-success">
            <h5><i class="fa-solid fa-building"></i> Phòng ban</h5>
            <h3><?php echo $totalDepartments; ?></h3>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card text-center shadow-sm p-3 text-bg-warning">
            <h5><i class="fa-solid fa-briefcase"></i> Vị trí tuyển</h5>
            <h3><?php echo $totalJobs; ?></h3>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card text-center shadow-sm p-3 text-bg-danger">
            <h5><i class="fa-solid fa-id-card"></i> Hồ sơ ứng tuyển</h5>
            <h3><?php echo $totalApplicants; ?></h3>
          </div>
        </div>
      </div>

      <!-- Charts -->
      <div class="row mb-4">
        <div class="col-md-6">
          <div class="card shadow-sm p-3">
            <h5><i class="fa-solid fa-chart-pie"></i> Nhân sự theo phòng ban</h5>
            <canvas id="deptChart"></canvas>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card shadow-sm p-3">
            <h5><i class="fa-solid fa-chart-bar"></i> Tổng quan</h5>
            <canvas id="barChart"></canvas>
          </div>
        </div>
      </div>

      <!-- Tables -->
      <div class="row">
        <div class="col-md-6">
          <div class="card shadow-sm p-3">
            <h5><i class="fa-solid fa-user-clock"></i> Nhân sự mới nhất</h5>
            <table class="table table-striped">
              <thead><tr><th>Tên</th><th>Vị trí</th><th>Phòng ban</th><th>Ngày vào</th></tr></thead>
              <tbody>
                <?php while($row = $latestEmployees->fetch_assoc()): ?>
                  <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['position']) ?></td>
                    <td><?= htmlspecialchars($row['department']) ?></td>
                    <td><?= htmlspecialchars($row['hire_date']) ?></td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card shadow-sm p-3">
            <h5><i class="fa-solid fa-user-pen"></i> Ứng viên mới nhất</h5>
            <table class="table table-striped">
              <thead><tr><th>Tên</th><th>Job ID</th><th>Ngày ứng tuyển</th></tr></thead>
              <tbody>
                <?php while($row = $latestApplicants->fetch_assoc()): ?>
                  <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['job_id']) ?></td>
                    <td><?= htmlspecialchars($row['applied_at']) ?></td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
const ctx = document.getElementById('deptChart');
new Chart(ctx, {
  type: 'pie',
  data: {
    labels: <?php echo json_encode($deptLabels); ?>,
    datasets: [{
      data: <?php echo json_encode($deptCounts); ?>,
      backgroundColor: ['#3498db','#2ecc71','#f1c40f','#e74c3c','#9b59b6']
    }]
  }
});

const ctx2 = document.getElementById('barChart');
new Chart(ctx2, {
  type: 'bar',
  data: {
    labels: ["Nhân sự", "Phòng ban", "Công việc", "Ứng viên"],
    datasets: [{
      label: 'Thống kê',
      data: [<?php echo $totalEmployees; ?>, <?php echo $totalDepartments; ?>, <?php echo $totalJobs; ?>, <?php echo $totalApplicants; ?>],
      backgroundColor: '#2980b9'
    }]
  },
  options: { scales: { y: { beginAtZero: true } } }
});
</script>
</body>
</html>
