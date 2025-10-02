<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
include("db.php");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý nhân sự</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container-fluid p-4">

<!-- Thanh menu -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="Dashboard.php">🏢 HRMS</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="Dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link active" href="QLnhansu.php">Quản lý nhân sự</a></li>
        <li class="nav-item"><a class="nav-link" href="jobs.php">Tuyển dụng</a></li>
      </ul>
      <span class="navbar-text text-white me-3">
        Xin chào, <?php echo $_SESSION['user']; ?> (<?php echo $_SESSION['role']; ?>)
      </span>
      <a href="logout.php" class="btn btn-outline-light btn-sm">Đăng xuất</a>
    </div>
  </div>
</nav>

<div class="container">
    <h2 class="mb-4">👨‍💼 Danh sách nhân sự</h2>

    <!-- Shortcut nhanh -->
    <div class="mb-3">
        <a href="ThemNhanSu.php" class="btn btn-success">➕ Thêm nhân sự</a>
        <a href="DuyetDon.php" class="btn btn-primary">📋 Duyệt đơn nghỉ phép</a>
        <a href="BaoCaoLuong.php" class="btn btn-info">💰 Báo cáo lương</a>
        <a href="ChamCong.php" class="btn btn-warning">⏰ Chấm công</a>
    </div>

    <!-- Bộ lọc -->
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <select name="department" class="form-select">
                <option value="">-- Lọc theo phòng ban --</option>
                <?php
                $depts = $conn->query("SELECT DISTINCT department FROM employees");
                while ($d = $depts->fetch_assoc()) {
                    $selected = (isset($_GET['department']) && $_GET['department'] == $d['department']) ? "selected" : "";
                    echo "<option $selected>{$d['department']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-4">
            <select name="position" class="form-select">
                <option value="">-- Lọc theo chức danh --</option>
                <?php
                $positions = $conn->query("SELECT DISTINCT position FROM employees");
                while ($p = $positions->fetch_assoc()) {
                    $selected = (isset($_GET['position']) && $_GET['position'] == $p['position']) ? "selected" : "";
                    echo "<option $selected>{$p['position']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-4">
            <button class="btn btn-dark" type="submit">🔍 Lọc</button>
            <a href="QLnhansu.php" class="btn btn-secondary">❌ Xóa lọc</a>
        </div>
    </form>

    <!-- Danh sách nhân sự -->
    <table class="table table-hover table-bordered shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>ID</th><th>Tên</th><th>Phòng ban</th><th>Chức vụ</th>
                <th>Lương</th><th>Ngày vào</th><th>Hành động</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT * FROM employees WHERE 1=1";
        if (!empty($_GET['department'])) {
            $dept = $conn->real_escape_string($_GET['department']);
            $sql .= " AND department='$dept'";
        }
        if (!empty($_GET['position'])) {
            $pos = $conn->real_escape_string($_GET['position']);
            $sql .= " AND position='$pos'";
        }
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['id']}</td>
                <td><a href='NhanSuChiTiet.php?id={$row['id']}'>{$row['name']}</a></td>
                <td>{$row['department']}</td>
                <td>{$row['position']}</td>
                <td>{$row['salary']}</td>
                <td>{$row['hire_date']}</td>
                <td>
                    <a href='ChinhsuaNhanSu.php?id={$row['id']}' class='btn btn-sm btn-warning'>✏️ Sửa</a>
                    <a href='XoaNhanSu.php?id={$row['id']}' class='btn btn-sm btn-danger'
                       onclick='return confirm(\"Xóa nhân sự này?\")'>🗑️ Xóa</a>
                </td>
            </tr>";
        }
        ?>
        </tbody>
    </table>

    <a href="Dashboard.php" class="btn btn-secondary">⬅ Dashboard</a>
</div>

</body>
</html>
