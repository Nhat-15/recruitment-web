<?php
include("db.php");
$id = intval($_GET['id']);
$status = $_GET['status'];

$conn->query("UPDATE leave_requests SET status='$status' WHERE id=$id");

header("Location: LeaveRequests.php");
